# AWS ECS (Fargate) デプロイガイド

LaravelアプリケーションをDocker化し、AWSのECS（Fargate）およびAurora MySQL環境へデプロイするための手順書です。

## 1. 前提条件

デプロイを開始する前に、以下の準備が完了していることを確認してください。

* **AWS CLI**: 適切なIAM権限を持つアクセスキーが設定されていること
* **Docker**: イメージのビルドおよびローカルテストに使用
* **Make**: コマンドの簡略化に使用
* **環境変数**: `.env` ファイルに必要な設定が記述されていること

---

## 2. デプロイ・フロー

デプロイは以下の4つのフェーズで行います。

### Phase 1: Dockerイメージの準備

イメージを格納するリポジトリ（ECR）を作成し、ビルドしたイメージをプッシュします。

```bash
# 1. AWS CLI操作用コンテナの起動
make awscli

# 2. ECRリポジトリの作成
aws ecr create-repository --repository-name laraec-app --region ap-northeast-1

# 3. 本番イメージのビルドとECRへのプッシュ
make aws-build

```

### Phase 2: ローカルでの最終テスト

本番用イメージを使い、ローカル環境のDBと正常に通信できるか確認します。

```bash
# 本番用イメージをローカルで起動
make aws-test

# 必要に応じてDBマイグレーションを実行
make mysql-migrate

```

### Phase 3: AWSインフラの構築

CloudFormationを使用して、VPC、Security Group、Aurora、ECSを構築します。

```bash
# CloudFormationによる一括デプロイ
make aws-deploy

```

> [!IMPORTANT]
> **セキュリティグループの手動設定**
> デプロイ完了後、AWSコンソールのEC2サービスからセキュリティグループ `SGWeb` を開き、インバウンドルールに「マイ IP」からのポート **80** を許可する設定を追加してください。以下のコマンドで編集画面へ直接アクセスできます。

```bash
echo "🌐 セキュリティグループ一覧URL (ここからSGWebを探してください):"
echo "https://${AWS_DEFAULT_REGION}.console.aws.amazon.com/ec2/v2/home?region=${AWS_DEFAULT_REGION}#SecurityGroups:"

```

## Phase 4: アクセス確認

デプロイされたアプリケーションのパブリックIPを取得し、ブラウザでアクセスします。

```bash
CLUSTER_NAME="laraec-app-dev-cluster"; \
SERVICE_NAME="laraec-app-dev-service"; \
TASK_ARN=$(aws ecs list-tasks --cluster $CLUSTER_NAME --service-name $SERVICE_NAME --query 'taskArns[0]' --output text); \
ENI_ID=$(aws ecs describe-tasks --cluster $CLUSTER_NAME --tasks $TASK_ARN --query 'tasks[0].attachments[0].details[?name==`networkInterfaceId`].value' --output text); \
PUBLIC_IP=$(aws ec2 describe-network-interfaces --network-interface-ids $ENI_ID --query 'NetworkInterfaces[0].Association.PublicIp' --output text); \
echo "🌐 Laravel App URL: http://$PUBLIC_IP"

```

---

### Phase 5: データベースの初期化

ECSコンテナからAurora MySQLに対してマイグレーションを実行します。

```bash
# 1. コンテナへのログイン (ECS Exec)
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
php artisan migrate --force

# 3. .envファイルのAPP_URLを更新してビルド
sed -i "s|^APP_URL=.*|APP_URL=http://[]|g" .env
npm run build

```

---

## 3. リソースの削除

検証終了後、作成した全リソースを削除する場合は以下のコマンドを実行します。

> [!CAUTION]
> 実行するとRDS（Aurora）内のデータもすべて削除されます。

```bash
make aws-destroy

```
