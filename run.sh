#! /bin/bash

set -e

DOCKER_HOME=./docker
DOCKER_COMPOSE="docker compose -f $DOCKER_HOME/docker-compose.yml"

function usage {
    cat <<EOF
$(basename ${0}) is a tool for ...

Usage:
  $(basename ${0}) [command] [<options>]

Options:
  stats|st                 Dockerコンテナの状態を表示します。
  logs                     Dockerコンテナのログを表示します。
  init                     Dockerコンテナ・イメージ・生成ファイルの状態を初期化します。
  start                    すべてのDaemonを起動します。
  stop                     すべてのDaemonを停止します。
  mysql login              MySQLデータベースにログインします。
  mysql export <PAHT>      MySQLデータベースのdumpファイルをエクスポートします。
  mysql import <PAHT>      MySQLデータベースにdumpファイルをインポートします。
  app login                Webサーバーにログインします。
  app dev                  アプリを起動します。
  app build                アプリをビルドします。
  app test                 テストコードを実行します。
  check git-cr             Git 管理下のテキストファイルのうち、CRLF または CR 改行を含むファイルを検出
  check sh-exec            シェルスクリプトに実行権限が付与されていないファイルを検出
  --version, -v            バージョンを表示します。
  --help, -h               ヘルプを表示します。
EOF
}

function version {
    echo "$(basename ${0}) version 0.0.1 "
}

case ${1} in
    stats|st)
        $DOCKER_COMPOSE ps
    ;;

    logs)
        $DOCKER_COMPOSE logs -f
    ;;

    init)
        # 停止＆削除（コンテナ・イメージ・ボリューム）
        pushd $DOCKER_HOME
        docker compose down --rmi all --volumes
        rm -Rf ./mysql/logs && mkdir ./mysql/logs && chmod 777 ./mysql/logs
        rm -Rf ./apache/logs && mkdir ./apache/logs && chmod 777 ./apache/logs
        rm -Rf ./php/logs && mkdir ./php/logs && chmod 777 ./php/logs
        rm -Rf ./vendor
        rm -Rf ./node_modules
        popd
    ;;

    start)
        $DOCKER_COMPOSE up -d --wait
    ;;

    stop)
        pushd $DOCKER_HOME
        docker compose down
        popd
    ;;

    mysql)
      case ${2} in
          login)
              $DOCKER_COMPOSE exec mysql bash -c 'mysql -u root -p$MYSQL_ROOT_PASSWORD $MYSQL_DATABASE'
          ;;
          export)
              mysqldump --skip-column-statistics -u root -ppassword -h 127.0.0.1 laraec > ${3}
          ;;
          import)
              mysql -u root -ppassword -h 127.0.0.1 -e 'drop database if exists laraec;'
              mysql -u root -ppassword -h 127.0.0.1 -e 'create database if not exists laraec;'
              mysql -u root -ppassword -h 127.0.0.1 --default-character-set=utf8mb4 laraec < ${3}
              $DOCKER_COMPOSE restart mysql
          ;;
          *)
              usage
          ;;
      esac
    ;;

    app)
      case ${2} in
          login)
              $DOCKER_COMPOSE exec app /bin/bash
          ;;
          dev)
              $DOCKER_COMPOSE exec app npm run dev
          ;;
          build)
              $DOCKER_COMPOSE exec app npm run build
              $DOCKER_COMPOSE exec app npm run build-storybook
          ;;
          test)
              $DOCKER_COMPOSE exec app npm run prettier
              $DOCKER_COMPOSE exec app npm run ts-check
              $DOCKER_COMPOSE exec app npm run test
              $DOCKER_COMPOSE exec app ./vendor/bin/phpstan analyse --memory-limit=1G
              $DOCKER_COMPOSE exec app ./vendor/bin/phpunit tests
          ;;
          *)
              usage
          ;;
      esac
    ;;

    check)
        case ${2} in
            git-cr)
                git ls-files -z | xargs -0 file --mime-type | grep 'text/' | cut -d: -f1 | xargs -r grep -lzP '\r(\n)?'
            ;;
            sh-exec)
                find . -type f -name "*.sh" ! -perm -u=x -print
            ;;
        esac
    ;;

    help|--help|-h)
        usage
    ;;

    version|--version|-v)
        version
    ;;

    *)
        echo "[ERROR] Invalid subcommand '${1}'"
        usage
        exit 1
    ;;
esac


