# Suggested Commands

## Docker操作 (ホストマシンから実行)

```bash
make up              # コンテナ起動
make down            # コンテナ停止
make restart         # 再起動
make init            # 初期化 (.env, volumes, vendor, node_modules をクリーン)
make ps              # コンテナ状態確認
make logs            # ログ表示
make app             # appコンテナに入る
make tinker          # Laravel tinker
make migrate         # マイグレーション実行
make cache-clear     # キャッシュ全クリア
```

## テスト

```bash
# ホストから (JS + PHP 両方)
make test

# Dockerコンテナ内 - PHP
php -d memory_limit=1G ./vendor/bin/phpunit                      # 全テスト
php -d memory_limit=1G ./vendor/bin/phpunit tests/Unit           # Unitのみ
php -d memory_limit=1G ./vendor/bin/phpunit tests/Feature        # Featureのみ
php -d memory_limit=1G ./vendor/bin/phpunit --filter=ClassName   # 特定テスト
php -d memory_limit=1G ./vendor/bin/phpunit path/to/TestFile.php # ファイル指定

# Dockerコンテナ内 - JavaScript/TypeScript
npm run test         # Vitest全テスト

# カバレッジ
make test-coverage   # PHPUnit カバレッジレポート
```

## フォーマット & Lint

```bash
# ホストから (JS + PHP 両方)
make format

# Dockerコンテナ内 - PHP
./vendor/bin/pint                        # Pint (PHP フォーマッタ)
./vendor/bin/rector                      # Rector (PHP リファクタリング)

# Dockerコンテナ内 - JS/TS
npm run lint                             # ESLint
npm run prettier                         # Prettier

# Dockerコンテナ内 - Blade
npx -y blade-formatter --write           # Bladeテンプレート

# 静的解析
./vendor/bin/phpstan analyse             # PHPStan (level 6)
npm run ts-check                         # TypeScript型チェック
```

## ビルド

```bash
# Dockerコンテナ内
npm run dev          # Vite開発サーバー
npm run build        # Vite本番ビルド
npm run storybook    # Storybook開発サーバー (port 6006)
```

## コミット前チェック

```bash
make pre-commit      # format + test を一括実行
```

## Git
```bash
git status           # 状態確認
git diff             # 差分確認
git log --oneline    # コミット履歴
```

## 注意事項
- PHPのテスト・フォーマット・静的解析はDockerコンテナ内で実行する
- テストDBは `testing_database` (MySQL, .env.testingで設定)
- PHPUnit実行時は `memory_limit=1G` を指定する
