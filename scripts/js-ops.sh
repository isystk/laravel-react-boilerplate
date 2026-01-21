#!/bin/bash

# ==============================================================================
# Script Name:  Frontend Task Runner (Docker Wrapper)
# Description:  Dockerコンテナ内のJS/TSプロジェクトに対して、フォーマットやテストを実行します。
#               Gitの差分(staged/branch)やファイルパスを指定して、限定的な実行が可能です。
#
# Usage:        ./js-ops.sh {format|test} [branch|staged|file_paths...]
#
# Arguments:
#   COMMAND:    format - Lint, TypeCheck, Prettierを実行
#               test   - テストを実行 (関連するテストファイルを自動抽出)
#
#   DIFF_MODE:  branch    - 現在のブランチの差分を対象
#               staged    - ステージング済みのファイルを対象
#               filepaths - 特定のファイルパスを直接指定
#               (空)      - 全ファイルを対象
#
# Environment:  $LLM_GEMINI_KEY を含む環境変数や .env ファイル、
# ==============================================================================

set -e

COMMAND=$1
shift
DIFF_MODE=$1 # branch, staged, [file_path], or empty (all)

# スクリプトの場所を基準にルートディレクトリを特定
SCRIPT_DIR=$(cd $(dirname $0); pwd)
BASE_DIR=$(dirname "$SCRIPT_DIR")
UTILS_SH=$(dirname $0)/utils.sh
ENV_FILE="$BASE_DIR/.env"

# 内部変数
DOCKER_HOME="$BASE_DIR/docker"
COMPOSE_FILE="$DOCKER_HOME/docker-compose.yml"
DOCKER_CMD="docker compose -f $COMPOSE_FILE --env-file $ENV_FILE"
APP_CMD="$DOCKER_CMD exec -T laraec-app"

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
            # 拡張子のバリエーションを考慮して検索
            local target=$(find "$dir" -maxdepth 1 \( -name "${base_name}.test.[tj]sx" -o -name "${base_name}.spec.[tj]sx" -o -name "${base_name}.test.[tj]s" -o -name "${base_name}.spec.[tj]s" \) | head -n 1)

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
            $APP_CMD npm run lint
            $APP_CMD npm run ts-check
            $APP_CMD npm run prettier -- --write "resources/assets/**/*.{js,jsx,ts,tsx}"
        else
            # ファイルが存在するか、または特殊キーワード(staged/branch等)でないかを確認
            if [ -f "$DIFF_MODE" ] || [[ ! "$DIFF_MODE" =~ ^(staged|branch)$ ]]; then
                echo "📄 指定されたファイルを処理します: $@"
                ALL_DIFF="$@"
            else
                source "$UTILS_SH"
                ALL_DIFF=$(get_diff_files "$DIFF_MODE")
            fi

            # resources/assets 配下などの JS/TS 系ファイルに限定
            DIFF_FILES=$(echo "$ALL_DIFF" | xargs -n1 | grep -E '\.(js|jsx|ts|tsx)$' | xargs -I{} ls -d {} 2>/dev/null || true)

            [ -z "$DIFF_FILES" ] && { echo "✨ 対象のJS/TSファイルは見つかりませんでした。"; exit 0; }

            echo "📝 差分のJS/TSファイルを処理中..."
            $APP_CMD npm run lint -- $(echo "$DIFF_FILES" | xargs)
            $APP_CMD npm run ts-check
            $APP_CMD npm run prettier -- --write $(echo "$DIFF_FILES" | xargs)
        fi
        echo "✅ 完了しました。"
        exit 0
        ;;

    test)
        if [ -z "$DIFF_MODE" ]; then
            echo "🚀 全テストを実行します..."
        else
            if [ -f "$DIFF_MODE" ] || [[ ! "$DIFF_MODE" =~ ^(staged|branch)$ ]]; then
                echo "🔍 指定されたファイルのテストを実行します..."
                JS_DIFF="$@"
            else
                echo "🔍 テスト対象を抽出中 ($DIFF_MODE)..."
                source "$UTILS_SH"
                ALL_DIFF=$(get_diff_files "$DIFF_MODE")
                JS_DIFF=$(echo "$ALL_DIFF" | grep -E '\.(js|jsx|ts|tsx)$' || true)
            fi

            TEST_FILES=$(get_js_test_targets "$JS_DIFF")

            if [ -z "$TEST_FILES" ]; then
                echo "✨ 関連するJS/TSファイルのテストは見つかりませんでした。"; exit 0;
            fi

            echo "🚀 実行: $TEST_FILES"
        fi
        $APP_CMD npm run test -- $TEST_FILES
        exit 0
        ;;

    *)
        echo "Usage: $0 {format|test} [branch|staged|filepaths...]"
        exit 1
        ;;
esac
