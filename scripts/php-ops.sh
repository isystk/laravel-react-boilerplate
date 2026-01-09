#!/bin/bash
set -e

COMMAND=$1
DIFF_MODE=$2 # local or staged

# スクリプトの場所を基準にルートディレクトリを特定
SCRIPT_DIR=$(cd $(dirname $0); pwd)
BASE_DIR=$(dirname "$SCRIPT_DIR")
UTILS_SH=~/dotfiles/scripts/utils.sh

# 内部変数
DOCKER_HOME="$BASE_DIR/docker"
DOCKER_CMD="docker compose -f $DOCKER_HOME/docker-compose.yml"

# --- 共通関数 ---

# 比較対象のファイルリストを取得する関数
get_diff_files() {
    local mode=$1
    local filter="d"
    shift 1
    local patterns=("$@")

    if [ "$mode" = "local" ]; then
        local branch_list=$(git branch --format='%(refname:short)' | grep -v "HEAD")
        source "$UTILS_SH"
        local selected_branch=$(select_from_list "$branch_list" "🌿 比較対象のローカルブランチを選択してください")
        [ -z "$selected_branch" ] && { echo "🚫 キャンセルされました。"; exit 1; }
        git diff --name-only --diff-filter=$filter "$selected_branch...HEAD" -- "${patterns[@]}"
    else
        git diff --name-only --cached --diff-filter=$filter -- "${patterns[@]}"
    fi
}

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
        DIFF_FILES=$(get_diff_files "$DIFF_MODE" "*.php")
        [ -z "$DIFF_FILES" ] && { echo "✨ 対象ファイルは見つかりませんでした。"; exit 0; }

        PHP_FILES=$(echo "$DIFF_FILES" | grep -v '\.blade\.php$' | xargs -r ls -d 2>/dev/null | tr '\n' ' ' || true)
        BLADE_FILES=$(echo "$DIFF_FILES" | grep '\.blade\.php$' | xargs -r ls -d 2>/dev/null | tr '\n' ' ' || true)

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
        DIFF_FILES=$(get_diff_files "$DIFF_MODE" "app/" "tests/")

        TEST_FILES=$(get_test_targets "$DIFF_FILES")

        if [ -z "$TEST_FILES" ]; then
            echo "✨ 実行可能なテストはありません。"; exit 0;
        fi

        echo "🚀 実行: $TEST_FILES"
        $DOCKER_CMD exec -e XDEBUG_MODE=off app ./vendor/bin/phpunit $TEST_FILES
        ;;
esac
