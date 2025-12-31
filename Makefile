SHELL := /bin/bash
.SHELLFLAGS := -eu -o pipefail -c

# 変数定義
BASE_DIR := $(CURDIR)
DOCKER_HOME := $(BASE_DIR)/docker
COMPOSE_FILE := $(DOCKER_HOME)/docker-compose.yml
ENV_FILE := $(BASE_DIR)/.env
DOCKER_CMD := docker compose -f $(COMPOSE_FILE) --env-file $(ENV_FILE)
TOOLS_CMD := ~/dotfiles/tools/run.sh
AWS_CLI_CMD := $(DOCKER_CMD) exec aws
# AWS関連設定
AWS_REGION     := ap-northeast-1
AWS_ACCOUNT_ID := 004796740041
ECR_DOMAIN     := $(AWS_ACCOUNT_ID).dkr.ecr.$(AWS_REGION).amazonaws.com
APP_NAME       := laraec-app
IMAGE_URI      := $(ECR_DOMAIN)/$(APP_NAME):latest
TEMPLATE_URL   := https://s3.ap-northeast-1.amazonaws.com/laraec-cfm-template/root-stack.yml

# デフォルトタスク
.DEFAULT_GOAL := help

.PHONY: help
help: ## ヘルプを表示します。
	@echo "Available commands:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "} {printf "%-20s %s\n", $$1, $$2}'

.PHONY: ps
ps: ## Dockerコンテナの状態を表示します。
	$(DOCKER_CMD) ps

.PHONY: logs
logs: ## Dockerコンテナのログを表示します。
	$(DOCKER_CMD) logs -f

.PHONY: init
init: ## 初期化します。
	$(DOCKER_CMD) down --rmi all --volumes --remove-orphans
	rm -rf "$(DOCKER_HOME)/mysql/logs" && mkdir -p "$(DOCKER_HOME)/mysql/logs"
	rm -rf "$(DOCKER_HOME)/app/logs" && mkdir -p "$(DOCKER_HOME)/app/logs"
	chmod -R 755 "$(DOCKER_HOME)/mysql/logs" "$(DOCKER_HOME)/app/logs"
	rm -rf "$(BASE_DIR)/vendor"
	rm -rf "$(BASE_DIR)/node_modules"

.PHONY: start
start: ## 起動します。
	$(DOCKER_CMD) up -d --wait

.PHONY: stop
stop: ## 停止します。
	@pushd "$(DOCKER_HOME)" >/dev/null; docker compose down; popd >/dev/null

.PHONY: restart
restart: ## 再起動します。
	stop start

.PHONY: tinker
tinker: ## tinkerを実行します。
	$(DOCKER_CMD) exec app php artisan tinker

.PHONY: mysql-login
mysql-login: ## mysqlにログインします。
	$(DOCKER_CMD) exec mysql bash -c 'mysql -u $$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE'

.PHONY: mysql-migrate
mysql-migrate: ## マイグレーションを実行します。
	$(DOCKER_CMD) exec app php artisan migrate

.PHONY: mysql-export
mysql-export: ## mysqlのdumpファイルをエクスポートします。
	@TS=$$(date +%Y%m%d_%H%M%S) && \
	FILE=local_dump_$$TS.sql && \
	$(DOCKER_CMD) exec mysql bash -c 'mysqldump --no-tablespaces -u $$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE' > $$FILE && \
	echo "DBダンプを $$FILE に出力しました"

.PHONY: mysql-import
mysql-import: ## mysqlにdumpファイルをインポートします。
	@if [ -z "$(DUMPFILE)" ]; then \
		echo "使い方: make mysql-import DUMPFILE=ファイル名.sql"; \
		exit 1; \
	fi
	$(DOCKER_CMD) exec -T mysql bash -c 'mysql -u $$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE' < $(DUMPFILE)

.PHONY: app-login
app-login: ## appコンテナに入ります。
	$(DOCKER_CMD) exec app /bin/bash

.PHONY: npm-run-dev
npm-run-dev: ## appコンテナで開発用ビルドを実行します。
	$(DOCKER_CMD) exec app npm run dev

.PHONY: npm-run-build
npm-run-build: ## appコンテナでビルドを実行します。
	$(DOCKER_CMD) exec app npm run build; \
	$(DOCKER_CMD) exec app npm run build-storybook;

.PHONY: prettier
prettier: ## コードフォーマットを実行します。
	$(DOCKER_CMD) exec app npm run prettier; \
	$(DOCKER_CMD) exec app ./vendor/bin/pint;

.PHONY: check
check: ## コードチェックを実行します。
	$(DOCKER_CMD) exec app npm run lint; \
	$(DOCKER_CMD) exec app npm run ts-check; \
	$(DOCKER_CMD) exec app ./vendor/bin/phpstan analyse --memory-limit=1G;

