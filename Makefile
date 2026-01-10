SHELL := /bin/bash
UTILS_SH := ~/dotfiles/scripts/utils.sh
DB_OPS_SH := ./scripts/db-ops.sh
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
	$(DOCKER_CMD) exec app php artisan tinker

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
	stop start

.PHONY: db-login
db-login: ## DBにログインします。
	$(DOCKER_CMD) exec mysql bash -c 'mysql -u $$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE'

.PHONY: db-migrate
db-migrate: ## マイグレーションを実行します。
	$(DOCKER_CMD) exec app php artisan migrate

.PHONY: db-export
db-export: ## DBのdumpファイルをエクスポートします。
	@$(DB_OPS_SH) export

.PHONY: db-import
db-import: ## DBにdumpファイルをインポートします。
	@$(DB_OPS_SH) import

.PHONY: app
app: ## appコンテナに入ります。
	$(DOCKER_CMD) exec app /bin/bash

.PHONY: artisan
artisan: ## AppコンテナでArtisanコマンドを実行します。(使い方: make artisan -- "photo_upload --run")
	 ${DOCKER_CMD} exec app php artisan $(filter-out $@,$(MAKECMDGOALS))

.PHONY: npm-run-dev
npm-run-dev: ## appコンテナで開発用ビルドを実行します。
	$(DOCKER_CMD) exec app npm run dev

.PHONY: npm-run-build
npm-run-build: ## appコンテナでビルドを実行します。
	$(DOCKER_CMD) exec app npm run build; \
	$(DOCKER_CMD) exec app npm run build-storybook;

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
		QUERY="SELECT CONCAT(id, ':', name) FROM users;"; \
		ENDPOINT="user"; \
	else \
		QUERY="SELECT CONCAT(id, ':', name, '(', role, ')') FROM admins;"; \
		ENDPOINT="admin"; \
	fi; \
	LIST=$$(echo "$$QUERY" | $(MYSQL_EXEC) -N -s 2>/dev/null); \
	if [ -z "$$LIST" ]; then \
		echo "ユーザーが見つかりませんでした。"; \
		exit 1; \
	fi; \
	SELECTED=$$(select_from_list "$$LIST" "👤 ログインする $$TYPE を選択してください"); \
	ID=$$(echo $$SELECTED | cut -d':' -f1); \
	URL="http://localhost/skip-login/$$ENDPOINT?id=$$ID"; \
	echo "ID: $$ID ($$TYPE) でログインします..."; \
	open "$$URL"
