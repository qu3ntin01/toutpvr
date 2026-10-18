#!/usr/bin/env bash
# ============================================================================
#  ToutPVR — installeur Linux universel
#  Distributions prises en charge :
#    - Debian 11/12, Ubuntu 20.04/22.04/24.04, Linux Mint, Pop!_OS, Raspberry Pi OS
#    - RHEL 8/9/10, AlmaLinux 8/9/10, Rocky Linux 8/9/10, CentOS Stream, Oracle Linux
#    - Fedora 39+
#    - openSUSE Leap 15.x / Tumbleweed, SLES 15
#    - Arch Linux, Manjaro
#    - Alpine 3.18+
#
#  Utilisation (en root ou via sudo) :
#    sudo bash install.sh                       # installation interactive minimale
#    sudo bash install.sh --yes --port 8080 --admin-password 'MonMotDePasse!'
#    sudo bash install.sh --license TP1-XXXXX-XXXXX-XXXXX-XXXXX
#    sudo bash install.sh --upgrade             # mise à niveau en place
#    sudo bash install.sh --uninstall [--purge] # désinstallation (--purge supprime les données)
#
#  Deux dispositions sont gérées :
#    - arbre source (pyproject.toml + toutpvr/)        -> pip install <dossier>
#    - arbre de diffusion (dossier wheels/ à côté du script) -> pip install --no-index --find-links wheels toutpvr
#      (roue choisie selon l'interpréteur et l'architecture ; dépendances depuis PyPI)
#
#  Variables d'environnement acceptées : TP_SOURCE (dossier ou archive .tar.gz),
#  TP_PREFIX, TP_PORT, TP_LANG, TP_ADMIN_USER, TP_ADMIN_PASSWORD, TP_LICENSE, TP_PORTAL_URL,
#  TP_CONFIG_DIR, TP_DATA_DIR, TP_BIN_LINK, TP_DESKTOP_DIR.
#
#  Icône et lanceur de bureau : sur un poste avec environnement graphique, l'installeur ajoute l'entrée « ToutPVR »
#  au menu des applications (/usr/share/applications/toutpvr.desktop, icônes hicolor) ; ignorée sur un serveur sans
#  interface, ou avec --no-desktop. Les icônes viennent de icons/ (arbre de diffusion ou server/icons/) ou du kit de
#  marque (../brand/assets/app/ dans l'arbre source du dépôt).
# ============================================================================
set -euo pipefail

VERSION_SCRIPT="1.0.0"
PRODUCT="ToutPVR"
SERVICE_USER="toutpvr"
PREFIX="${TP_PREFIX:-/opt/toutpvr}"
CONFIG_DIR="${TP_CONFIG_DIR:-/etc/toutpvr}"
CONFIG_FILE=""   # défini après lecture des arguments : $CONFIG_DIR/config.yaml
DATA_DIR="${TP_DATA_DIR:-/var/lib/toutpvr}"
BIN_LINK="${TP_BIN_LINK:-/usr/local/bin/toutpvr}"
PORT="${TP_PORT:-8080}"
LANG_CODE="${TP_LANG:-fr}"
ADMIN_USER="${TP_ADMIN_USER:-admin}"
ADMIN_PASSWORD="${TP_ADMIN_PASSWORD:-}"
LICENSE_KEY="${TP_LICENSE:-}"
PORTAL_URL="${TP_PORTAL_URL:-https://toutpvr.example}"
SOURCE="${TP_SOURCE:-}"
# Dépôts de diffusion : GitLab (repo.qu3ntin.com) en premier, GitHub en secours.
REPO_PRIMARY="${TP_REPO_URL:-https://repo.qu3ntin.com/toutpvr/toutpvr}"
REPO_FALLBACK="${TP_REPO_FALLBACK_URL:-https://github.com/qu3ntin01/toutpvr}"
CHANNEL="${TP_CHANNEL:-main}"   # main = versions finales, dev = versions en développement
DB_KIND="sqlite"
DB_URL=""
ASSUME_YES=0
WANT_SERVICE=1
WANT_FIREWALL=1
WANT_FFMPEG=1
WANT_SYSTEM_DEPS=1
WANT_DESKTOP=1
DESKTOP_DIR="${TP_DESKTOP_DIR:-}"   # racine « share » (applications/ et icons/hicolor/) ; vide = /usr/share avec détection du bureau
WHEEL_DIR=""
WHEEL_HINT=""       # dossier wheels/ repéré avant le choix de Python (arbre de diffusion)
MODE="install"          # install | upgrade | uninstall
PURGE=0
DRY_RUN=0
SIMULATE_OS=""
PYTHON_BIN=""

# ----------------------------------------------------------------------------
#  Affichage
# ----------------------------------------------------------------------------
if [ -t 1 ]; then
  C_BOLD="\033[1m"; C_DIM="\033[2m"; C_OK="\033[32m"; C_WARN="\033[33m"; C_ERR="\033[31m"; C_INFO="\033[36m"; C_RESET="\033[0m"
else
  C_BOLD=""; C_DIM=""; C_OK=""; C_WARN=""; C_ERR=""; C_INFO=""; C_RESET=""
fi
log()  { printf "%b›%b %s\n" "$C_INFO" "$C_RESET" "$*"; }
ok()   { printf "%b✔%b %s\n" "$C_OK" "$C_RESET" "$*"; }
warn() { printf "%b!%b %s\n" "$C_WARN" "$C_RESET" "$*" >&2; }
die()  { printf "%b✖ %s%b\n" "$C_ERR" "$*" "$C_RESET" >&2; exit 1; }
step() { printf "\n%b%s%b\n" "$C_BOLD" "$*" "$C_RESET"; }

