FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zlib1g-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    mariadb-client tzdata curl nano nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip pdo_mysql gd \
    && pecl install xdebug-3.3.2 \
    && docker-php-ext-enable xdebug

ENV TZ=Asia/Tokyo
RUN ln -sf /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

RUN a2enmod rewrite proxy proxy_http proxy_fcgi
RUN rm -f /etc/apache2/sites-enabled/*
COPY ./sites-enabled/local-default.conf /etc/apache2/sites-enabled/local-default.conf
COPY ./php.ini /usr/local/etc/php/php.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/composer
ENV PATH=$PATH:/composer/vendor/bin

RUN curl -O https://dl.min.io/client/mc/release/linux-amd64/mc \
    && chmod +x mc \
    && mv mc /usr/local/bin/

WORKDIR /var/www/html

# ブラウザの依存ライブラリだけを先にインストールしておく
RUN npx playwright install-deps chromium

ENV PLAYWRIGHT_BROWSERS_PATH=/ms-playwright

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]

EXPOSE 80 5173
