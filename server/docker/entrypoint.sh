#!/usr/bin/env bash
# Point d'entrée de l'image ToutPVR :
#  - attend la base MySQL/MariaDB si TP_DB_URL pointe dessus
#  - délègue ensuite à la commande (toutpvr start par défaut)
set -euo pipefail

if [[ "${TP_DB_URL:-}" == mysql* ]]; then
  echo "› Attente de la base de données…"
  python - <<'PY'
import os, re, socket, sys, time
url = os.environ["TP_DB_URL"]
m = re.search(r"@([^/:]+)(?::(\d+))?/", url)
host, port = (m.group(1), int(m.group(2) or 3306)) if m else ("db", 3306)
for i in range(60):
    try:
        with socket.create_connection((host, port), timeout=2):
            print(f"✔ Base joignable : {host}:{port}")
            sys.exit(0)
    except OSError:
        time.sleep(2)
print(f"✖ Base injoignable après 120 s : {host}:{port}", file=sys.stderr)
sys.exit(1)
PY
fi

exec "$@"
