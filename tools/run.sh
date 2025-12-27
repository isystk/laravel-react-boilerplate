#! /bin/bash

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

confirm() {
    if [ "$FORCE" = true ]; then return 0; fi
    read -r -p "${1:-Are you sure?} [y/N]: " ans
    [[ $ans =~ ^[Yy] ]] && return 0 || return 1
}

usage() {
    awk '
        # 親階層(gemini)の開始
        /^[[:space:]]*(gemini)\)/ { parent = $1; sub(/\).*/, "", parent); next }
        # 親階層の終了
        /^[[:space:]]*esac/ { parent = "" }
        # 説明文の保持
        /^[[:space:]]*## / { desc = $0; sub(/.*## /, "", desc); next }
        # コマンドの出力
        /^[[:space:]]*[a-zA-Z0-9_|-]+\)/ && desc {
            cmd = $1; sub(/\).*/, "", cmd)
            full = (parent && cmd != parent && cmd !~ /help/) ? parent " " cmd : cmd
            printf "  %-25s %s\n", full, desc
            desc = ""
        }
    ' "$0"
}

case "${1}" in

    gemini)
        case "${2}" in
            ## PR用の説明文を自動生成します。
            generate-pr)
                "${SCRIPT_DIR}/generate-pr.sh"
                ;;

            ## 変更内容をAIがレビューします。
            review)
                "${SCRIPT_DIR}/code-review.sh"
                ;;

            *)
                usage
                ;;
        esac
        ;;

    ## ヘルプを表示します。
    help|--help|-h)
        usage
        ;;

    *)
        usage
        exit 1
        ;;
esac
