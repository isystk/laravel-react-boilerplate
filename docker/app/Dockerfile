FROM php:8.3-apache

# 必要なパッケージをインストール
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zlib1g-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    mariadb-client \
    tzdata \
    curl \
    openssl \
    nano \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip pdo_mysql gd \
    && pecl install xdebug-3.3.2 \
    && docker-php-ext-enable xdebug

# タイムゾーン設定
ENV TZ=Asia/Tokyo
RUN ln -sf /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

# Apache モジュール有効化
RUN a2enmod rewrite ssl proxy proxy_fcgi proxy_http

# 自己署名証明書を生成
RUN mkdir -p /etc/apache2/ssl && \
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/apache2/ssl/server.key \
    -out /etc/apache2/ssl/server.crt \
    -subj "/C=JP/ST=Tokyo/L=Chiyoda/O=Example/CN=localhost"

# ServerName を明示（SSL 警告対策）
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Composer のインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/composer
ENV PATH=$PATH:/composer/vendor/bin

# 作業ディレクトリ
WORKDIR /var/www/html

# エントリポイント
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]

# ポート
EXPOSE 80 443 5173
