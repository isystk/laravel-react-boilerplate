#!/bin/bash
# ==============================================================================
# 概要: DBと画像(MinIO)のバックアップをZIPにまとめ、5世代管理する
#
# 機能:
#   - 最新3世代の日次バックアップを保持
#   - 毎週月曜日のバックアップを1世代保持 (-weekly)
#   - 毎月1日のバックアップを1世代保持 (-monthly)
#   - 実行ログを storage/logs/backup-daily.log に追記
#
# 使い方:
#   bash scripts/backup-daily.sh
#
# crontab 設定例 (毎日 05:00 実行):
#   0 5 * * * cd /path/to/laravel-react-boilerplate && bash scripts/backup-daily.sh >> storage/logs/backup-cron.log 2>&1
# ==============================================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
BACKUP_DIR="$PROJECT_DIR/storage/backup"
LOG_FILE="$PROJECT_DIR/storage/logs/backup-daily.log"

# 設定
MAX_DAILY_GENERATIONS=3
DAY_OF_WEEK=$(date +%u)
DAY_OF_MONTH=$(date +%d)

log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

mkdir -p "$BACKUP_DIR"
mkdir -p "$(dirname "$LOG_FILE")"

TS=$(date +%Y%m%d_%H%M%S)
WORK_DIR=$(mktemp -d "/tmp/laraec-backup-${TS}-XXXXXX")

cleanup() {
    rm -rf "$WORK_DIR"
}
trap cleanup EXIT

log "バックアッププロセス開始"

log "DBバックアップ実行中..."
DUMP_DIR="$WORK_DIR" bash "$SCRIPT_DIR/db-backup.sh"

log "S3(MinIO)バックアップ実行中..."
DUMP_DIR="$WORK_DIR" bash "$SCRIPT_DIR/s3-backup.sh"

SUFFIX=""
if [ "$DAY_OF_MONTH" = "01" ]; then
    SUFFIX="-monthly"
    log "毎月1日のため月次ラベルを付与"
elif [ "$DAY_OF_WEEK" = "1" ]; then
    SUFFIX="-weekly"
    log "月曜日のため週次ラベルを付与"
fi

ZIP_FILE="$BACKUP_DIR/backup_${TS}${SUFFIX}.zip"
log "ZIPファイル作成中: $(basename "$ZIP_FILE")"
(cd "$WORK_DIR" && zip -r "$ZIP_FILE" .)

# 世代管理: まず全バックアップファイルを取得（新しい順）
ALL_BACKUPS=($(ls -t "$BACKUP_DIR"/backup_*.zip 2>/dev/null || true))

DAILY_LIST=()
for f in "${ALL_BACKUPS[@]}"; do
    # ファイル名に -weekly または -monthly を含まないものを日次リストに追加
    if [[ ! "$f" =~ -(weekly|monthly)\.zip$ ]]; then
        DAILY_LIST+=("$f")
    fi
done

# 日次バックアップの削除実行
COUNT=${#DAILY_LIST[@]}
if [ "$COUNT" -gt "$MAX_DAILY_GENERATIONS" ]; then
    for OLD in "${DAILY_LIST[@]:$MAX_DAILY_GENERATIONS}"; do
        rm -f "$OLD"
        log "古い日次バックアップを削除: $(basename "$OLD")"
    done
fi

# 週次・月次の削除 (最新1件のみ残す)
{ ls -t "$BACKUP_DIR"/*-weekly.zip 2>/dev/null || true; } | tail -n +2 | xargs -r rm -f
{ ls -t "$BACKUP_DIR"/*-monthly.zip 2>/dev/null || true; } | tail -n +2 | xargs -r rm -f

log "バックアップ完了 (日次: ${#DAILY_LIST[@]}/$MAX_DAILY_GENERATIONS, 週次・月次各1件保持)"