usage() {
  cat <<EOF
$PRODUCT — installeur Linux v$VERSION_SCRIPT

Usage : sudo bash install.sh [options]

Options principales :
  -y, --yes                 Mode non interactif (valeurs par défaut ou options)
  --port PORT               Port HTTP du panneau (défaut : $PORT)
  --lang CODE               Langue (fr en es de it pt nl pl ru uk tr ar hi bn id vi th zh ja ko)
  --admin-user NOM          Compte propriétaire (défaut : admin)
  --admin-password MDP      Mot de passe du propriétaire (généré si absent)
  --license CLÉ             Clé de licence TP1-XXXXX-... à activer après l'installation
  --portal-url URL          Portail de licence / mises à jour
  --db sqlite|mysql         Base de données (défaut : sqlite)
  --db-url URL              URL SQLAlchemy MySQL (mysql+pymysql://user:mdp@hote/toutpvr)
  --source CHEMIN|URL       Dossier source, archive .tar.gz locale ou URL (défaut : dossier du script)
  --channel main|dev        Sans --source : télécharge la dernière version finale (main) ou de développement (dev)
                            depuis repo.qu3ntin.com (GitLab), avec GitHub en secours
  --prefix DIR              Dossier d'installation de l'environnement Python (défaut : $PREFIX)
  --data-dir DIR            Dossier des données (défaut : $DATA_DIR)
  --config-dir DIR          Dossier de la configuration (défaut : $CONFIG_DIR)
  --bin-link CHEMIN         Lanceur global « toutpvr » (défaut : $BIN_LINK)
  --skip-system-deps        Ne pas installer de paquets système (Python, ffmpeg…) : à utiliser si déjà présents
  --no-service              Ne pas créer le service systemd / OpenRC
  --no-firewall             Ne pas ouvrir le port dans firewalld / ufw
  --no-ffmpeg               Ne pas installer ffmpeg
  --no-desktop              Ne pas créer l'entrée de menu « ToutPVR » ni installer les icônes (serveur sans interface)
  --desktop-dir DIR         Racine « share » de l'entrée de menu et des icônes (défaut : /usr/share, si un bureau est détecté)
  --upgrade                 Mettre à niveau une installation existante (conserve config et données)
  --uninstall [--purge]     Désinstaller (--purge supprime aussi les données et la configuration)
  --dry-run                 Afficher les actions sans les exécuter
  --simulate-os ID:VERSION  Simuler une distribution pour le dry-run (ex. almalinux:10)
  -h, --help                Cette aide
EOF
}

# ----------------------------------------------------------------------------
#  Arguments
# ----------------------------------------------------------------------------
while [ $# -gt 0 ]; do
  case "$1" in
    -y|--yes) ASSUME_YES=1 ;;
    --channel) CHANNEL="$2"; shift ;;
    --port) PORT="$2"; shift ;;
    --lang) LANG_CODE="$2"; shift ;;
    --admin-user) ADMIN_USER="$2"; shift ;;
    --admin-password) ADMIN_PASSWORD="$2"; shift ;;
    --license) LICENSE_KEY="$2"; shift ;;
    --portal-url) PORTAL_URL="$2"; shift ;;
    --db) DB_KIND="$2"; shift ;;
    --db-url) DB_URL="$2"; DB_KIND="mysql"; shift ;;
    --source) SOURCE="$2"; shift ;;
    --prefix) PREFIX="$2"; shift ;;
    --data-dir) DATA_DIR="$2"; shift ;;
    --config-dir) CONFIG_DIR="$2"; shift ;;
    --bin-link) BIN_LINK="$2"; shift ;;
    --skip-system-deps) WANT_SYSTEM_DEPS=0 ;;
    --no-service) WANT_SERVICE=0 ;;
    --no-firewall) WANT_FIREWALL=0 ;;
    --no-ffmpeg) WANT_FFMPEG=0 ;;
    --no-desktop) WANT_DESKTOP=0 ;;
    --desktop-dir) DESKTOP_DIR="$2"; shift ;;
    --upgrade) MODE="upgrade" ;;
    --uninstall) MODE="uninstall" ;;
    --purge) PURGE=1 ;;
    --dry-run) DRY_RUN=1 ;;
    --simulate-os) SIMULATE_OS="$2"; shift ;;
    -h|--help) usage; exit 0 ;;
    *) die "Option inconnue : $1 (voir --help)" ;;
  esac
  shift
done

CONFIG_FILE="$CONFIG_DIR/config.yaml"
DESKTOP_ROOT="${DESKTOP_DIR:-/usr/share}"

run() {
  if [ "$DRY_RUN" = 1 ]; then printf "%b  \$ %s%b\n" "$C_DIM" "$*" "$C_RESET"; return 0; fi
  "$@"
}

# ----------------------------------------------------------------------------
#  Pré-requis
# ----------------------------------------------------------------------------
if [ "$DRY_RUN" = 0 ] && [ "$(id -u)" -ne 0 ]; then
  die "Ce script doit être lancé en root : sudo bash install.sh"
fi

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
if [ -n "$SOURCE" ] && [ -d "$SOURCE/wheels" ]; then WHEEL_HINT="$SOURCE/wheels"
elif [ -z "$SOURCE" ] && [ -d "$SCRIPT_DIR/wheels" ]; then WHEEL_HINT="$SCRIPT_DIR/wheels"; fi
compgen -G "$WHEEL_HINT/toutpvr-*.whl" >/dev/null 2>&1 || WHEEL_HINT=""

