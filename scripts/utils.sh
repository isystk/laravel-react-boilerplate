#!/bin/bash

# OSの判定
IS_MAC=$( [ "$(uname)" = "Darwin" ] && echo 1 || : )
IS_WSL=$( grep -qi "microsoft" /proc/version 2>/dev/null && echo 1 || : )
IS_LINUX=$( [ -z "$IS_MAC$IS_WSL" ] && echo 1 || : )

# 汎用選択関数
# 第1引数: 改行区切りのリスト文字列
# 第2引数: (任意) ヘッダーメッセージ
select_from_list() {
    local list_data="$1"
    local header_msg="${2:-❓ 項目を選択してください}" # 第2引数が空ならデフォルトメッセージ

    if [ -z "$list_data" ]; then
        echo "❌ 選択肢となるデータが空です。" >&2
        return 1
    fi

    local selected_item=""
    echo "$header_msg" >&2

    if command -v $ENHANCD_FILTER >/dev/null 2>&1; then
        # fzy での選択
        selected_item=$(echo "$list_data" | $ENHANCD_FILTER)
    else
        # fzy がない場合の select
        PS3="番号を入力: "
        # list_dataを配列に変換してselectに渡す
        local IFS=$'\n'
        local options=($list_data)
        select opt in "${options[@]}"; do
            if [ -n "$opt" ]; then
                selected_item=$opt
                break
            else
                echo "⚠️ 無効な選択です。" >&2
            fi
        done
    fi

    if [ -z "$selected_item" ]; then
        return 1
    fi

    echo "$selected_item"
}

# OSに応じてURLをブラウザをで開く関数
# 引数: 開きたいURL
open_browser() {
    if [ -n "$IS_MAC" ]; then
        /usr/bin/open "$@"
    elif [ -n "$IS_WSL" ]; then
        powershell.exe -Command "Start-Process '$1'"
    elif [ -n "$IS_LINUX" ]; then
        xdg-open "$@"
    else
        echo "Unsupported OS"
    fi
}
