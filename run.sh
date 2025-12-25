#! /bin/bash

set -e

BASE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
DOCKER_HOME="$BASE_DIR/docker"
COMPOSE_FILE="$DOCKER_HOME/docker-compose.yml"
DOCKER_COMPOSE="docker compose -f $COMPOSE_FILE"

confirm() {
    if [ "$FORCE" = true ]; then return 0; fi
    read -r -p "${1:-Are you sure?} [y/N]: " ans
    [[ $ans =~ ^[Yy] ]] && return 0 || return 1
}

usage() {
    awk '
        # 親階層(mysql|app)の開始
        /^[[:space:]]*(mysql|app)\)/ { parent = $1; sub(/\).*/, "", parent); next }
        # 親階層の終了
        /^[[:space:]]*esac/ { parent = "" }
        # 説明文の保持
        /^[[:space:]]*## / { desc = $0; sub(/.*## /, "", desc); next }
        # コマンドの出力
        /^[[:space:]]*[a-zA-Z0-9_|-]+\)/ && desc {
            cmd = $1; sub(/\).*/, "", cmd)
            full = (parent && cmd != parent && cmd !~ /help/) ? parent " " cmd : cmd
            printf "  %-25s %s\n", full, desc
            desc = ""
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
        if confirm "イメージ、ボリューム、vendor、node_modules、および storage が削除されます。続行しますか？"; then
            $DOCKER_COMPOSE down --rmi all --volumes
            pushd "$DOCKER_HOME" >/dev/null
            rm -rf ./mysql/logs && mkdir -p ./mysql/logs && chmod 755 ./mysql/logs
            rm -rf ./app/logs && mkdir -p ./app/logs && chmod 755 ./app/logs
            popd >/dev/null
            rm -rf "$BASE_DIR/vendor" "$BASE_DIR/node_modules"
            echo "Initialized."
        else
            echo "Aborted."
        fi
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

    ## tinkerを実行します。
    tinker)
        $DOCKER_COMPOSE exec app php artisan tinker
        ;;

    mysql)
        case "${2}" in
            ## mysqlにログインします。
            login)
                $DOCKER_COMPOSE exec mysql bash -c \
                    'mysql -u $MYSQL_USER  -p$MYSQL_PASSWORD $MYSQL_DATABASE'
                ;;

            ## マイグレーションを実行します。
            migrate)
                $DOCKER_COMPOSE exec app php artisan migrate
                ;;

            ## mysqlのdumpファイルをエクスポートします。
            export)
                TS=$(date +%Y%m%d_%H%M%S)
                DEFAULT_FILE="local_dump_${TS}.sql"
                outfile="${3:-$DEFAULT_FILE}"
                $DOCKER_COMPOSE exec mysql bash -c 'mysqldump --no-tablespaces -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE' > "$outfile"
                echo "DBダンプを $outfile に出力しました"
                ;;

            ## mysqlにdumpファイルをインポートします。
            import)
                infile="${3:-}"
                if [ -z "$infile" ]; then
                    echo "使い方: $0 mysql import <ファイル名.sql>" >&2
                    exit 1
                fi
                if [ ! -f "$infile" ]; then
                    echo "エラー: ファイル '$infile' が見つかりません。" >&2
                    exit 1
                fi
                $DOCKER_COMPOSE exec -T mysql bash -c 'mysql -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE' < "$infile"
                echo "Imported $infile"
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

    ## Gitの差分をGeminiで30文字程度に要約します。
    summarize)
        # 未コミットの差分を取得
        DIFF=$(git diff HEAD)
        if [ -z "$DIFF" ]; then
            echo "要約する差分がありません。"
            exit 0
        fi
        echo "Geminiで変更点を要約中..."
        # gemini cli を呼び出し
        echo "$DIFF" | gemini -p "以下のgit diffを、日本語で30文字程度で簡潔に要約してください。1行で、文末の句読点は不要です。"
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
