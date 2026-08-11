---
description: DB設計・クエリ性能ルール
paths:
  - "**/*.php"
  - "**/*.sql"
alwaysApply: false
---

# Database Rules

## Query

- `SELECT *`は禁止
- 必要なカラムのみ取得する
- 大量データ取得時に全件ロードしない
- 不要なJOINを避ける
- クエリ発行数を意識する

## Data Loading

- 大量データ処理ではメモリ使用量を考慮する
- 全件取得ではなく適切な分割処理を利用する
- データ量に応じて以下を使い分ける
  - pagination
  - chunk
  - cursor pagination

## Large Data

- TEXT/BLOBなど大容量カラムは必要時のみ取得する
- 一覧表示ではTEXT/BLOBを取得しない
- 詳細表示時に遅延ロードを検討する
- 大容量データを一括メモリ展開しない

## N+1 Prevention

- N+1クエリを発生させない
- リレーション取得時はEager Loadingを検討する
- ループ内でのクエリ発行を避ける
- クエリ数を意識して実装する

## Streaming

- 大量データのレスポンスはストリーミングを検討する
- CSV出力など大量出力ではメモリ効率を考慮する
- 大量データを一度にJSON化しない

## Index

- 検索条件・ソート条件を考慮してIndexを設計する
- インデックス追加前にクエリ実行計画を確認する
- 不要なIndexを増やさない

## Transaction

- 複数テーブルを更新する処理ではTransactionを利用する
- Transaction範囲は必要最小限にする
- 長時間Transactionを避ける

## Migration

- 本番適用済みMigrationを直接変更しない
- Schema変更は新規Migrationで行う
- 大量データMigrationではロック時間を考慮する

## Quality

- 可読性と性能のバランスを考慮する
- 早すぎる最適化は避ける
- データ量増加後も動作する設計を意識する