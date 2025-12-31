# AWS ECS (Fargate) への Dockerコンテナデプロイ手順

このドキュメントでは、LaravelアプリケーションをDocker化し、AWSのECS（Fargate）およびAurora MySQL環境へデプロイする手順を説明します。

## 1. 前提条件

* **AWS CLI**: インストールおよびプロファイル設定済みであること
* **Session Manager Plugin**: `aws ecs execute-command` を使用するためにローカル（またはAWS操作用コンテナ）にインストール済みであること
* **Docker**: イメージのビルドおよびローカルテストに使用
* **CloudFormation**: インフラ構成管理に使用

---

## 2. デプロイフロー

### STEP 1: ECRリポジトリの作成

イメージを格納するためのリポジトリを作成します。

```bash
# AWS CLI操作用コンテナを起動
make awscli

# リポジトリ作成
aws ecr create-repository --repository-name laraec-app --region ap-northeast-1

```

### STEP 2: イメージのビルドとプッシュ

本番用イメージをビルドし、AWSのECRへプッシュします。

```bash
make aws-build

```

### STEP 3: ローカルでの最終動作確認

本番用イメージがDB（ローカルのMySQL）と正しく通信できるかテストします。

```bash
# 本番用イメージをローカルで起動
make aws-test

# 必要に応じてDBマイグレーションを実行
make mysql-migrate

```

---

## 3. インフラ構築とデプロイ

### STEP 4: CloudFormation テンプレートの同期

S3バケットにテンプレートファイルをアップロードします。

```bash
make aws-template-sync

```

### STEP 5: AWSリソースの構築（デプロイ）

VPC、Security Group、Aurora、ECSを順次構築します。

```bash
make aws-deploy

```

> **⚠️ 手動設定**: 構築完了後、AWSコンソールのEC2サービスからセキュリティグループ `SGWeb` を開き、インバウンドルールに「マイ IP」からのポート 80 を許可する設定を追加してください。

---

## 4. データベースの初期化（マイグレーション）

ECSコンテナが起動した後、Aurora MySQLに対してテーブルを作成します。ECS Execを使用してコンテナにログインし、コマンドを実行します。

### コンテナへのログインと実行

```bash
# 1. 起動中のタスクIDを取得してログイン
CLUSTER_NAME="laraec-app-dev-cluster"; \
SERVICE_NAME="laraec-app-dev-service"; \
TASK_ID=$(aws ecs list-tasks --cluster $CLUSTER_NAME --service-name $SERVICE_NAME --query 'taskArns[0]' --output text | cut -d'/' -f3); \
echo "🚀 Entering container ($TASK_ID)..."; \
aws ecs execute-command \
  --cluster $CLUSTER_NAME \
  --task $TASK_ID \
  --container app \
  --interactive \
  --command "/bin/bash"

# 2. コンテナ内でマイグレーションを実行
# php artisan migrate --force

```

---

## 5. アプリケーションの削除

検証が終了し、環境をすべて削除する場合は以下のコマンドを実行します。
※RDS等のデータも削除されるため注意してください。

```bash
make aws-destroy

```