# ----------------------------------------------------------------------------
#  Détection de la distribution
# ----------------------------------------------------------------------------
OS_ID=""; OS_LIKE=""; OS_VERSION=""; PKG=""
detect_os() {
  if [ -n "$SIMULATE_OS" ]; then
    OS_ID="${SIMULATE_OS%%:*}"; OS_VERSION="${SIMULATE_OS#*:}"; [ "$OS_VERSION" = "$OS_ID" ] && OS_VERSION=""
  elif [ -r /etc/os-release ]; then
    # shellcheck disable=SC1091
    . /etc/os-release
    OS_ID="${ID:-}"; OS_LIKE="${ID_LIKE:-}"; OS_VERSION="${VERSION_ID:-}"
  else
    die "Impossible de lire /etc/os-release : distribution non reconnue."
  fi
  case "$OS_ID" in
    debian|ubuntu|linuxmint|pop|raspbian|elementary|zorin|kali|neon) PKG="apt" ;;
    rhel|almalinux|rocky|centos|ol|fedora|amzn|cloudlinux) PKG="dnf" ;;
    opensuse*|sles|suse) PKG="zypper" ;;
    arch|manjaro|endeavouros|garuda) PKG="pacman" ;;
    alpine) PKG="apk" ;;
    *)
      case " $OS_LIKE " in
        *debian*|*ubuntu*) PKG="apt" ;;
        *rhel*|*fedora*|*centos*) PKG="dnf" ;;
        *suse*) PKG="zypper" ;;
        *arch*) PKG="pacman" ;;
        *) die "Distribution non prise en charge : $OS_ID ($OS_LIKE). Installez Python 3.10+, ffmpeg et relancez avec --source." ;;
      esac ;;
  esac
  if [ "$PKG" = "dnf" ] && ! command -v dnf >/dev/null 2>&1 && command -v yum >/dev/null 2>&1; then PKG="yum"; fi
  ok "Distribution : ${OS_ID} ${OS_VERSION} (gestionnaire : $PKG)"
}

# ----------------------------------------------------------------------------
#  Python : trouve un interpréteur >= 3.10, sinon l'installe
# ----------------------------------------------------------------------------
find_python() {
  local cand tag
  for cand in python3.13 python3.12 python3.11 python3.10 python3; do
    if command -v "$cand" >/dev/null 2>&1; then
      if "$cand" -c 'import sys; sys.exit(0 if sys.version_info >= (3,10) else 1)' 2>/dev/null; then
        # Arbre de diffusion : l'interpréteur doit avoir une roue compilée correspondante (cp311, cp312…).
        if [ -n "$WHEEL_HINT" ]; then
          tag="$("$cand" -c 'import sys; print("cp%d%d" % sys.version_info[:2])')"
          compgen -G "$WHEEL_HINT/toutpvr-*-$tag-$tag-*.whl" >/dev/null || continue
        fi
        PYTHON_BIN="$(command -v "$cand")"; return 0
      fi
    fi
  done
  return 1
}

# Tags Python des roues fournies (pour les messages d'erreur).
wheel_tags() { local w; for w in "$WHEEL_HINT"/toutpvr-*.whl; do basename "$w" | cut -d- -f3; done | sort -u | tr '\n' ' '; }

pkg_update() {
  case "$PKG" in
    apt) run env DEBIAN_FRONTEND=noninteractive apt-get update -q ;;
    dnf|yum) run "$PKG" -q makecache ;;
    zypper) run zypper --non-interactive refresh ;;
    pacman) run pacman -Sy --noconfirm ;;
    apk) run apk update ;;
  esac
}

pkg_install() {
  case "$PKG" in
    apt) run env DEBIAN_FRONTEND=noninteractive apt-get install -y -q --no-install-recommends "$@" ;;
    dnf|yum) run "$PKG" install -y -q "$@" ;;
    zypper) run zypper --non-interactive install -y "$@" ;;
    pacman) run pacman -S --noconfirm --needed "$@" ;;
    apk) run apk add --no-cache "$@" ;;
  esac
}

