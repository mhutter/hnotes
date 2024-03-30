FROM docker.io/library/php:5-apache

# Install required extensions
RUN set -e -u -x; \
    docker-php-ext-install mysql

COPY . /var/www/html/
RUN set -e -u -x; \
    sed -r -i 's/^\$server.+$/\$server = '"'db'"';/' /var/www/html/src/db.inc.php; \
    a2enmod rewrite
