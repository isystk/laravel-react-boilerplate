FROM php:7.4.1-fpm
COPY php.ini /usr/local/etc/php/

COPY install-composer.sh /
RUN apt-get update \
  && apt-get install -y wget git unzip libpq-dev libzip-dev zlib1g-dev mariadb-client libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
  && : 'Install Node.js' \
  && curl -sL https://deb.nodesource.com/setup_12.x | bash - \
  && apt-get install -y nodejs \
  && : 'Install PHP Extensions' \
  && docker-php-ext-install -j$(nproc) pdo_pgsql \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd \
  && docker-php-ext-install zip pdo_mysql
  
#Composer install
COPY --from=composer /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1

ENV COMPOSER_HOME /composer

ENV PATH $PATH:/composer/vendor/bin


WORKDIR /var/www/html

RUN composer global require "laravel/installer"
