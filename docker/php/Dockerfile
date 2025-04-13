FROM php:8.3-fpm

RUN apt-get update \
  && apt-get install -y wget git unzip libzip-dev zlib1g-dev mariadb-client libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
  && : 'Install Node.js' \
  && curl -sL https://deb.nodesource.com/setup_20.x | bash - \
  && apt-get install -y nodejs \
  && : 'Install PHP Extensions' \
  && docker-php-ext-install zip pdo_mysql \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd

# Xdebug のインストール
RUN pecl install xdebug-3.3.2 \
  && docker-php-ext-enable xdebug

# Composer のコピー
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Composer の環境設定
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/composer
ENV PATH=$PATH:/composer/vendor/bin

# 作業ディレクトリ
WORKDIR /var/www/html

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]
