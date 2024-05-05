FROM alpine:latest

# apacheのインストール
RUN \
    apk add --no-cache \
    apache2-proxy \
    apache2-ssl \
    apache2-utils \
    curl \
    git \
    logrotate \
    openssl

# タイムゾーンを東京に設定
RUN apk --update --no-cache add tzdata && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    apk del tzdata && \
    rm -rf /var/cache/apk/*

# ポート設定
EXPOSE 80 8080

RUN mkdir -p /run/apache2

# フォアグラウンドでapache実行
CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]
