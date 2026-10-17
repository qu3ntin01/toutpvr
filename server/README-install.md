# ToutPVR 1.0.0-dev.1 — installation

Cette distribution contient le serveur **compilé** (modules natifs, panneau minifié et obscurci) sous forme de roues Python
dans `wheels/`. Python pris en charge : 3.11, 3.12 (roues `cpXY` correspondantes). Aucune source lisible n'est fournie.

Contenu : `wheels/` · `install.sh` (Linux) · `install.ps1` / `install.bat` (Windows) · `systemd/` · `Dockerfile` +
`docker-compose.yml` · `.env.example` · `requirements.txt` · `LICENSE-COMMERCIAL.md` · `BUILD-INFO.json` (empreintes sha256).

## Linux (Debian, Ubuntu, RHEL/Alma/Rocky, Fedora, openSUSE, Arch, Alpine…)

```bash
sudo bash install.sh --yes --port 8080 --admin-password 'MotDePasseSolide!'
# options : --license TP1-XXXXX-XXXXX-XXXXX-XXXXX · --lang fr · --db mysql --db-url … · --no-service · --no-firewall
sudo bash install.sh --upgrade        # mise à niveau en place (sauvegarde puis nouvelle roue)
sudo bash install.sh --uninstall --purge
```

L'installeur choisit la roue qui correspond à l'interpréteur Python et à l'architecture ; `toutpvr` est installé
uniquement depuis `wheels/` (`--no-index`), ses dépendances depuis PyPI (accès réseau requis).
Sans roue correspondante (autre version de Python ou autre architecture), l'installation s'arrête avec un message explicite.

## Windows

```powershell
powershell -ExecutionPolicy Bypass -File install.ps1 -Yes -Port 8080 -AdminPassword 'MotDePasseSolide!'
```
(ou double-clic sur `install.bat`). Les roues Windows (amd64) sont publiées avec la version si elles ont été construites.

## Docker

```bash
cp .env.example .env     # ajuster TP_ADMIN_PASSWORD, TP_PORT…
docker compose up -d     # construit l'image depuis wheels/ puis démarre sur http://localhost:8080
```

## Vérification

`toutpvr --version` doit afficher `1.0.0-dev.1`, `GET /health` renvoie la même version. Les empreintes de chaque fichier
sont dans `BUILD-INFO.json`.
