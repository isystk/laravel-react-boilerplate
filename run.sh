#! /bin/bash

set -e

DOCKER_HOME=./docker
DOCKER_COMPOSE="docker compose -f $DOCKER_HOME/docker-compose.yml"

function usage {
    echo "Usage:"
    awk '
        # 親コマンドをリセット
        /^[[:space:]]*case[[:space:]]+"\$\{1\}"[[:space:]]+in/ {
            parent = ""
        }

        # 親コマンドを取得
        /^[[:space:]]*(mysql|app)\)/ {
            parent = $1
            sub(/\)$/, "", parent)
        }

        # usage コメント行を保存
        /^[[:space:]]*##[[:space:]]+/ {
            desc = $0
            sub(/^[[:space:]]*##[[:space:]]+/, "", desc)
            next
        }

        # case ラベル行
        /^[[:space:]]*[a-zA-Z0-9_|-]+\)/ {
            if (desc != "") {
                cmd = $0
                sub(/^[[:space:]]*/, "", cmd)
                sub(/\).*/, "", cmd)

                if (parent != "" && cmd != parent) {
                    printf "  %-25s %s\n", parent " " cmd, desc
                } else {
                    printf "  %-25s %s\n", cmd, desc
                }

                desc = ""
            }
        }
    ' "$0"
}

case "${1}" in
    ## Dockerコンテナの状態を表示します。
    stats|st)
        $DOCKER_COMPOSE ps
        ;;

    ## Dockerコンテナのログを表示します。
    logs)
        $DOCKER_COMPOSE logs -f
        ;;

    ## 初期化します。
    init)
        $DOCKER_COMPOSE down --rmi all --volumes
        pushd "$DOCKER_HOME"
        rm -Rf ./mysql/logs && mkdir ./mysql/logs && chmod 777 ./mysql/logs
        rm -Rf ./app/logs && mkdir ./app/logs && chmod 777 ./app/logs
        rm -Rf ../vendor
        rm -Rf ../node_modules
        rm -Rf ../storage/app/*
        popd
        ;;

    ## 起動します。
    start)
        $DOCKER_COMPOSE up -d --wait
        ;;

    ## 停止します。
    stop)
        pushd "$DOCKER_HOME"
        docker compose down
        popd
        ;;

    ## 再起動します。
    restart)
        "${0}" stop
        "${0}" start
        ;;

    mysql)
        case "${2}" in
            ## mysqlにログインします。
            login)
                $DOCKER_COMPOSE exec mysql bash -c \
                    'mysql -u root -p$MYSQL_ROOT_PASSWORD $MYSQL_DATABASE'
                ;;

            ## マイグレーションを実行します。
            migrate)
                $DOCKER_COMPOSE exec app php artisan migrate
                ;;

            ## mysqlのdumpファイルをエクスポートします。
            export)
                mysqldump --skip-column-statistics \
                    -u root -ppassword -h 127.0.0.1 laraec > "${3}"
                ;;

            ## mysqlにdumpファイルをインポートします。
            import)
                mysql -u root -ppassword -h 127.0.0.1 \
                    -e 'drop database if exists laraec;'
                mysql -u root -ppassword -h 127.0.0.1 \
                    -e 'create database if not exists laraec;'
                mysql -u root -ppassword -h 127.0.0.1 \
                    --default-character-set=utf8mb4 laraec < "${3}"
                $DOCKER_COMPOSE restart mysql
                ;;

            *)
                usage
                ;;
        esac
        ;;

    app)
        case "${2}" in
            ## appコンテナに入ります。
            login)
                $DOCKER_COMPOSE exec app /bin/bash
                ;;

            ## appコンテナで開発用ビルドを実行します。
            npm-run-dev)
                $DOCKER_COMPOSE exec app npm run dev
                ;;

            ## appコンテナでビルドを実行します。
            npm-run-build)
                $DOCKER_COMPOSE exec app npm run build
                $DOCKER_COMPOSE exec app npm run build-storybook
                ;;

            ## コード成型を実行します。
            prettier)
                $DOCKER_COMPOSE exec app npm run prettier
                $DOCKER_COMPOSE exec app ./vendor/bin/pint
                ;;

            ## 自動テストを実行します。
            test)
                $DOCKER_COMPOSE exec app npm run lint
                $DOCKER_COMPOSE exec app npm run ts-check
                $DOCKER_COMPOSE exec app npm run test
                $DOCKER_COMPOSE exec app \
                    ./vendor/bin/phpstan analyse --memory-limit=1G
                $DOCKER_COMPOSE exec -e XDEBUG_MODE=off app \
                    ./vendor/bin/phpunit --display-phpunit-deprecations
                ;;

            *)
                usage
                ;;
        esac
        ;;

    ## ヘルプを表示します。
    help|--help|-h)
        usage
        ;;

    *)
        usage
        exit 1
        ;;
esac
