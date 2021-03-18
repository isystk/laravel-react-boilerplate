FROM mysql:5.7

# タイムゾーンを東京に設定
ENV TZ "Asia/Tokyo"

COPY init/* /docker-entrypoint-initdb.d/

EXPOSE 3306
