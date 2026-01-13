SHELL := /bin/bash
UTILS_SH := ~/dotfiles/scripts/utils.sh
MYSQL_OPS_SH := ~/dotfiles/scripts/mysql-ops.sh
JS_OPS_SH := ./scripts/js-ops.sh
PHP_OPS_SH := ./scripts/php-ops.sh
AWS_DEPLOY_SH := ./scripts/aws-deploy.sh
.SHELLFLAGS := -eu -o pipefail -c

# 変数定義
BASE_DIR := $(CURDIR)
DOCKER_HOME := $(BASE_DIR)/docker
COMPOSE_FILE := $(DOCKER_HOME)/docker-compose.yml
ENV_FILE := $(BASE_DIR)/.env
DOCKER_CMD := docker compose -f $(COMPOSE_FILE) --env-file $(ENV_FILE)
APP_CMD := $(DOCKER_CMD) exec app
MYSQL_EXEC := $(DOCKER_CMD) exec -T mysql bash -c 'mysql -N -s -u $$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE'

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

.PHONY: tinker
tinker: ## tinkerを実行します。
	$(APP_CMD) php artisan tinker

.PHONY: init
init: ## 初期化します。
	@if [ ! -f .env ]; then \
		echo "📄 .env not found, copying from .env.example"; \
		cp .env.example .env; \
	fi
	$(DOCKER_CMD) down --rmi all --volumes --remove-orphans
	rm -rf "$(DOCKER_HOME)/mysql/logs" && mkdir -p "$(DOCKER_HOME)/mysql/logs"
	rm -rf "$(DOCKER_HOME)/app/logs" && mkdir -p "$(DOCKER_HOME)/app/logs"
	chmod -R 755 "$(DOCKER_HOME)/mysql/logs" "$(DOCKER_HOME)/app/logs"
	rm -rf "$(BASE_DIR)/vendor"
	rm -rf "$(BASE_DIR)/node_modules"

.PHONY: up
up: ## 起動します。
	$(DOCKER_CMD) up -d --wait

.PHONY: down
down: ## 停止します。
	@pushd "$(DOCKER_HOME)" >/dev/null; docker compose down; popd >/dev/null

.PHONY: restart
restart: ## 再起動します。
	@make down
	@make up

.PHONY: mysql
mysql: ## MySQLデータベースに関する各種操作を行います。
	export DUMP_DIR="./dump" && \
	$(MYSQL_OPS_SH) laraec-mysql

.PHONY: migrate
migrate: ## マイグレーションを実行します。
	$(APP_CMD) php artisan migrate

.PHONY: app
app: ## appコンテナに入ります。
	$(APP_CMD) /bin/bash

.PHONY: npm-run-dev
npm-run-dev: ## appコンテナで開発用ビルドを実行します。
	$(APP_CMD) npm run dev

.PHONY: npm-run-build
npm-run-build: ## appコンテナでビルドを実行します。
	$(APP_CMD) npm run build; \
	$(APP_CMD) npm run build-storybook;

.PHONY: format
format: ## すべてのコード自動整形
	@bash $(JS_OPS_SH) format
	@bash $(PHP_OPS_SH) format

.PHONY: format-branch
format-branch: ## 選択したブランチとローカル差分のコード自動整形
	@bash $(JS_OPS_SH) format branch
	@bash $(PHP_OPS_SH) format branch

.PHONY: format-staged
format-staged: ## ステージング済みのファイルのコード自動整形
	@bash $(JS_OPS_SH) format staged
	@bash $(PHP_OPS_SH) format staged

.PHONY: test
test: ## すべてのテスト実行
	@bash $(JS_OPS_SH) test
	@bash $(PHP_OPS_SH) test

.PHONY: test-branch
test-branch: ## 選択したブランチとローカル差分のテスト実行
	@bash $(JS_OPS_SH) test branch
	@bash $(PHP_OPS_SH) test branch

.PHONY: test-staged
test-staged: ## ステージング済みファイルのテスト実行
	@bash $(JS_OPS_SH) test staged
	@bash $(PHP_OPS_SH) test staged

.PHONY: test-coverage
test-coverage: ## コードカバレッジレポートを出力します。
	$(DOCKER_CMD) exec -e XDEBUG_MODE=coverage app php -d memory_limit=1G ./vendor/bin/phpunit --coverage-text --display-phpunit-deprecations

.PHONY: pre-commit
pre-commit: ## コミット前にすべてのチェックを実行します。
	@make format
	@make test

.PHONY: aws-build
aws-build: ## AWS用のイメージビルドとECRプッシュ
	@$(AWS_DEPLOY_SH) build

.PHONY: aws-test
aws-test: ## ビルドしたイメージのローカル起動確認とテスト
	@$(AWS_DEPLOY_SH) test

.PHONY: aws-template-sync
aws-template-sync: ## S3にCFnテンプレートを同期
	@$(AWS_DEPLOY_SH) template-sync

.PHONY: aws-deploy
aws-deploy: ## AWSへのデプロイ実行
	@$(AWS_DEPLOY_SH) deploy

.PHONY: aws-destroy
aws-destroy: ## AWSスタックの削除
	@$(AWS_DEPLOY_SH) destroy

.PHONY: awscli
awscli: ## AWSコンテナに入ります
	@$(DOCKER_CMD) exec aws /bin/bash

.PHONY: login
login: ## ユーザーまたは管理者を選択してログインします。
	@source $(UTILS_SH); \
	TYPES=$$(printf "user:ユーザー\nadmin:管理者"); \
	TYPE_LABEL=$$(select_from_list "$$TYPES" "📂 ログインタイプを選択してください"); \
	TYPE=$$(echo $$TYPE_LABEL | cut -d':' -f1); \
	if [ "$$TYPE" = "user" ]; then \
		ID=$$( $(MYSQL_OPS_SH) laraec-mysql select --query="SELECT CONCAT(id, ':', name) FROM users;" --name="ユーザー" ); \
		ENDPOINT="user"; \
	else \
	    ID=$$( $(MYSQL_OPS_SH) laraec-mysql select --query="SELECT CONCAT(id, ':', name, '(', role, ')') FROM admins;" --name="管理者" ); \
		ENDPOINT="admin"; \
	fi; \
	URL="http://localhost/skip-login/$$ENDPOINT?id=$$ID"; \
	echo "ID: $$ID ($$TYPE) でログインします..."; \
	open_browser "$$URL"

.PHONY: batch
batch: ## バッチを選択して実行します。
	@source $(UTILS_SH); \
	SELECTED=$$(select_from_list "$$BATCH_COMMANDS" "📂 バッチコマンドを選択してください"); \
	TAB=$$(printf '\t'); \
	CMD=$$(echo "$$SELECTED" | cut -d"$$TAB" -f1); \
	if [ -n "$$CMD" ]; then \
		$(APP_CMD) php artisan $$CMD; \
	fi
# バッチコマンド定義
define BATCH_COMMANDS
export_monthly_sales ./export_monthly_sales.sh --run	月別売上金額出力バッチ
photo_upload  --run	S3アップロードバッチ
endef
export BATCH_COMMANDS