install_system_deps() {
  step "Dépendances système"
  if [ "$WANT_SYSTEM_DEPS" = 0 ]; then
    find_python || die "Aucun interpréteur Python >= 3.10 trouvé (--skip-system-deps : installez-le d'abord)."
    ok "Python : $PYTHON_BIN (paquets système ignorés)"
    return 0
  fi
  pkg_update
  local major="${OS_VERSION%%.*}"
  case "$PKG" in
    apt)
      # Ubuntu 20.04 : python3 = 3.8 → on tente python3.10 via deadsnakes, sinon 3.11 natif ailleurs.
      pkg_install ca-certificates curl tar gzip libgl1 libglib2.0-0 || true
      if ! find_python; then
        if [ "$OS_ID" = "ubuntu" ] && [ "$major" = "20" ]; then
          pkg_install software-properties-common
          run add-apt-repository -y ppa:deadsnakes/ppa
          pkg_update
          pkg_install python3.10 python3.10-venv python3.10-dev
        else
          pkg_install python3 python3-venv python3-dev python3-pip
        fi
      else
        local v; v="$("$PYTHON_BIN" -c 'import sys; print(f"{sys.version_info[0]}.{sys.version_info[1]}")')"
        pkg_install "python${v}-venv" "python${v}-dev" 2>/dev/null || pkg_install python3-venv python3-dev
      fi
      [ "$WANT_FFMPEG" = 1 ] && { pkg_install ffmpeg || warn "ffmpeg indisponible : enregistrement via OpenCV."; }
      ;;
    dnf|yum)
      pkg_install ca-certificates curl tar gzip mesa-libGL glib2 || true
      if ! find_python; then
        case "$major" in
          8) pkg_install python3.11 python3.11-devel ;;          # RHEL/Alma/Rocky 8 : python3 = 3.6
          9) pkg_install python3.11 python3.11-devel ;;          # RHEL/Alma/Rocky 9 : python3 = 3.9
          *) pkg_install python3 python3-devel python3-pip ;;    # Alma 10 / Fedora : python3 >= 3.12
        esac
      fi
      if [ "$WANT_FFMPEG" = 1 ]; then
        if [ "$OS_ID" = "fedora" ]; then
          pkg_install ffmpeg-free || warn "ffmpeg-free indisponible."
        else
          # EPEL + RPM Fusion fournissent ffmpeg sur la famille RHEL (8, 9 et 10).
          pkg_install epel-release 2>/dev/null || run "$PKG" install -y -q "https://dl.fedoraproject.org/pub/epel/epel-release-latest-${major}.noarch.rpm" || true
          if command -v crb >/dev/null 2>&1; then run crb enable || true; else run "$PKG" config-manager --set-enabled crb 2>/dev/null || run "$PKG" config-manager --set-enabled powertools 2>/dev/null || true; fi
          run "$PKG" install -y -q "https://mirrors.rpmfusion.org/free/el/rpmfusion-free-release-${major}.noarch.rpm" 2>/dev/null || true
          pkg_install ffmpeg || warn "ffmpeg indisponible (RPM Fusion) : enregistrement via OpenCV."
        fi
      fi
      ;;
    zypper)
      pkg_install ca-certificates curl tar gzip Mesa-libGL1 libglib-2_0-0 || true
      find_python || pkg_install python311 python311-devel python311-pip || pkg_install python3 python3-devel python3-pip
      [ "$WANT_FFMPEG" = 1 ] && { pkg_install ffmpeg || pkg_install ffmpeg-4 || warn "ffmpeg indisponible."; }
      ;;
    pacman)
      pkg_install ca-certificates curl tar gzip python python-pip libglvnd glib2 || true
      [ "$WANT_FFMPEG" = 1 ] && { pkg_install ffmpeg || warn "ffmpeg indisponible."; }
      ;;
    apk)
      pkg_install ca-certificates curl tar gzip python3 py3-pip python3-dev build-base libffi-dev openssl-dev mesa-gl glib libstdc++ || true
      [ "$WANT_FFMPEG" = 1 ] && { pkg_install ffmpeg || warn "ffmpeg indisponible."; }
      ;;
  esac
  if ! find_python; then
    if [ "$DRY_RUN" = 1 ]; then PYTHON_BIN="python3"
    elif [ -n "$WHEEL_HINT" ]; then die "Aucun Python compatible avec les roues fournies (cibles : $(wheel_tags)). Installez python3.11 ou python3.12."
    else die "Aucun interpréteur Python >= 3.10 trouvé après installation."; fi
  fi
  ok "Python : $PYTHON_BIN"
}

# ----------------------------------------------------------------------------
#  Source du paquet
# ----------------------------------------------------------------------------
# Dossier contenant le paquet : arbre source (pyproject.toml + toutpvr/) ou arbre de diffusion (wheels/*.whl).
locate_package_root() {
  local base="$1" cand
  for cand in "$base" "$base"/*/ "$base"/*/*/; do
    cand="${cand%/}"
    if [ -d "$cand/wheels" ] && compgen -G "$cand/wheels/toutpvr-*.whl" >/dev/null; then echo "$cand"; return 0; fi
    if [ -f "$cand/pyproject.toml" ] && [ -d "$cand/toutpvr" ]; then echo "$cand"; return 0; fi
  done
  return 1
}

# Télécharge la branche $CHANNEL du dépôt de diffusion : GitLab d'abord, GitHub en secours. Affiche l'URL retenue.
download_release() {
  local u
  for u in "$REPO_PRIMARY/-/archive/$CHANNEL/toutpvr-$CHANNEL.tar.gz" "$REPO_FALLBACK/archive/refs/heads/$CHANNEL.tar.gz"; do
    if [ "$DRY_RUN" = 1 ]; then echo "$u"; return 0; fi
    if curl -fsSIL --max-time 20 "$u" >/dev/null 2>&1; then echo "$u"; return 0; fi
    warn "Dépôt injoignable : $u" >&2
  done
  return 1
}

resolve_source() {
  step "Source de $PRODUCT"
  if [ -z "$SOURCE" ]; then
    if compgen -G "$SCRIPT_DIR/wheels/toutpvr-*.whl" >/dev/null || { [ -f "$SCRIPT_DIR/pyproject.toml" ] && [ -d "$SCRIPT_DIR/toutpvr" ]; }; then
      SOURCE="$SCRIPT_DIR"
    else
      case "$CHANNEL" in main|dev) ;; *) die "--channel : main ou dev" ;; esac
      SOURCE="$(download_release || true)"
      [ -n "$SOURCE" ] || die "Aucune source trouvée : ni à côté du script, ni sur $REPO_PRIMARY (GitLab) ou $REPO_FALLBACK (GitHub). Passez --source <dossier|archive.tar.gz|URL>."
    fi
  fi
  case "$SOURCE" in
    http://*|https://*)
      local tmp; tmp="$(mktemp -d)"
      log "Téléchargement de $SOURCE"
      run curl -fsSL -o "$tmp/toutpvr.tar.gz" "$SOURCE"
      run tar -xzf "$tmp/toutpvr.tar.gz" -C "$tmp"
      SOURCE="$(locate_package_root "$tmp" || true)"
      [ -n "$SOURCE" ] || [ "$DRY_RUN" = 1 ] || die "L'archive ne contient ni pyproject.toml ni dossier wheels/."
      ;;
    *.tar.gz|*.tgz)
      local tmp; tmp="$(mktemp -d)"
      run tar -xzf "$SOURCE" -C "$tmp"
      SOURCE="$(locate_package_root "$tmp" || true)"
      [ -n "$SOURCE" ] || [ "$DRY_RUN" = 1 ] || die "L'archive ne contient ni pyproject.toml ni dossier wheels/."
      ;;
  esac
  if [ -n "$SOURCE" ] && [ -d "$SOURCE/wheels" ] && compgen -G "$SOURCE/wheels/toutpvr-*.whl" >/dev/null; then
    WHEEL_DIR="$SOURCE/wheels"
    ok "Arbre de diffusion (roues compilées) : $WHEEL_DIR"
  elif [ -n "$SOURCE" ] && [ ! -f "$SOURCE/pyproject.toml" ] && [ "$DRY_RUN" = 0 ]; then
    die "Source invalide : $SOURCE (ni pyproject.toml ni wheels/toutpvr-*.whl)."
  else
    ok "Source : $SOURCE"
  fi
}

