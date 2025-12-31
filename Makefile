SHELL := /bin/bash
.SHELLFLAGS := -eu -o pipefail -c

BASE_DIR := $(CURDIR)
DOCKER_HOME := $(BASE_DIR)/docker
COMPOSE_FILE := $(DOCKER_HOME)/docker-compose.yml
ENV_FILE := $(BASE_DIR)/.env
DOCKER_CMD := docker compose -f $(COMPOSE_FILE) --env-file $(ENV_FILE)
TOOLS_CMD := ~/dotfiles/tools/run.sh

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
	@$(DOCKER_CMD) exec awscli /bin/bash

aws-template-sync: ## S3バケットにCfnスタックのテンプレートを同期します
	@$(DOCKER_CMD) exec awscli aws s3 mb s3://laraec-cfm-template --region ap-northeast-1
	@$(DOCKER_CMD) exec awscli aws s3 sync ./template s3://laraec-cfm-template --delete

aws-create-vpc: ## AWSにVPC及びセキュリティグループを作成します
	@$(DOCKER_CMD) exec awscli aws cloudformation create-stack \
		--stack-name laraec-vpc \
		--template-url https://s3-ap-northeast-1.amazonaws.com/laraec-cfm-template/root-stack.yml \
		--capabilities CAPABILITY_NAMED_IAM \
		--parameters \
			ParameterKey=ProjectName,ParameterValue=laraec \
			ParameterKey=Environment,ParameterValue=dev \
			ParameterKey=KeyPairName,ParameterValue=iseyoshitaka

.PHONY: generate-pr
generate-pr: ## PR用の説明文を生成します。
	$(TOOLS_CMD) gemini generate-pr

.PHONY: review
review: ## 変更内容をAIがレビューします。
	$(TOOLS_CMD) gemini review

