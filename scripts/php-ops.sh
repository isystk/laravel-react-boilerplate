#!/bin/bash
set -e

COMMAND=$1
DIFF_MODE=${2:-} # branch, staged, or [branch_name]

# スクリプトの場所を基準にルートディレクトリを特定
SCRIPT_DIR=$(cd $(dirname $0); pwd)
BASE_DIR=$(dirname "$SCRIPT_DIR")
UTILS_SH=~/dotfiles/scripts/utils.sh

# 内部変数
DOCKER_HOME="$BASE_DIR/docker"
DOCKER_CMD="docker compose -f $DOCKER_HOME/docker-compose.yml"

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
        # 全差分を取得
        source "$UTILS_SH"
        ALL_DIFF=$(get_diff_files "$DIFF_MODE")

        # 呼び出し側でフィルタリング（削除されたファイルを除外 --diff-filter=d 相当のチェックも含む）
        # かつ .php ファイルに限定
        DIFF_FILES=$(echo "$ALL_DIFF" | grep '\.php$' | xargs -r ls -d 2>/dev/null || true)

        [ -z "$DIFF_FILES" ] && { echo "✨ 対象ファイルは見つかりませんでした。"; exit 0; }

        PHP_FILES=$(echo "$DIFF_FILES" | grep -v '\.blade\.php$' | tr '\n' ' ')
        BLADE_FILES=$(echo "$DIFF_FILES" | grep '\.blade\.php$' | tr '\n' ' ')

        if [ -n "$(echo "$PHP_FILES" | xargs)" ]; then
            echo "📝 PHPファイル実行中 (Rector, Pint):"
            $DOCKER_CMD exec -T app ./vendor/bin/rector process $PHP_FILES --clear-cache
            $DOCKER_CMD exec -T app ./vendor/bin/pint $PHP_FILES

            echo "🚚 オートロードの整合性を確認中..."
            WARNINGS=$($DOCKER_CMD exec -T app composer dump-autoload 2>&1 | grep "does not comply" || true)
            if [ -n "$WARNINGS" ]; then
                for f in $PHP_FILES; do
                    if echo "$WARNINGS" | grep -q "$f"; then
                        echo "❌ PSR-4 違反: $f"
                        exit 1
                    fi
                done
            fi
        fi

        if [ -n "$(echo "$BLADE_FILES" | xargs)" ]; then
            echo "🎨 Bladeファイル実行中:"
            npx -y blade-formatter --write $(echo "$BLADE_FILES" | xargs)
        fi
        echo "✅ 完了しました。"
        [ "$DIFF_MODE" = "staged" ] && echo "⚠️ 修正後は再度 'git add' が必要です。"
        ;;

    test)
        echo "🔍 テスト対象を抽出中..."
        # 全差分を取得
        source "$UTILS_SH"
        ALL_DIFF=$(get_diff_files "$DIFF_MODE")

        # app/ または tests/ ディレクトリ配下のファイルのみ抽出
        DIFF_FILES=$(echo "$ALL_DIFF" | grep -E '^(app/|tests/)' || true)

        TEST_FILES=$(get_test_targets "$DIFF_FILES")

        if [ -z "$TEST_FILES" ]; then
            echo "✨ 実行可能なテストはありません。"; exit 0;
        fi

        echo "🚀 実行: $TEST_FILES"
        $DOCKER_CMD exec -e XDEBUG_MODE=off app ./vendor/bin/phpunit $TEST_FILES
        ;;

    *)
        echo "Usage: $0 {format|test} {branch|staged|branch_name}"
        exit 1
        ;;
esac
