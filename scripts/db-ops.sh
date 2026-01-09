#!/bin/bash

set -euo pipefail

COMMAND=${1:-help}
shift || true

# スクリプトの場所を基準にルートディレクトリを特定
SCRIPT_DIR=$(cd $(dirname $0); pwd)
BASE_DIR=$(dirname "$SCRIPT_DIR")
UTILS_SH=~/dotfiles/scripts/utils.sh

# .env ファイルから変数をロード
if [ -f "$BASE_DIR/.env" ]; then
    export $(grep -v '^#' "$BASE_DIR/.env" | xargs)
fi

# 内部変数
DOCKER_HOME="$BASE_DIR/docker"
COMPOSE_FILE="$DOCKER_HOME/docker-compose.yml"
ENV_FILE="$BASE_DIR/.env"
DUMP_DIR="$BASE_DIR/dump"

# コマンド定義
DOCKER_CMD="docker compose -f $COMPOSE_FILE --env-file $ENV_FILE"

case "$COMMAND" in
    "export")
        mkdir -p "$DUMP_DIR"
        TS=$(date +%Y%m%d_%H%M%S)
        FILE="$DUMP_DIR/local_dump_$TS.sql"

        echo "バックアップを作成中..."
        $DOCKER_CMD exec mysql bash -c 'mysqldump --no-tablespaces -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE' > "$FILE"

        echo "✅ DBダンプを $FILE に出力しました"
        ;;

    "import")
        echo "インポートファイルの準備中..."
        FILES_LIST=$(ls $DUMP_DIR/*.sql 2>/dev/null || true)

        if [ -z "$FILES_LIST" ]; then
            echo "❌ $DUMP_DIR ディレクトリに .sql ファイルが見つかりません。"
            exit 1
        fi

        if [ -f "$UTILS_SH" ]; then
            source "$UTILS_SH"
        else
            echo "❌ $UTILS_SH が見つかりません。"
            exit 1
        fi

        SELECTED=$(select_from_list "$FILES_LIST" "📂 インポートするファイルを選択してください")

        if [ -z "$SELECTED" ]; then
            echo "🚫 キャンセルされました。"
            exit 1
        fi

        echo "🚀 $SELECTED をインポートしています..."
        $DOCKER_CMD exec -T mysql bash -c 'mysql -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE' < "$SELECTED"

        echo "✅ インポートが完了しました。"
        ;;

    *)
        echo "Usage: $0 {export|import}"
        exit 1
        ;;
esac