# Roue adaptée à l'interpréteur ($1) et à l'architecture : toutpvr-<ver>-cpXY-cpXY-<plateforme>.whl
select_wheel() {
  local py="$1" tag arch w found=""
  tag="$("$py" -c 'import sys; print("cp%d%d" % sys.version_info[:2])')"
  arch="$(uname -m)"
  case "$arch" in amd64) arch="x86_64" ;; arm64) arch="aarch64" ;; esac
  for w in "$WHEEL_DIR"/toutpvr-*-"$tag"-"$tag"-*.whl; do
    [ -f "$w" ] || continue
    case "$w" in *"$arch"*) found="$w"; break ;; esac
  done
  if [ -z "$found" ]; then
    {
      printf "%b✖ Aucune roue ToutPVR pour Python %s sur %s (%s).%b\n" "$C_ERR" "${tag#cp}" "$arch" "$py" "$C_RESET"
      printf "  Roues disponibles dans %s :\n" "$WHEEL_DIR"
      for w in "$WHEEL_DIR"/toutpvr-*.whl; do printf "    - %s\n" "$(basename "$w")"; done
      printf "  Installez un Python correspondant (ex. python3.11 ou python3.12) ou utilisez l'image Docker.\n"
    } >&2
    exit 1
  fi
  printf '%s\n' "$found"
}

# Installe / met à niveau le paquet dans l'environnement $PREFIX/venv (source ou roue compilée).
pip_install_package() {
  local vpy="$PREFIX/venv/bin/python"
  if [ -n "$WHEEL_DIR" ]; then
    local wheel reqs sel_py="$vpy"
    if [ "$DRY_RUN" = 1 ]; then
      log "(roue choisie selon l'interpréteur et l'architecture dans $WHEEL_DIR)"
      run "$vpy" -m pip install --quiet --upgrade --force-reinstall --no-index --no-deps "$WHEEL_DIR/toutpvr-<version>-<cpXY>-<plateforme>.whl"
      run "$vpy" -m pip install --quiet --upgrade -r "<dépendances de la roue>"
      return 0
    fi
    [ -x "$vpy" ] || sel_py="$PYTHON_BIN"
    wheel="$(select_wheel "$sel_py")"
    log "Roue : $(basename "$wheel")"
    # toutpvr lui-même vient uniquement du dossier wheels/ (jamais d'un index) ; ses dépendances viennent de PyPI.
    run "$vpy" -m pip install --quiet --upgrade --force-reinstall --no-index --no-deps "$wheel"
    reqs="$(mktemp)"
    "$vpy" - > "$reqs" <<'PY'
import importlib.metadata as m
print("\n".join(r for r in (m.requires("toutpvr") or []) if "extra ==" not in r))
PY
    "$vpy" -m pip install --quiet --upgrade -r "$reqs" || { rm -f "$reqs"; die "Installation des dépendances impossible (accès à PyPI requis)."; }
    rm -f "$reqs"
  else
    run "$vpy" -m pip install --quiet --upgrade "$SOURCE"
  fi
}

# ----------------------------------------------------------------------------
#  Environnement Python
# ----------------------------------------------------------------------------
install_package() {
  step "Installation dans $PREFIX"
  if [ "$DRY_RUN" = 0 ] && ! id "$SERVICE_USER" >/dev/null 2>&1; then
    if command -v useradd >/dev/null 2>&1; then
      useradd --system --home-dir "$DATA_DIR" --shell /usr/sbin/nologin --user-group "$SERVICE_USER" 2>/dev/null \
        || useradd --system --home-dir "$DATA_DIR" --shell /sbin/nologin "$SERVICE_USER"
    else
      adduser -S -D -H -h "$DATA_DIR" -s /sbin/nologin "$SERVICE_USER" 2>/dev/null || true
      addgroup -S "$SERVICE_USER" 2>/dev/null || true
    fi
    ok "Utilisateur système « $SERVICE_USER » créé"
  fi
  run mkdir -p "$PREFIX" "$CONFIG_DIR" "$DATA_DIR" "$(dirname "$BIN_LINK")"
  if [ ! -x "$PREFIX/venv/bin/python" ]; then
    run "$PYTHON_BIN" -m venv "$PREFIX/venv"
  fi
  run "$PREFIX/venv/bin/python" -m pip install --quiet --upgrade pip wheel
  pip_install_package
  if [ "$DB_KIND" = "mysql" ]; then run "$PREFIX/venv/bin/python" -m pip install --quiet --upgrade pymysql; fi
  # Lanceur global : fixe la configuration système quelle que soit la session.
  if [ "$DRY_RUN" = 0 ]; then
    cat > "$BIN_LINK" <<EOF
#!/usr/bin/env bash
# Lanceur $PRODUCT — généré par install.sh
export TP_CONFIG="\${TP_CONFIG:-$CONFIG_FILE}"
export TP_DATA_DIR="\${TP_DATA_DIR:-$DATA_DIR}"
exec "$PREFIX/venv/bin/toutpvr" "\$@"
EOF
    chmod 755 "$BIN_LINK"
  else
    log "(écriture du lanceur $BIN_LINK)"
  fi
  ok "Paquet installé : $("$PREFIX/venv/bin/toutpvr" --version 2>/dev/null || echo "toutpvr")"
}

