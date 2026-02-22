#!/bin/bash

set -euo pipefail

# --- 設定項目 ---
COMMAND=${1:-help}
shift || true

# スクリプトの場所を基準にルートディレクトリを特定
SCRIPT_DIR=$(cd $(dirname $0); pwd)
BASE_DIR=$(dirname "$SCRIPT_DIR")

# .env ファイルから変数をロード
if [ -f "$BASE_DIR/.env" ]; then
    export $(grep -v '^#' "$BASE_DIR/.env" | xargs)
fi

# 内部変数
DOCKER_HOME="$BASE_DIR/docker"
COMPOSE_FILE="$DOCKER_HOME/docker-compose.yml"
ENV_FILE="$BASE_DIR/.env"
APP_NAME="laraec-app"

# コマンド定義
DOCKER_CMD="docker compose -f $COMPOSE_FILE --env-file $ENV_FILE"
AWS_CLI_CMD="$DOCKER_CMD exec -T laraec-aws"

# AWS詳細設定
ECR_DOMAIN="${AWS_ACCOUNT_ID}.dkr.ecr.${AWS_DEFAULT_REGION}.amazonaws.com"
IMAGE_URI="${ECR_DOMAIN}/${APP_NAME}:latest"
TEMPLATE_URL="https://s3.ap-northeast-1.amazonaws.com/${APP_NAME}-cfm-template/main.yml"

# --- メインロジック ---

case "$COMMAND" in
    "build")
        echo "🔐 Logging in to ECR..."
        $AWS_CLI_CMD aws ecr get-login-password --region "$AWS_DEFAULT_REGION" | docker login --username AWS --password-stdin "$ECR_DOMAIN"
        echo "🏗️ Building Docker image..."
        docker build --platform linux/amd64 -t "$APP_NAME" -f "$DOCKER_HOME/app/Dockerfile.ecs" "$BASE_DIR"
        echo "🏷️ Tagging image..."
        docker tag "${APP_NAME}:latest" "$IMAGE_URI"
        echo "🚀 Pushing image to ECR..."
        docker push "$IMAGE_URI"
        ;;

    "test")
        echo "🚀 Starting local test for production image..."
        # 既存のテストコンテナがあれば削除
        docker rm -f "${APP_NAME}-test" 2>/dev/null || true

        # コンテナをバックグラウンドで起動
        docker run -d --rm -p 8080:80 \
            --name "${APP_NAME}-test" \
            --network docker_default \
            -e APP_URL="http://localhost:8080" \
            "${APP_NAME}:latest"

        echo "⏳ Waiting for container to start..."
        sleep 5

        echo "--- 📦 Installing Dev Dependencies for Testing ---"
        docker exec "${APP_NAME}-test" npm install
        docker exec "${APP_NAME}-test" npx playwright install --with-deps chromium

        echo "--- ✅ Running Tests ---"
        docker exec "${APP_NAME}-test" npx vitest run || true
        docker exec "${APP_NAME}-test" ./vendor/bin/phpunit --display-phpunit-deprecations || true

        echo "--- 📝 Access: http://localhost:8080 ---"
        echo "Streaming logs (Press Ctrl+C to stop)..."
        docker logs -f "${APP_NAME}-test"
        ;;

    "template-sync")
        BUCKET_NAME="${APP_NAME}-cfm-template"
        if ! $AWS_CLI_CMD aws s3api head-bucket --bucket "$BUCKET_NAME" 2>/dev/null; then
            echo "Bucket does not exist. Creating bucket...";
            $AWS_CLI_CMD aws s3 mb "s3://$BUCKET_NAME" --region ap-northeast-1
        fi
        echo "Syncing CloudFormation templates to S3 (./docker/aws/template -> s3://${APP_NAME}-cfm-template)..."
        $AWS_CLI_CMD aws s3 sync "./docker/aws/template" "s3://$BUCKET_NAME" --delete
        echo "S3 sync completed successfully."
        ;;

    "deploy")
        "$0" template-sync
        echo "Starting CloudFormation deployment for stack: ${APP_NAME}..."
        echo "Using template: ${TEMPLATE_URL}"
        $AWS_CLI_CMD aws cloudformation create-stack \
            --stack-name "${APP_NAME}-stack" \
            --template-body "file://docker/aws/template/main.yml" \
            --parameters \
                ParameterKey=ProjectName,ParameterValue="$APP_NAME" \
                ParameterKey=Environment,ParameterValue=dev \
                ParameterKey=TemplateURL,ParameterValue="https://${APP_NAME}-cfm-template.s3.ap-northeast-1.amazonaws.com/" \
                ParameterKey=ImageTag,ParameterValue=latest \
            --capabilities CAPABILITY_IAM CAPABILITY_NAMED_IAM \
            --disable-rollback \
            --region ap-northeast-1
        echo "Deployment process finished. Please check the AWS Console for status."
        ;;

    "destroy")
        echo "!!! WARNING !!! This will delete the entire stack: ${APP_NAME}-stack"
        echo -n "Are you sure you want to proceed? [y/N]: "
        read ans
        if [ "${ans:-N}" != "y" ]; then
            echo "Cancelled."
            exit 0
        fi
        BUCKET_NAME=$($AWS_CLI_CMD aws s3 ls | awk '{print $3}' | grep "^${APP_NAME}-.*-images-" | head -n 1 || true)
        if [ -n "$BUCKET_NAME" ]; then
            echo "🧹 Emptying S3 bucket: $BUCKET_NAME..."
            $AWS_CLI_CMD aws s3 rm "s3://$BUCKET_NAME" --recursive
        fi
        echo "Deleting CloudFormation stack: ${APP_NAME}-stack..."
        $AWS_CLI_CMD aws cloudformation delete-stack --stack-name "${APP_NAME}-stack"
        echo "Deletion request submitted. Waiting for stack to be deleted..."
        $AWS_CLI_CMD aws cloudformation wait stack-delete-complete --stack-name "${APP_NAME}-stack"
        echo "Stack '${APP_NAME}-stack' has been successfully deleted."
        ;;

    *)
        echo "Usage: $0 {build|test|template-sync|deploy|destroy}"
        exit 1
        ;;
esac
