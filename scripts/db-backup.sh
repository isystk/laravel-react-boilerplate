#!/bin/bash
# ==============================================================================
# 概要: MySQLのDBダンプを実行する（世代管理なし）
#
# 機能:
#   - mysql-ops.sh export を使用してmysqldumpを実行
#
# 使い方:
#   bash scripts/db-backup.sh
#
# 環境変数 (任意):
#   CONTAINER  対象のDockerコンテナ名 (デフォルト: laraec-mysql)
#   DUMP_DIR   バックアップ出力先ディレクトリ (デフォルト: {PROJECT_DIR}/dump)
# ==============================================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
DUMP_DIR="${DUMP_DIR:-$PROJECT_DIR/dump}"
CONTAINER="${CONTAINER:-laraec-mysql}"

mkdir -p "$DUMP_DIR"

cd "$PROJECT_DIR"
DUMP_DIR="$DUMP_DIR" bash "$SCRIPT_DIR/mysql-ops.sh" "$CONTAINER" export
