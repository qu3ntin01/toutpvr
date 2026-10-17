# ============================================================================
#  ToutPVR — image du site / portail de licences (PHP 8.3 + Apache)
#  Construction : docker build -t toutpvr/website:1.0.0-dev.1 .
# ============================================================================
FROM php:8.3-apache

# Extensions requises : PDO MySQL/SQLite, mbstring, sodium (Ed25519), opcache, gd (vignettes médias), zip
RUN sed -i 's|http://deb.debian.org|https://deb.debian.org|g' /etc/apt/sources.list.d/debian.sources \
    && apt-get update \
    && apt-get install -y --no-install-recommends libsodium-dev libsqlite3-dev libzip-dev libpng-dev libjpeg-dev libwebp-dev libonig-dev unzip \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql pdo_sqlite mbstring sodium opcache gd zip \
    && a2enmod rewrite headers expires \
    && rm -rf /var/lib/apt/lists/*

# Apache : racine web = public/, AllowOverride pour .htaccess
RUN sed -ri 's!/var/www/html!/var/www/toutpvr/public!g' /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf \
    && printf '<Directory /var/www/toutpvr/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' > /etc/apache2/conf-available/toutpvr.conf \
    && a2enconf toutpvr \
    && { echo 'opcache.enable=1'; echo 'opcache.memory_consumption=128'; echo 'opcache.validate_timestamps=0'; echo 'upload_max_filesize=512M'; echo 'post_max_size=512M'; echo 'memory_limit=256M'; echo 'expose_php=Off'; } > /usr/local/etc/php/conf.d/toutpvr.ini

WORKDIR /var/www/toutpvr
COPY . /var/www/toutpvr
# Le .htaccess racine ne sert qu'aux vhosts mal configurés : inutile ici (DocumentRoot = public/) et il
# bloquerait les routes PHP se terminant en .json. Les dossiers non publics sont interdits explicitement.
RUN rm -f /var/www/toutpvr/.htaccess /var/www/toutpvr/config/install.lock /var/www/toutpvr/config/config.php \
    && rm -rf /var/www/toutpvr/docs/screenshots \
    && mkdir -p storage/releases storage/logs storage/cache storage/invoices storage/media storage/sessions config \
    && printf '<DirectoryMatch "^/var/www/toutpvr/(app|config|storage|cli|migrations|templates|lang|install/.*\\.php$)">\n    Require all denied\n</DirectoryMatch>\n' > /etc/apache2/conf-available/toutpvr-deny.conf \
    && a2enconf toutpvr-deny \
    && chown -R www-data:www-data /var/www/toutpvr

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

VOLUME ["/var/www/toutpvr/storage", "/var/www/toutpvr/config"]
EXPOSE 80
HEALTHCHECK --interval=30s --timeout=5s --start-period=30s CMD curl -fsS http://127.0.0.1/status.json >/dev/null || exit 1
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
