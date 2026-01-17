#!/bin/bash

# ==============================================================================
# Script Name:  Backend Task Runner (Laravel/Docker Wrapper)
# Description:  Dockerコンテナ内のPHP(Laravel)環境に対して、静的解析・修正・テストを実行します。
#               PHPファイル（Rector/Pint）とBladeファイル（blade-formatter）の両方に対応し、
#               PSR-4 規約の自動チェックも行います。
#
# Usage:        ./php-ops.sh {format|test} [branch|staged|file_paths...]
#
# Arguments:
#   COMMAND:    format - Rector (自動修正), Pint (スタイル修正), blade-formatter を実行
#               test   - PHPUnitを実行 (ソース変更から関連するテストを自動特定)
#
#   DIFF_MODE:  branch    - 現在のブランチの差分を対象
#               staged    - ステージング済みのファイルを対象
#               filepaths - 特定のファイルパスを直接指定
#               (空)      - プロジェクト全ファイルを対象
#
# Features:     - 差分抽出時に `app/*.php` に対応する `tests/*Test.php` を自動検索
#               - `composer dump-autoload` による PSR-4 違反の検知と停止
#               - XDEBUGオフ、メモリ制限1Gでの高速なテスト実行
# ==============================================================================

set -e

COMMAND=$1
shift
DIFF_MODE=$1 # branch, staged, [file_path], or empty (all)

# スクリプトの場所を基準にルートディレクトリを特定
UTILS_SH=~/dotfiles/scripts/utils.sh

SCRIPT_DIR=$(cd $(dirname $0); pwd)
BASE_DIR=$(dirname "$SCRIPT_DIR")
ENV_FILE="$BASE_DIR/.env"

# 内部変数
DOCKER_HOME="$BASE_DIR/docker"
COMPOSE_FILE="$DOCKER_HOME/docker-compose.yml"
DOCKER_CMD="docker compose -f $COMPOSE_FILE --env-file $ENV_FILE"
APP_CMD="$DOCKER_CMD exec -T laraec-app"

# --- 共通関数 ---

# 差分ファイルから関連するテストファイルを抽出する関数
get_test_targets() {
    local diff_files=$1
    local final_files=""

    for file in $diff_files; do
        if [[ $file == tests/*Test.php ]]; then
            final_files="$final_files $file"
        elif [[ $file == app/*.php ]]; then
            local class_name=$(basename "$file" .php)
            local target=$(find tests -name "${class_name}Test.php" -print -quit)
            [ -n "$target" ] && final_files="$final_files $target"
        fi
    done
    echo "$final_files" | tr ' ' '\n' | sort -u | xargs
}

# --- メイン処理 ---

case $COMMAND in
    format)
        if [ -z "$DIFF_MODE" ]; then
            echo "📢 全ファイルを対象にフォーマットを開始します..."
            $APP_CMD ./vendor/bin/rector process --clear-cache
            $APP_CMD ./vendor/bin/pint
            $APP_CMD npx -y blade-formatter --write "resources/**/*.blade.php"
            $APP_CMD composer dump-autoload
        else
            # ファイルが存在するか、または特殊キーワード(staged/branch等)でないかを確認
            if [ -f "$DIFF_MODE" ] || [[ ! "$DIFF_MODE" =~ ^(staged|branch)$ ]]; then
                echo "📄 指定されたファイルを処理します: $@"
                ALL_DIFF="$@" # 全ての引数をファイルパスとして扱う
            else
                source "$UTILS_SH"
                ALL_DIFF=$(get_diff_files "$DIFF_MODE")
            fi

            # 存在するPHPファイルのみに絞り込み
            DIFF_FILES=$(echo "$ALL_DIFF" | xargs -n1 | grep '\.php$' | xargs -I{} ls -d {} 2>/dev/null || true)

            [ -z "$DIFF_FILES" ] && { echo "✨ 対象のPHPファイルは見つかりませんでした。"; exit 0; }

            PHP_FILES=$(echo "$DIFF_FILES" | grep -v '\.blade\.php$' | tr '\n' ' ')
            BLADE_FILES=$(echo "$DIFF_FILES" | grep '\.blade\.php$' | tr '\n' ' ')

            if [ -n "$(echo "$PHP_FILES" | xargs)" ]; then
                echo "📝 PHPファイル実行中 (Rector, Pint):"
                $APP_CMD ./vendor/bin/rector process $PHP_FILES --clear-cache
                $APP_CMD ./vendor/bin/pint $PHP_FILES

                echo "🚚 オートロードの整合性を確認中..."
                WARNINGS=$($APP_CMD composer dump-autoload 2>&1 | grep "does not comply" || true)
                if [ -n "$WARNINGS" ]; then
                    for f in $PHP_FILES; do
                        if echo "$WARNINGS" | grep -q "$(basename "$f")"; then
                            echo "❌ PSR-4 違反: $f"
                            exit 1
                        fi
                    done
                fi
            fi

            if [ -n "$(echo "$BLADE_FILES" | xargs)" ]; then
                echo "🎨 Bladeファイル実行中:"
                $APP_CMD npx -y blade-formatter --write $(echo "$BLADE_FILES" | xargs)
            fi
        fi
        echo "✅ 完了しました。"
        [ "$DIFF_MODE" = "staged" ] && echo "⚠️ 修正後は再度 'git add' が必要です。"
        exit 0
        ;;

    test)
        if [ -z "$DIFF_MODE" ]; then
            echo "🚀 全テストを実行します..."
            # 引数なしでphpunitを実行
            $DOCKER_CMD exec -e XDEBUG_MODE=off app php -d memory_limit=1G ./vendor/bin/phpunit --stop-on-failure --display-phpunit-deprecations
        else
            if [ -f "$DIFF_MODE" ] || [[ ! "$DIFF_MODE" =~ ^(staged|branch)$ ]]; then
                echo "🔍 指定されたファイルのテストを実行します..."
                DIFF_FILES="$@"
            else
                echo "🔍 テスト対象を抽出中 ($DIFF_MODE)..."
                source "$UTILS_SH"
                ALL_DIFF=$(get_diff_files "$DIFF_MODE")
                DIFF_FILES=$(echo "$ALL_DIFF" | grep -E '^(app/|tests/)' || true)
            fi

            TEST_FILES=$(get_test_targets "$DIFF_FILES")

            if [ -z "$TEST_FILES" ]; then
                echo "✨ 実行可能なテストはありません。"; exit 0;
            fi

            echo "🚀 実行: $TEST_FILES"
            $APP_CMD php -d memory_limit=1G ./vendor/bin/phpunit --stop-on-failure --display-phpunit-deprecations $TEST_FILES
        fi
        exit 0
        ;;

    *)
        echo "Usage: $0 {format|test} [branch|staged|filepaths...]"
        exit 1
        ;;
esac