# ----------------------------------------------------------------------------
#  Configuration initiale
# ----------------------------------------------------------------------------
# « tr | head » : tr reçoit SIGPIPE quand head a fini ; avec « set -o pipefail » le statut 141 interrompait l'installation.
gen_password() { local p; p="$(LC_ALL=C tr -dc 'A-Za-z0-9!%+=' </dev/urandom | head -c 16)" || true; printf '%s' "$p"; }

configure() {
  step "Configuration"
  if [ -f "$CONFIG_FILE" ] && [ "$MODE" = "install" ]; then
    warn "Configuration existante conservée : $CONFIG_FILE (utilisez --upgrade pour une mise à niveau explicite)."
    return 0
  fi
  if [ -z "$ADMIN_PASSWORD" ]; then
    if [ "$ASSUME_YES" = 0 ] && [ -t 0 ]; then
      printf "Mot de passe du compte « %s » (vide = généré) : " "$ADMIN_USER"; read -r -s ADMIN_PASSWORD; echo
    fi
    if [ -z "$ADMIN_PASSWORD" ]; then ADMIN_PASSWORD="$(gen_password)"; GENERATED_PASSWORD=1; fi
  fi
  local args=(install --yes --lang "$LANG_CODE" --port "$PORT" --host 0.0.0.0 --data-dir "$DATA_DIR"
              --admin-user "$ADMIN_USER" --admin-password "$ADMIN_PASSWORD" --portal-url "$PORTAL_URL" --db "$DB_KIND")
  [ -n "$DB_URL" ] && args+=(--db-url "$DB_URL")
  [ -n "$LICENSE_KEY" ] && args+=(--license "$LICENSE_KEY")
  run env TP_CONFIG="$CONFIG_FILE" TP_DATA_DIR="$DATA_DIR" "$PREFIX/venv/bin/toutpvr" "${args[@]}"
  run chown -R "$SERVICE_USER:$SERVICE_USER" "$CONFIG_DIR" "$DATA_DIR"
  run chmod 750 "$CONFIG_DIR" "$DATA_DIR"
  run chmod 640 "$CONFIG_FILE"
}

# ----------------------------------------------------------------------------
#  Icône et entrée de menu (postes de travail uniquement)
# ----------------------------------------------------------------------------
# Chemin d'une icône ($1 : toutpvr.ico, toutpvr.icns, toutpvr-256.png, toutpvr-512.png) : dossier icons/ de la source,
# du script (arbre de diffusion ou server/icons/), sinon kit de marque du dépôt (../brand/assets/app/).
icon_path() {
  local name="$1" d alt="$1"
  case "$name" in toutpvr-*.png) alt="app-icon-light-${name#toutpvr-}" ;; *) ;; esac
  for d in "${SOURCE:+$SOURCE/icons}" "$SCRIPT_DIR/icons"; do
    if [ -n "$d" ] && [ -f "$d/$name" ]; then printf '%s\n' "$d/$name"; return 0; fi
  done
  d="$SCRIPT_DIR/../brand/assets/app"
  if [ -f "$d/$alt" ]; then printf '%s\n' "$d/$alt"; return 0; fi
  return 1
}

# Un bureau est-il présent ? Oui si --desktop-dir est fourni, sinon si /usr/share/applications existe avec xdg-open ou une session graphique.
desktop_available() {
  [ -n "$DESKTOP_DIR" ] && return 0
  [ -d /usr/share/applications ] || return 1
  command -v xdg-open >/dev/null 2>&1 || [ -d /usr/share/xsessions ] || [ -d /usr/share/wayland-sessions ]
}

# Contenu du fichier .desktop (spécification freedesktop.org) ; $1 = port.
desktop_entry() {
  cat <<EOF
[Desktop Entry]
Type=Application
Version=1.0
Name=$PRODUCT
GenericName=Video surveillance
GenericName[fr]=Vidéosurveillance
Comment=Open the $PRODUCT web panel
Comment[fr]=Ouvrir le panneau web $PRODUCT
Exec=xdg-open http://localhost:$1
Icon=toutpvr
Terminal=false
Categories=Network;Security;
Keywords=ToutPVR;camera;cameras;surveillance;cctv;nvr;pvr;video;
StartupNotify=false
EOF
}

refresh_desktop_caches() {
  if command -v update-desktop-database >/dev/null 2>&1 && [ -d "$DESKTOP_ROOT/applications" ]; then
    run update-desktop-database "$DESKTOP_ROOT/applications" >/dev/null 2>&1 || true
  fi
  if command -v gtk-update-icon-cache >/dev/null 2>&1 && [ -f "$DESKTOP_ROOT/icons/hicolor/index.theme" ]; then
    run gtk-update-icon-cache -f -t "$DESKTOP_ROOT/icons/hicolor" >/dev/null 2>&1 || true
  fi
}

