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

get_js_test_targets() {
    local diff_files=$1
    local final_files=""

    for file in $diff_files; do
        # 1. すでにテストファイル自体の場合はそのまま追加
        if [[ $file =~ \.(test|spec)\.(js|jsx|ts|tsx)$ ]]; then
            final_files="$final_files $file"
        # 2. ソースファイルの場合は対応するテストファイルを検索
        elif [[ $file =~ \.(js|jsx|ts|tsx)$ ]]; then
            local base_path="${file%.*}"
            local dir=$(dirname "$file")
            local base_name=$(basename "$base_path")
            local target=$(find "$dir" -maxdepth 1 -name "${base_name}.test.[tj]sx" -o -name "${base_name}.spec.[tj]sx" -o -name "${base_name}.test.[tj]s" -o -name "${base_name}.spec.[tj]s" | head -n 1)

            [ -n "$target" ] && final_files="$final_files $target"
        fi
    done
    echo "$final_files" | tr ' ' '\n' | sort -u | xargs
}

# --- メイン処理 ---

case $COMMAND in
    format)
        if [ -z "$DIFF_MODE" ]; then
            echo "📢 全JS/TSファイルを対象にフォーマットを開始します..."
            $DOCKER_CMD exec -T app npm run lint
            $DOCKER_CMD exec -T app npm run ts-check
            $DOCKER_CMD exec -T app npm run prettier -- --write "resources/assets/**/*.{js,jsx,ts,tsx}"
        else
            source "$UTILS_SH"
            ALL_DIFF=$(get_diff_files "$DIFF_MODE")

            # resources/assets 配下などの JS/TS 系ファイルに限定
            DIFF_FILES=$(echo "$ALL_DIFF" | grep -E '\.(js|jsx|ts|tsx)$' | xargs -r ls -d 2>/dev/null || true)

            [ -z "$DIFF_FILES" ] && { echo "✨ 対象ファイルは見つかりませんでした。"; exit 0; }

            echo "📝 差分ファイルを処理中..."
            $DOCKER_CMD exec -T app npm run lint -- $DIFF_FILES
            $DOCKER_CMD exec -T app npm run ts-check
            $DOCKER_CMD exec -T app npm run prettier -- --write $DIFF_FILES
        fi
        echo "✅ 完了しました。"
        exit 0
        ;;

    test)
        if [ -z "$DIFF_MODE" ]; then
            echo "🚀 全テストを実行します..."
            $DOCKER_CMD exec -T app npm run test
        else
            echo "🔍 テスト対象を抽出中 ($DIFF_MODE)..."
            source "$UTILS_SH"
            ALL_DIFF=$(get_diff_files "$DIFF_MODE")

            # 差分からJS/TS関連ファイルを抽出
            JS_DIFF=$(echo "$ALL_DIFF" | grep -E '\.(js|jsx|ts|tsx)$' || true)
            TEST_FILES=$(get_js_test_targets "$JS_DIFF")

            if [ -z "$TEST_FILES" ]; then
                echo "✨ 関連するテストは見つかりませんでした。"; exit 0;
            fi

            echo "🚀 実行: $TEST_FILES"
            $DOCKER_CMD exec -T app npm run test -- $TEST_FILES
        fi
        exit 0
        ;;

    *)
        echo "Usage: $0 {format|test} [branch|staged|branch_name]"
        exit 1
        ;;
esac
