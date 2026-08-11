#!/bin/bash
# ==============================================================================
# 概要: バックアップZIPからDBとMinIO画像データを復元する
#
# 機能:
#   - storage/backup/*.zip から復元対象をインタラクティブに選択
#   - SQLファイルをMySQLへインポート
#   - tar.gzをMinIOコンテナの /data へ展開
#
# 使い方:
#   bash scripts/backup-restore.sh
#
# 環境変数 (任意):
#   DB_CONTAINER  MySQLコンテナ名 (デフォルト: laraec-mysql)
#   S3_CONTAINER  MinIOコンテナ名 (デフォルト: laraec-s3)
# ==============================================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
BACKUP_DIR="$PROJECT_DIR/storage/backup"
DB_CONTAINER="${DB_CONTAINER:-laraec-mysql}"
S3_CONTAINER="${S3_CONTAINER:-laraec-s3}"

source "$SCRIPT_DIR/utils.sh"

ZIP_LIST=$(ls -t "$BACKUP_DIR"/backup_*.zip 2>/dev/null || true)
if [ -z "$ZIP_LIST" ]; then
    echo "❌ $BACKUP_DIR にバックアップファイルが見つかりません。"
    exit 1
fi

SELECTED_ZIP=$(select_from_list "$ZIP_LIST" "復元するバックアップを選択してください")
if [ -z "$SELECTED_ZIP" ]; then
    echo "🚫 キャンセルされました。"
    exit 0
fi

echo ""
echo "選択されたバックアップ: $(basename "$SELECTED_ZIP")"
echo "⚠️  現在のDB・S3データが上書きされます。続行しますか？ [y/N]"
read -r CONFIRM
if [[ ! "$CONFIRM" =~ ^[Yy]$ ]]; then
    echo "🚫 キャンセルされました。"
    exit 0
fi

WORK_DIR=$(mktemp -d "/tmp/laraec-restore-XXXXXX")
cleanup() {
    rm -rf "$WORK_DIR"
}
trap cleanup EXIT

echo "バックアップを展開中..."
unzip -q "$SELECTED_ZIP" -d "$WORK_DIR"

SQL_FILE=$(ls "$WORK_DIR"/*.sql 2>/dev/null | head -1 || true)
if [ -n "$SQL_FILE" ]; then
    echo "DBをリストア中 (file=$(basename "$SQL_FILE"), container=$DB_CONTAINER)..."
    echo "DBを初期化中 (drop & create)..."
    docker exec -i "$DB_CONTAINER" bash -c 'mysql -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "DROP DATABASE IF EXISTS \`$MYSQL_DATABASE\`; CREATE DATABASE \`$MYSQL_DATABASE\`;"'
    docker exec -i "$DB_CONTAINER" bash -c 'mysql -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"' < "$SQL_FILE"
    echo "✅ DBリストア完了"
else
    echo "⚠️  SQLファイルが見つかりません。DBリストアをスキップします。"
fi

S3_TAR=$(ls "$WORK_DIR"/s3_backup_*.tar.gz 2>/dev/null | head -1 || true)
if [ -n "$S3_TAR" ]; then
    echo "MinIOデータをリストア中 (file=$(basename "$S3_TAR"), container=$S3_CONTAINER)..."
    docker run --rm -i --volumes-from "$S3_CONTAINER" alpine tar xzf - -C /  < "$S3_TAR"
    echo "✅ MinIOリストア完了"
else
    echo "⚠️  S3バックアップファイルが見つかりません。MinIOリストアをスキップします。"
fi

echo "✅ リストア完了"