install_desktop() {
  [ "$WANT_DESKTOP" = 1 ] || { log "Entrée de menu non créée (--no-desktop)."; return 0; }
  if ! desktop_available; then log "Pas d'environnement de bureau détecté : entrée de menu ignorée."; return 0; fi
  step "Icône et entrée de menu"
  local name size src icons_ok=1 apps="$DESKTOP_ROOT/applications"
  for name in toutpvr.ico toutpvr.icns toutpvr-256.png toutpvr-512.png; do
    icon_path "$name" >/dev/null || icons_ok=0
  done
  if [ "$icons_ok" = 0 ]; then warn "Icônes introuvables (icons/ ou ../brand/assets/app/) : entrée de menu ignorée."; return 0; fi
  # Jeu d'icônes à côté de l'installation (même disposition que Windows : <préfixe>/icons).
  run mkdir -p "$PREFIX/icons"
  for name in toutpvr.ico toutpvr.icns toutpvr-256.png toutpvr-512.png; do
    src="$(icon_path "$name")"; run install -m 644 "$src" "$PREFIX/icons/$name"
  done
  for size in 256 512; do
    run mkdir -p "$DESKTOP_ROOT/icons/hicolor/${size}x${size}/apps"
    src="$(icon_path "toutpvr-$size.png")"
    run install -m 644 "$src" "$DESKTOP_ROOT/icons/hicolor/${size}x${size}/apps/toutpvr.png"
  done
  run mkdir -p "$apps"
  if [ "$DRY_RUN" = 0 ]; then
    desktop_entry "$PORT" > "$apps/toutpvr.desktop"
    chmod 644 "$apps/toutpvr.desktop"
  else
    log "(écriture de $apps/toutpvr.desktop : Exec=xdg-open http://localhost:$PORT, Icon=toutpvr)"
  fi
  refresh_desktop_caches
  ok "Entrée de menu « $PRODUCT » installée ($apps/toutpvr.desktop)"
}

remove_desktop() {
  local f
  for f in "$DESKTOP_ROOT/applications/toutpvr.desktop" "$DESKTOP_ROOT/icons/hicolor/256x256/apps/toutpvr.png" "$DESKTOP_ROOT/icons/hicolor/512x512/apps/toutpvr.png"; do
    if [ -e "$f" ]; then run rm -f "$f"; fi
  done
  refresh_desktop_caches
}

# ----------------------------------------------------------------------------
#  Service
# ----------------------------------------------------------------------------
install_service() {
  [ "$WANT_SERVICE" = 1 ] || { warn "Service non créé (--no-service). Démarrez avec : toutpvr start"; return 0; }
  step "Service système"
  if [ -d /run/systemd/system ] || [ "$DRY_RUN" = 1 ] && [ "$PKG" != "apk" ]; then
    local unit=/etc/systemd/system/toutpvr.service
    if [ "$DRY_RUN" = 0 ]; then
      cat > "$unit" <<EOF
[Unit]
Description=$PRODUCT — serveur de vidéosurveillance
After=network-online.target
Wants=network-online.target

[Service]
Type=simple
User=$SERVICE_USER
Group=$SERVICE_USER
Environment=TP_CONFIG=$CONFIG_FILE
Environment=TP_DATA_DIR=$DATA_DIR
Environment=PYTHONUNBUFFERED=1
ExecStart=$PREFIX/venv/bin/toutpvr start
Restart=always
RestartSec=3
KillSignal=SIGTERM
TimeoutStopSec=30
LimitNOFILE=65536
NoNewPrivileges=true
ProtectSystem=full
ReadWritePaths=$DATA_DIR $CONFIG_DIR

[Install]
WantedBy=multi-user.target
EOF
    else
      log "(écriture de $unit)"
    fi
    run systemctl daemon-reload
    run systemctl enable --now toutpvr.service
    ok "Service systemd « toutpvr » activé"
  elif command -v rc-update >/dev/null 2>&1; then
    local initd=/etc/init.d/toutpvr
    if [ "$DRY_RUN" = 0 ]; then
      cat > "$initd" <<EOF
#!/sbin/openrc-run
# $PRODUCT — service OpenRC (généré par install.sh)
name="toutpvr"
description="$PRODUCT — serveur de vidéosurveillance"
command="$PREFIX/venv/bin/toutpvr"
command_args="start"
command_user="$SERVICE_USER:$SERVICE_USER"
command_background=true
pidfile="/run/toutpvr.pid"
output_log="$DATA_DIR/logs/service.log"
error_log="$DATA_DIR/logs/service.log"
export TP_CONFIG="$CONFIG_FILE"
export TP_DATA_DIR="$DATA_DIR"
depend() { need net; after firewall; }
EOF
      chmod 755 "$initd"
      mkdir -p "$DATA_DIR/logs"; chown "$SERVICE_USER:$SERVICE_USER" "$DATA_DIR/logs"
    fi
    run rc-update add toutpvr default
    run rc-service toutpvr start
    ok "Service OpenRC « toutpvr » activé"
  else
    warn "Ni systemd ni OpenRC détecté : démarrez manuellement avec « toutpvr start --daemon »."
  fi
}

open_firewall() {
  [ "$WANT_FIREWALL" = 1 ] || return 0
  if command -v firewall-cmd >/dev/null 2>&1 && { [ "$DRY_RUN" = 1 ] || firewall-cmd --state >/dev/null 2>&1; }; then
    run firewall-cmd --permanent --add-port="${PORT}/tcp"
    run firewall-cmd --reload
    ok "firewalld : port ${PORT}/tcp ouvert"
  elif command -v ufw >/dev/null 2>&1 && { [ "$DRY_RUN" = 1 ] || ufw status 2>/dev/null | grep -q "Status: active"; }; then
    run ufw allow "${PORT}/tcp"
    ok "ufw : port ${PORT}/tcp ouvert"
  fi
  # SELinux (famille RHEL) : autoriser le port si ce n'est pas un port HTTP standard.
  if command -v semanage >/dev/null 2>&1 && command -v getenforce >/dev/null 2>&1 && [ "$(getenforce 2>/dev/null)" = "Enforcing" ]; then
    run semanage port -a -t http_port_t -p tcp "$PORT" 2>/dev/null || run semanage port -m -t http_port_t -p tcp "$PORT" 2>/dev/null || true
  fi
}

