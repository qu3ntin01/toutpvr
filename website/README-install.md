# ToutPVR — site / portail de licences (version 1.0.0-dev.1)

Cette arborescence est la version **diffusée** du site : le code PHP est obscurci (sans commentaires, identifiants
internes renommés) et les ressources JS/CSS sont minifiées. Elle se déploie telle quelle, sans étape de compilation.

## Docker (recommandé)

```bash
cp .env.example .env        # puis adaptez les mots de passe, TP_SITE_URL, TP_SITE_DB_DRIVER (mysql | sqlite)
docker compose up -d
```

Le site écoute sur le port `TP_SITE_PORT` (8088 par défaut). Avec `TP_SITE_AUTO_INSTALL=1`, l'installation est faite au
premier démarrage ; sinon ouvrez `http://hôte:8088/install/`. Placez un reverse proxy TLS devant le conteneur en production.

## Sans Docker

Prérequis : PHP 8.2 ou plus avec `pdo_mysql` ou `pdo_sqlite`, `mbstring`, `sodium`, `gd`, `zip`, `opcache` (recommandé).

1. Servez `public/` comme racine web (voir `nginx.conf.example` ; Apache : `.htaccess` fourni).
2. `storage/` et `config/` doivent être inscriptibles par l'utilisateur du serveur web.
3. Installation : ouvrez `/install/` ou, en ligne de commande :
   `php cli/console.php install --driver=sqlite --admin-email=vous@exemple.fr --admin-password='…' --url=https://portail.exemple.fr`
4. Tâches planifiées (expirations, relances, files d'e-mails), toutes les heures :
   `0 * * * * php /chemin/vers/website/cli/console.php cron`

`php cli/console.php help` liste les commandes (migrations, licences, clés, catalogue IA, vérification i18n…).
Les clés de licence Ed25519 sont générées à l'installation (ou importées avec `--import-key`) et stockées dans `config/` :
sauvegardez `config/` et `storage/`.

## Intégrité

`BUILD-INFO.json` contient la version, la date de construction, les versions des outils et l'empreinte SHA-256 de chaque fichier.