.PHONY: test
test: ## 自動テストを実行します。
	@$(DOCKER_CMD) exec app npm run test; \
	$(DOCKER_CMD) exec -e XDEBUG_MODE=off app ./vendor/bin/phpunit --display-phpunit-deprecations

.PHONY: test-coverage
test-coverage: ## コードカバレッジレポートを出力します。
	$(DOCKER_CMD) exec -e XDEBUG_MODE=coverage app ./vendor/bin/phpunit --coverage-text --display-phpunit-deprecations

.PHONY: pre-commit
pre-commit: ## コミット前にすべてのチェックを実行します。
	@make prettier
	@make check
	@make test

.PHONY: awscli
awscli: ## AWS CLIを実行します。
	@$(AWS_CLI_CMD) /bin/bash

.PHONY: aws-build
aws-build: ## AWS用のDockerイメージをビルド、タグ付け、ECRへプッシュします
	@echo "Logging in to ECR..."
	@$(AWS_CLI_CMD) aws ecr get-login-password --region $(AWS_REGION) | docker login --username AWS --password-stdin $(ECR_DOMAIN)
	@echo "Building Docker image for ECS (platform: linux/amd64)..."
	# プロジェクトルートからビルドし、docker/aws/Dockerfileを適用
	docker build --platform linux/amd64 -t $(APP_NAME) -f ./docker/app/Dockerfile.ecs .
	@echo "Tagging image..."
	docker tag $(APP_NAME):latest $(IMAGE_URI)
	@echo "Pushing image to ECR..."
	docker push $(IMAGE_URI)
	@echo "Deploy complete: $(IMAGE_URI)"

.PHONY: aws-test
aws-test: ## ビルドしたAWS用のDockerイメージをローカルで起動確認します
	@echo "Starting local test for production image..."
	@echo "Access: http://localhost:8080"
	docker run --rm -p 8080:80 \
		--name laraec-app-test \
		--network docker_default \
		-e APP_KEY="base64:$$(openssl rand -base64 32)" \
		-e APP_ENV=local \
		-e APP_DEBUG=true \
		-e APP_URL="http://localhost:8080" \
		$(APP_NAME):latest

.PHONY: aws-template-sync
aws-template-sync: ## S3バケットにCloudFormationのテンプレートを同期します
	@if ! $(AWS_CLI_CMD) aws s3api head-bucket --bucket laraec-cfm-template 2>/dev/null; then \
		echo "Bucket does not exist. Creating bucket..."; \
		$(AWS_CLI_CMD) aws s3 mb s3://laraec-cfm-template --region ap-northeast-1; \
	fi
	@echo "Syncing CloudFormation templates to S3 (./docker/aws/template -> s3://laraec-cfm-template)..."
	@$(AWS_CLI_CMD) aws s3 sync ./docker/aws/template s3://laraec-cfm-template --delete
	@echo "S3 sync completed successfully."

.PHONY: aws-deploy
aws-deploy: ## アプリケーションをAWS ECSにデプロイします
	@echo "Starting CloudFormation deployment for stack: $(APP_NAME)..."
	@echo "Using template: $(TEMPLATE_URL)"
	@$(AWS_CLI_CMD) aws cloudformation deploy \
		--stack-name $(APP_NAME) \
		--template-file ./docker/aws/template/root-stack.yml \
		--capabilities CAPABILITY_NAMED_IAM \
		--parameter-overrides \
			ProjectName=$(APP_NAME) \
			Environment=dev \
			KeyPairName=iseyoshitaka
	@echo "Deployment process finished. Please check the AWS Console for status."

.PHONY: aws-destroy
aws-destroy: ## AWS上のスタックを削除します
	@echo "!!! WARNING !!! This will delete the entire stack: $(APP_NAME)"
	@echo -n "Are you sure you want to proceed? [y/N]: " && read ans && [ $${ans:-N} = y ]
	@echo "Deleting CloudFormation stack: $(APP_NAME)..."
	@$(AWS_CLI_CMD) aws cloudformation delete-stack --stack-name $(APP_NAME)
	@echo "Deletion request submitted. Waiting for stack to be deleted..."
	@$(AWS_CLI_CMD) aws cloudformation wait stack-delete-complete --stack-name $(APP_NAME)
	@echo "Stack '$(APP_NAME)' has been successfully deleted."

.PHONY: generate-pr
generate-pr: ## PR用の説明文を生成します。
	$(TOOLS_CMD) gemini generate-pr

.PHONY: review
review: ## 変更内容をAIがレビューします。
	$(TOOLS_CMD) gemini review

