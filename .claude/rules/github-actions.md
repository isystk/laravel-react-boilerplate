---
description: CI設計ルール
paths:
  - ".github/workflows/**/*.yml"
  - ".github/workflows/**/*.yaml"
alwaysApply: false
---

# GitHub Actions Rules

## General

- 既存のワークフロー構成を尊重する
- 最小限の変更で目的を達成する
- ワークフローの可読性を優先する

## Workflow Design

- Jobは責務ごとに分割する
- Stepには分かりやすい名前を付ける
- 共通処理は可能な限り再利用する
- 不要な重複を作らない

## Actions

- 公式または信頼できるActionを優先する
- Actionは可能な限りバージョン固定する
- 最新タグではなくメジャーバージョンまたはSHA固定を優先する

## Security

- Secretをログへ出力しない
- Secretをハードコードしない
- 権限は最小権限（Principle of Least Privilege）を採用する
- `permissions`は必要最小限のみ付与する

## Performance

- キャッシュを活用できる場合は利用する
- 不要なJobは実行しない
- 並列実行できるJobは並列化を検討する

## Reliability

- タイムアウトを適切に設定する
- 必要に応じてリトライを検討する
- 一時的なエラーと恒久的なエラーを区別する

## Debugging

- CIを無効化して問題を回避しない
- テストを削除してCIを通さない
- 原因を調査して修正する

## Quality

- Formatter・Lint・TestをCIへ含める
- CIを通すためだけのコード変更を行わない
- 既存の命名規則・構成に合わせる