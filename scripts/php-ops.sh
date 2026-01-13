#!/bin/bash
set -e

COMMAND=$1
DIFF_MODE=${2:-} # branch, staged, [branch_name], or empty (all)

# スクリプトの場所を基準にルートディレクトリを特定
SCRIPT_DIR=$(cd $(dirname $0); pwd)
BASE_DIR=$(dirname "$SCRIPT_DIR")
UTILS_SH=~/dotfiles/scripts/utils.sh
ENV_FILE="$BASE_DIR/.env"

# 内部変数
DOCKER_HOME="$BASE_DIR/docker"
COMPOSE_FILE="$DOCKER_HOME/docker-compose.yml"
DOCKER_CMD="docker compose -f $COMPOSE_FILE --env-file $ENV_FILE"

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
            echo "📝 PHPファイル実行中 (Rector, Pint):"
            # 引数なしで実行することで、ツール側の設定に従い全走査
            $DOCKER_CMD exec -T app ./vendor/bin/rector process --clear-cache
            $DOCKER_CMD exec -T app ./vendor/bin/pint

            echo "🎨 Bladeファイル実行中:"
            # resourcesディレクトリ配下を対象
            npx -y blade-formatter --write "resources/**/*.blade.php"

            echo "🚚 オートロードの整合性を確認中..."
            $DOCKER_CMD exec -T app composer dump-autoload
        else
            source "$UTILS_SH"
            ALL_DIFF=$(get_diff_files "$DIFF_MODE")
            DIFF_FILES=$(echo "$ALL_DIFF" | grep '\.php$' | xargs -r ls -d 2>/dev/null || true)

            [ -z "$DIFF_FILES" ] && { echo "✨ 対象のPHPファイルは見つかりませんでした。"; exit 0; }

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
                        if echo "$WARNINGS" | grep -q "$(basename "$f")"; then
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
        fi
        echo "✅ 完了しました。"
        [ "$DIFF_MODE" = "staged" ] && echo "⚠️ 修正後は再度 'git add' が必要です。"
        exit 0
        ;;

    test)
        if [ -z "$DIFF_MODE" ]; then
            echo "🚀 全テストを実行します..."
            # 引数なしでphpunitを実行
            $DOCKER_CMD exec -e XDEBUG_MODE=off app php -d memory_limit=1G ./vendor/bin/phpunit
        else
            echo "🔍 テスト対象を抽出中 ($DIFF_MODE)..."
            source "$UTILS_SH"
            ALL_DIFF=$(get_diff_files "$DIFF_MODE")
            DIFF_FILES=$(echo "$ALL_DIFF" | grep -E '^(app/|tests/)' || true)
            TEST_FILES=$(get_test_targets "$DIFF_FILES")

            if [ -z "$TEST_FILES" ]; then
                echo "✨ 実行可能なテストはありません。"; exit 0;
            fi

            echo "🚀 実行: $TEST_FILES"
            $DOCKER_CMD exec -e XDEBUG_MODE=off app php -d memory_limit=1G ./vendor/bin/phpunit $TEST_FILES
        fi
        exit 0
        ;;

    *)
        echo "Usage: $0 {format|test} [branch|staged|branch_name]"
        exit 1
        ;;
esac
