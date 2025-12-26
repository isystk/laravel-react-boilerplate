#!/bin/bash

# ==============================================================================
# Git PR Draft Generator with Gemini
# ==============================================================================
# 【概要】
#   リモートブランチを選択し、現在のブランチ（HEAD）との差分を解析します。
#   解析結果をリポジトリ内の .github/pull_request_template.md に流し込み、
#   Gemini API を用いてプルリクエストの下書きを自動生成します。
#
# 【前提条件】
#   1. gemini-cli がインストールされ、APIキーが設定されていること
#   2. fzf (推奨) がインストールされていること（未インストールの場合は select を使用）
#   3. Gitリポジトリのルートまたはサブディレクトリで実行すること
#
# 【使用方法】
#   chmod +x generate_pr.sh
#   ./generate_pr.sh
#
# 【機能】
#   - リモートブランチの最新情報の自動取得 (fetch)
#   - インタラクティブなブランチ選択
#   - 巨大な差分によるトークン制限エラーの回避 (統計情報の活用 + 行数制限)
#   - PRテンプレートへの自動マッピング
# ==============================================================================

## 1. リモート情報の更新
echo "🔎 リモート情報を取得中..."
git fetch --prune > /dev/null 2>&1

## 2. リモートブランチの抽出
# origin/ を除外し、HEADポインタも除外した純粋なブランチ名のリストを作成
branches=$(git branch -r | sed 's/origin\///' | tr -d ' ' | grep -v "HEAD->")

if [ -z "$branches" ]; then
    echo "❌ リモートブランチが見つかりませんでした。"
    exit 1
fi

## 3. ブランチの選択
echo "❓ 比較対象のリモートブランチの番号を入力してください:"
if command -v fzf >/dev/null 2>&1; then
    selected_branch=$(echo "$branches" | fzf --height 40% --reverse --border --header="Select target branch")
else
    PS3="番号を入力してください: "
    select opt in $branches; do
        if [ -n "$opt" ]; then
            selected_branch=$opt
            break
        else
            echo "⚠️ 無効な選択です。"
        fi
    done
fi

if [ -z "$selected_branch" ]; then
    echo "👋 キャンセルされました。"
    exit 0
fi

## 4. PRテンプレートの読み込み
TEMPLATE_PATH=".github/pull_request_template.md"
if [ -f "$TEMPLATE_PATH" ]; then
    PR_TEMPLATE=$(cat "$TEMPLATE_PATH")
    echo "📄 PRテンプレートを読み込みました ($TEMPLATE_PATH)"
else
    PR_TEMPLATE="## 概要\n## 変更点\n## 影響範囲"
    echo "⚠️ テンプレートが見つからないため、基本フォーマットを使用します。"
fi

## 5. 差分の取得（トークン制限対策）
# 統計情報（どのファイルがどれだけ変わったか）
DIFF_STAT=$(git diff --stat origin/"$selected_branch"..HEAD)

# 実際の差分（トークン溢れを防ぐため、空白無視・1000行制限）
DIFF_CONTENT=$(git diff -w origin/"$selected_branch"..HEAD | head -n 1000)

if [ -z "$DIFF_STAT" ]; then
    echo "✅ 差分はありません（現在のブランチは最新です）。"
    exit 0
fi

echo "🤖 Geminiでプルリクエストを生成中..."

## 6. Geminiへの指示（プロンプト構築）
PROMPT="以下のgit差分を分析し、提供されたプルリクエストテンプレートの項目を日本語で埋めてください。
各項目は具体的に、かつ簡潔に記載してください。

# プルリクエストテンプレート
$PR_TEMPLATE

# git差分統計 (ファイル一覧)
$DIFF_STAT

# git差分内容 (コード抜粋)
$DIFF_CONTENT"

# Geminiで生成と表示
echo "$PROMPT" | gemini -p "出力はMarkdown形式のみとし、テンプレートの項目を全て網羅してください。"

echo -e "\n--- ✨ 生成完了 ---"