health_check() {
  [ "$DRY_RUN" = 0 ] || return 0
  [ "$WANT_SERVICE" = 1 ] || return 0
  for _ in $(seq 1 20); do
    if curl -fsS "http://127.0.0.1:${PORT}/health" >/dev/null 2>&1; then ok "Le serveur répond sur le port $PORT"; return 0; fi
    sleep 1
  done
  warn "Le serveur ne répond pas encore : journalctl -u toutpvr -f"
}

summary() {
  local ip; ip="$(hostname -I 2>/dev/null | awk '{print $1}')"; [ -n "$ip" ] || ip="127.0.0.1"
  printf "\n%b%s installé.%b\n" "$C_BOLD" "$PRODUCT" "$C_RESET"
  printf "  Panneau web    : http://%s:%s/\n" "$ip" "$PORT"
  printf "  Compte         : %s\n" "$ADMIN_USER"
  if [ "${GENERATED_PASSWORD:-0}" = 1 ]; then printf "  Mot de passe   : %b%s%b  (généré — notez-le maintenant)\n" "$C_BOLD" "$ADMIN_PASSWORD" "$C_RESET"; fi
  printf "  Configuration  : %s\n" "$CONFIG_FILE"
  printf "  Données        : %s\n" "$DATA_DIR"
  printf "  Commande       : toutpvr --help   (status, stop, user, license, update, backup, doctor)\n"
  if [ "$WANT_DESKTOP" = 1 ] && [ -f "$DESKTOP_ROOT/applications/toutpvr.desktop" ]; then printf "  Menu           : Applications > %s (icône du logo)\n" "$PRODUCT"; fi
  if [ "$WANT_SERVICE" = 1 ]; then printf "  Service        : systemctl status toutpvr · journalctl -u toutpvr -f\n"; fi
  if [ -z "$LICENSE_KEY" ]; then printf "  Licence        : toutpvr license activate TP1-XXXXX-XXXXX-XXXXX-XXXXX\n"; fi
  echo
}

# ----------------------------------------------------------------------------
#  Désinstallation / mise à niveau
# ----------------------------------------------------------------------------
do_uninstall() {
  step "Désinstallation de $PRODUCT"
  if [ "$ASSUME_YES" = 0 ] && [ -t 0 ]; then
    printf "Confirmer la désinstallation%s ? [o/N] " "$([ "$PURGE" = 1 ] && echo ' AVEC suppression des données')"
    read -r ans; case "$ans" in o|O|y|Y) ;; *) echo "Annulé."; exit 0 ;; esac
  fi
  if command -v systemctl >/dev/null 2>&1 && [ -f /etc/systemd/system/toutpvr.service ]; then
    run systemctl disable --now toutpvr.service || true
    run rm -f /etc/systemd/system/toutpvr.service
    run systemctl daemon-reload
  fi
  if [ -f /etc/init.d/toutpvr ]; then run rc-service toutpvr stop || true; run rc-update del toutpvr default || true; run rm -f /etc/init.d/toutpvr; fi
  run rm -f "$BIN_LINK"
  remove_desktop
  run rm -rf "$PREFIX"
  if [ "$PURGE" = 1 ]; then
    run rm -rf "$CONFIG_DIR" "$DATA_DIR"
    if [ "$DRY_RUN" = 0 ] && id "$SERVICE_USER" >/dev/null 2>&1; then userdel "$SERVICE_USER" 2>/dev/null || deluser "$SERVICE_USER" 2>/dev/null || true; fi
    ok "Programme, configuration et données supprimés."
  else
    ok "Programme supprimé. Configuration ($CONFIG_DIR) et données ($DATA_DIR) conservées (--purge pour les supprimer)."
  fi
}

do_upgrade() {
  [ -x "$PREFIX/venv/bin/toutpvr" ] || die "Aucune installation trouvée dans $PREFIX. Lancez une installation normale."
  detect_os
  resolve_source
  local had_service=0
  if command -v systemctl >/dev/null 2>&1 && systemctl is-active --quiet toutpvr 2>/dev/null; then had_service=1; run systemctl stop toutpvr; fi
  step "Sauvegarde avant mise à niveau"
  run env TP_CONFIG="$CONFIG_FILE" TP_DATA_DIR="$DATA_DIR" "$PREFIX/venv/bin/toutpvr" backup || warn "Sauvegarde impossible (poursuite)."
  pip_install_package
  run chown -R "$SERVICE_USER:$SERVICE_USER" "$DATA_DIR"
  # Rafraîchit l'entrée de menu (port lu dans la configuration) ; les anciennes installations gagnent l'icône.
  local cfg_port; cfg_port="$(sed -n 's/^port:[[:space:]]*\([0-9][0-9]*\).*/\1/p' "$CONFIG_FILE" 2>/dev/null | head -n1 || true)"
  if [ -n "$cfg_port" ]; then PORT="$cfg_port"; fi
  install_desktop
  if [ "$had_service" = 1 ]; then run systemctl start toutpvr; health_check; fi
  ok "Mise à niveau terminée : $("$PREFIX/venv/bin/toutpvr" --version 2>/dev/null || true)"
}

# ----------------------------------------------------------------------------
#  Programme principal
# ----------------------------------------------------------------------------
printf "%b%s — installeur Linux v%s%b\n" "$C_BOLD" "$PRODUCT" "$VERSION_SCRIPT" "$C_RESET"
case "$MODE" in
  uninstall) do_uninstall; exit 0 ;;
  upgrade) do_upgrade; exit 0 ;;
esac

detect_os
install_system_deps
resolve_source
install_package
configure
install_service
open_firewall
install_desktop
health_check
summary
