FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        locales \
        unzip \
    && docker-php-ext-install pdo_mysql \
    && sed -i '/pt_BR.UTF-8/s/^# //g' /etc/locale.gen \
    && locale-gen pt_BR.UTF-8 \
    && a2enmod rewrite \
    && echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername \
    && rm -rf /var/lib/apt/lists/*

ENV LANG=pt_BR.UTF-8 \
    LANGUAGE=pt_BR:pt \
    LC_ALL=pt_BR.UTF-8

WORKDIR /var/www/html

COPY . .
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php-dev.ini /usr/local/etc/php/conf.d/99-dev.ini

RUN mkdir -p imgs/profile imgs/generic storage/cache sys/logs \
    && chown -R www-data:www-data imgs storage sys/logs

EXPOSE 80
