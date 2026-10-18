# ToutPVR 1.0.0-dev.3 — installation

Cette distribution contient le serveur **compilé** (modules natifs, panneau minifié et obscurci) sous forme de roues Python
dans `wheels/`. Python pris en charge : 3.11, 3.12 (roues `cpXY` correspondantes). Aucune source lisible n'est fournie.

Contenu : `wheels/` · `install.sh` (Linux) · `install.ps1` / `install.bat` (Windows) · `systemd/` · `Dockerfile` +
`docker-compose.yml` · `.env.example` · `requirements.txt` · `LICENSE-COMMERCIAL.md` · `icons/` (logo : `.ico`, `.icns`, `.png`) ·
`BUILD-INFO.json` (empreintes sha256, y compris celles des icônes).

## Linux (Debian, Ubuntu, RHEL/Alma/Rocky, Fedora, openSUSE, Arch, Alpine…)

Sans rien télécharger avant (GitLab `repo.qu3ntin.com`, GitHub en secours ; `--channel dev` pour la version de développement) :

```bash
curl -fsSL https://repo.qu3ntin.com/toutpvr/toutpvr/-/raw/main/server/install.sh | sudo bash -s -- --yes --port 8080
```

Depuis cette arborescence :

```bash
sudo bash install.sh --yes --port 8080 --admin-password 'MotDePasseSolide!'
# options : --license TP1-XXXXX-XXXXX-XXXXX-XXXXX · --lang fr · --db mysql --db-url … · --no-service · --no-firewall · --no-desktop
sudo bash install.sh --upgrade        # mise à niveau en place (sauvegarde puis nouvelle roue)
sudo bash install.sh --uninstall --purge
```

L'installeur choisit la roue qui correspond à l'interpréteur Python et à l'architecture ; `toutpvr` est installé
uniquement depuis `wheels/` (`--no-index`), ses dépendances depuis PyPI (accès réseau requis).
Sans roue correspondante (autre version de Python ou autre architecture), l'installation s'arrête avec un message explicite.

**Icône et menu** : sur un poste avec environnement graphique (`/usr/share/applications` et `xdg-open` ou une session X11/Wayland),
l'installeur ajoute l'entrée « ToutPVR » (logo) : `/usr/share/applications/toutpvr.desktop` (ouvre `http://localhost:PORT` dans le
navigateur) et les icônes `hicolor` 256 et 512 px. Rien n'est ajouté sur un serveur sans interface ; `--no-desktop` l'interdit,
`--desktop-dir DIR` (ou `TP_DESKTOP_DIR`) change la racine « share » ; `--uninstall` retire entrée et icônes.

## Windows

```powershell
powershell -ExecutionPolicy Bypass -File install.ps1 -Yes -Port 8080 -AdminPassword 'MotDePasseSolide!'
```
(ou double-clic sur `install.bat`). Les roues Windows (amd64) sont publiées avec la version si elles ont été construites.

**Icône et raccourcis** : l'installeur crée le dossier « ToutPVR » du menu Démarrer avec l'icône du logo (`icons\toutpvr.ico`, copiée
dans `<Prefix>\icons\`) : « ToutPVR — Panneau » (raccourci Internet vers `http://localhost:PORT/`, navigateur par défaut),
démarrer / arrêter / redémarrer le service (administrateur), dossier des données et journaux, Désinstaller (administrateur).
`-DesktopShortcut` ajoute un raccourci sur le bureau ; `-NoShortcut` n'en crée aucun ; `-Uninstall` les supprime.

## Docker

```bash
cp .env.example .env     # ajuster TP_ADMIN_PASSWORD, TP_PORT…
docker compose up -d     # construit l'image depuis wheels/ puis démarre sur http://localhost:8080
```

## Vérification

`toutpvr --version` doit afficher `1.0.0-dev.3`, `GET /health` renvoie la même version. Les empreintes de chaque fichier
sont dans `BUILD-INFO.json`.
