#!/bin/bash

# ==============================================================================
# 概要: Docker上のMySQLコンテナに対して、対話的に各種操作を行う
#
# 主な機能:
#   1. login: コンテナ内の環境変数を利用してMySQLクライアントへログイン
#   2. export: 指定のディレクトリへmysqldumpを実行（ファイル名にタイムスタンプ付与）
#   3. import: 指定ディレクトリ内のSQLファイルを一覧表示し、選択して流し込み
#   4. query:  任意のSQLクエリを実行し、結果を表示する
#   5. select: 任意のSQLクエリを実行し、結果をリストから選択して取得（他スクリプト連携用）
#
# 前提条件:
#   - MySQLコンテナ内に $MYSQL_USER, $MYSQL_PASSWORD, $MYSQL_DATABASE が設定されていること
#   - utils.sh (select_from_list関数) が存在すること
# ==============================================================================

set -euo pipefail

# --- 環境準備 ---
UTILS_SH=$(dirname $0)/utils.sh
DUMP_DIR=${DUMP_DIR:-"./dump"}

if [ -f "$UTILS_SH" ]; then
    source "$UTILS_SH"
else
    echo "❌ $UTILS_SH が見つかりません。select_from_list 関数が必要です。"
    exit 1
fi

# --- 引数処理 ---
# 第1引数: コンテナ名
CONTAINER_NAME=${1:-""}
if [ -z "$CONTAINER_NAME" ]; then
    container_list=$(docker ps --format "{{.Names}}\t{{.Status}}\t{{.Ports}}" | grep "3306")
    if [[ -z "$container_list" ]]; then
        echo "実行中のMySQLコンテナはありません。"
        exit 1
    fi
    select_container=$(select_from_list "$container_list" "操作するコンテナを選択してください")
    CONTAINER_NAME=$(echo "$select_container" | cut -f1)
fi

# 第2引数: コマンド (任意)
COMMAND=${2:-""}

# 残りの引数をシフト（selectのオプション用）
shift 2 || shift 1 || true

# --- 1. 接続チェック ---
if ! docker exec -i "$CONTAINER_NAME" bash -c 'mysql -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE" -e "SELECT 1;"' >/dev/null 2>&1; then
    echo "❌ 接続できません。コンテナが起動しているか、設定を確認してください。"
    exit 1
fi

# --- 2. 操作の選択 (第2引数が未指定の場合) ---
if [ -z "$COMMAND" ]; then
    options=(
        "ログインする"
        "インポートする"
        "エクスポートする"
        "キャンセル"
    )

    PS3="操作を選択してください (1-${#options[@]}): "

    set +e
    select opt in "${options[@]}"; do
        case "$opt" in
            "ログインする") COMMAND="login"; break ;;
            "インポートする") COMMAND="import"; break ;;
            "エクスポートする") COMMAND="export"; break ;;
            "キャンセル") echo "🚫 終了します"; exit 0 ;;
            *) echo "無効な選択です。番号で入力してください。" ;;
        esac
    done
    set -e
fi

# --- 3. 操作実行 ---
case "$COMMAND" in
    "login")
        echo "🚀 $CONTAINER_NAME にログインします..."
        docker exec -it "$CONTAINER_NAME" bash -c 'mysql -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"'
        ;;

    "export")
        mkdir -p "$DUMP_DIR"
        TS=$(date +%Y%m%d_%H%M%S)
        FILE="$DUMP_DIR/local_dump_$TS.sql"
        echo "バックアップを作成中..."
        docker exec "$CONTAINER_NAME" bash -c 'mysqldump --no-tablespaces -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"' > "$FILE"
        echo "✅ DBダンプを $FILE に出力しました"
        ;;

    "import")
        echo "インポートファイルの準備中..."
        FILES_LIST=$(ls $DUMP_DIR/*.sql 2>/dev/null || true)
        if [ -z "$FILES_LIST" ]; then
            echo "❌ $DUMP_DIR に .sql ファイルが見つかりません。"
            exit 1
        fi
        SELECTED=$(select_from_list "$FILES_LIST" "📂 インポートするファイルを選択してください")
        if [ -z "$SELECTED" ]; then
            echo "🚫 キャンセルされました。"
            exit 1
        fi
        echo "🚀 $SELECTED をインポートしています..."
        docker exec -i "$CONTAINER_NAME" bash -c 'mysql -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"' < "$SELECTED"
        echo "✅ インポートが完了しました。"
        ;;

    "query")
        QUERY="$@"
        if [ -z "$QUERY" ]; then
            echo "❌ クエリーが指定されていません。" >&2
            exit 1
        fi
        RESULT=$(docker exec -i "$CONTAINER_NAME" bash -c "echo \"$QUERY\" | mysql -u \$MYSQL_USER -p\$MYSQL_PASSWORD \$MYSQL_DATABASE" 2>/dev/null || true)
        echo "$RESULT"
        ;;

    "select")
        QUERY=""
        NAME="項目"
        for arg in "$@"; do
            case "$arg" in
                --query=*) QUERY="${arg#*=}" ;;
                --name=*)  NAME="${arg#*=}" ;;
            esac
        done
        if [ -z "$QUERY" ]; then
            echo "❌ --query が指定されていません。" >&2
            exit 1
        fi
        LIST=$(docker exec -i "$CONTAINER_NAME" bash -c "echo \"$QUERY\" | mysql -N -s -u \$MYSQL_USER -p\$MYSQL_PASSWORD \$MYSQL_DATABASE" 2>/dev/null || true)
        if [ -z "$LIST" ]; then
            echo "❌ $NAME が見つかりませんでした。" >&2
            exit 1
        fi
        SELECTED=$(select_from_list "$LIST" "👤 対象の $NAME を選択してください")
        if [ -z "$SELECTED" ]; then
            echo "🚫 キャンセルされました。" >&2
            exit 1
        fi
        echo "$SELECTED" | cut -d':' -f1
        ;;

    *)
        echo "Usage: $0 [container] {login|export|import|query|select}"
        exit 1
        ;;
esac
