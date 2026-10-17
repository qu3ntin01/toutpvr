#!/usr/bin/env bash
# Point d'entrée du site ToutPVR :
#  - attend MariaDB si TP_SITE_DB_DRIVER=mysql
#  - installe automatiquement le site au premier démarrage si TP_SITE_AUTO_INSTALL=1
#    (sinon, ouvrez http://hote/install/ dans le navigateur)
#  - lance ensuite Apache
set -euo pipefail
cd /var/www/toutpvr

DRIVER="${TP_SITE_DB_DRIVER:-sqlite}"
if [ "$DRIVER" = "mysql" ]; then
  host="${TP_SITE_DB_HOST:-db}"; port="${TP_SITE_DB_PORT:-3306}"
  echo "› Attente de MariaDB ($host:$port)…"
  for _ in $(seq 1 60); do
    if (exec 3<>"/dev/tcp/$host/$port") 2>/dev/null; then exec 3>&-; echo "✔ Base joignable"; break; fi
    sleep 2
  done
fi

if [ ! -f config/install.lock ] && [ "${TP_SITE_AUTO_INSTALL:-0}" = "1" ]; then
  echo "› Installation automatique du site…"
  args=(install --driver="$DRIVER" --admin-email="${TP_SITE_ADMIN_EMAIL:-admin@example.com}"
        --admin-password="${TP_SITE_ADMIN_PASSWORD:-ChangeMe12345!}" --url="${TP_SITE_URL:-http://localhost:8088}")
  if [ "$DRIVER" = "mysql" ]; then
    args+=(--db-host="${TP_SITE_DB_HOST:-db}" --db-port="${TP_SITE_DB_PORT:-3306}" --db-name="${TP_SITE_DB_NAME:-toutpvr}"
           --db-user="${TP_SITE_DB_USER:-toutpvr}" --db-pass="${TP_SITE_DB_PASSWORD:-toutpvr}")
  fi
  if [ -n "${TP_SITE_IMPORT_KEY:-}" ] && [ -f "${TP_SITE_IMPORT_KEY}" ]; then args+=(--import-key="${TP_SITE_IMPORT_KEY}"); fi
  if ! su -s /bin/bash www-data -c "php cli/console.php $(printf '%q ' "${args[@]}")"; then
    echo "✖ Installation automatique échouée : corrigez les variables TP_SITE_* ou utilisez l'installeur web sur /install/" >&2
  fi
fi

# Cron interne (expirations, relances, files) toutes les heures, sans dépendance externe
if [ "${TP_SITE_CRON:-1}" = "1" ]; then
  ( while true; do sleep 3600; su -s /bin/bash www-data -c "php cli/console.php cron" >> storage/logs/cron.log 2>&1 || true; done ) &
fi

chown -R www-data:www-data storage config 2>/dev/null || true
exec "$@"
