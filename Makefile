SHELL := /bin/bash
.SHELLFLAGS := -eu -o pipefail -c

BASE_DIR := $(CURDIR)
DOCKER_HOME := $(BASE_DIR)/docker
COMPOSE_FILE := $(DOCKER_HOME)/docker-compose.yml
DOCKER_CMD := docker compose -f $(COMPOSE_FILE)

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
	chmod -R 777 "$(DOCKER_HOME)/mysql/logs" "$(DOCKER_HOME)/app/logs"
	rm -rf "$(BASE_DIR)/vendor"
	rm -rf "$(BASE_DIR)/node_modules"
	find "$(BASE_DIR)/storage/app" -mindepth 1 -not -name '.gitignore' -delete

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
	$(DOCKER_CMD) exec app npm run build; $(DOCKER_CMD) exec app npm run build-storybook

.PHONY: prettier
prettier: ## コードフォーマットを実行します。
	$(DOCKER_CMD) exec app npm run prettier; $(DOCKER_CMD) exec app ./vendor/bin/pint

.PHONY: test
test: ## 自動テストを実行します。
	@$(DOCKER_CMD) exec app npm run lint; \
	$(DOCKER_CMD) exec app npm run ts-check; \
	$(DOCKER_CMD) exec app npm run test; \
	$(DOCKER_CMD) exec app ./vendor/bin/phpstan analyse --memory-limit=1G; \
	$(DOCKER_CMD) exec -e XDEBUG_MODE=off app ./vendor/bin/phpunit --display-phpunit-deprecations

.PHONY: summarize
summarize: ## Gitの差分をGeminiで30文字程度に要約します。
	@DIFF=$$(git diff HEAD); \
	if [ -z "$$DIFF" ]; then \
		echo "要約する差分がありません。"; \
	else \
		echo "Geminiで変更点を要約中..."; \
		echo "$$DIFF" | gemini -p "以下のgit diffを、日本語で30文字程度で簡潔に要約してください。1行で、文末の句読点は不要です。"; \
	fi

