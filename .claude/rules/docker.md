---
description: Docker/docker-compose運用ルール
paths:
  - "**/docker-compose*.yml"
  - "**/Dockerfile"
  - "**/Makefile"
alwaysApply: false
---

# Docker Rules

## General

- 開発環境はDockerを前提とする
- ローカル環境へ依存ライブラリをインストールせず、コンテナ内で実行する
- プロジェクトで定義されたDocker Compose設定を利用する

## Commands

- PHP・Node.js・Pythonなどのコマンドは、可能な限りコンテナ内で実行する
- コンテナ名やサービス名は既存のdocker-compose.ymlに従う

## Images

- 既存のDockerfileを尊重し、不必要な変更を行わない
- ベースイメージはユーザーの指示なく変更しない
- イメージの更新が必要な場合は理由を明示する

## Volumes

- 永続ボリュームを削除しない
- ボリューム初期化が必要な場合は事前にユーザーへ確認する

## Safety

- `docker compose down -v` を実行しない
- `docker system prune` を実行しない
- `docker volume rm` を実行しない
- `docker image prune` を実行しない
- データを削除する可能性のある操作は必ずユーザーへ確認する

## Logs

- エラー調査ではコンテナログを確認する
- コンテナの再作成より原因調査を優先する

## Networking

- コンテナ間通信を優先し、`localhost`へ依存しない
- サービス名をホスト名として利用する

## Quality

- docker-compose.yml・Dockerfileは可読性を維持する
- 不要なレイヤーや重複設定を増やさない
- 既存の構成に合わせる