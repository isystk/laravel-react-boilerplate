---
description: Git運用ルール
alwaysApply: true
---

# Git Rules

## General

- ユーザーの明示的な指示がない限り、commit・push・tag作成・mergeは行わない
- 履歴を書き換える操作（rebase、reset --hard、force push）は提案のみとし、勝手に実行しない
- 他人の変更を上書きする可能性がある操作は事前に確認する

## Commits

- コミットは1つの論理的な変更単位にまとめる
- 無関係な変更を同じコミットへ含めない
- 自動生成ファイルは必要な場合のみコミットする
- デバッグコードや一時的なコメントを残さない

## Commit Message

- Conventional Commitsを使用する
- 件名は簡潔に記述する
- 必要に応じて本文へ変更理由を記載する

利用するタイプ

- feat
- fix
- refactor
- perf
- docs
- test
- chore
- ci
- build

## Before Commit

コミット前に以下を確認する

- フォーマッターを実行する
- Linterを実行する
- テストが存在する場合は実行する
- 不要なファイルを含めない
- 機密情報が含まれていないことを確認する

## Pull Requests

- PRの説明には変更内容・目的・影響範囲を記載する
- 必要に応じてスクリーンショットを添付する
- レビューしやすいサイズのPRを心掛ける

## Security

以下はコミットしない

- APIキー
- パスワード
- アクセストークン
- 秘密鍵
- .env
- 個人情報

## Generated Files

- lockファイルは依存関係を変更した場合のみ更新する
- ビルド成果物はプロジェクトルールに従う