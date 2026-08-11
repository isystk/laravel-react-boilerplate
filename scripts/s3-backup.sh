#!/bin/bash
# ==============================================================================
# 概要: MinIO（laraec-s3）の画像データを tar.gz でバックアップする
#
# 機能:
#   - MinIOコンテナ内の /data ディレクトリをアーカイブ
#
# 使い方:
#   bash scripts/s3-backup.sh
#
# 環境変数 (任意):
#   CONTAINER  MinIOコンテナ名 (デフォルト: laraec-s3)
#   DUMP_DIR   バックアップ出力先ディレクトリ (デフォルト: {PROJECT_DIR}/dump)
# ==============================================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
DUMP_DIR="${DUMP_DIR:-$PROJECT_DIR/dump}"
CONTAINER="${CONTAINER:-laraec-s3}"

mkdir -p "$DUMP_DIR"

if ! docker ps --format '{{.Names}}' | grep -q "^${CONTAINER}$"; then
    echo "❌ コンテナ '$CONTAINER' が起動していません。" >&2
    exit 1
fi

TS=$(date +%Y%m%d_%H%M%S)
FILE="$DUMP_DIR/s3_backup_$TS.tar.gz"

echo "MinIOデータをバックアップ中 (container=$CONTAINER)..."
docker run --rm --volumes-from "$CONTAINER" alpine tar czf - /data > "$FILE"
echo "✅ S3バックアップを $FILE に出力しました"
