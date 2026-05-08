#!/bin/bash
set -e  # エラーが発生した時点で終了

cd /var/www/html

# .env がなければコピー
echo "📦 Checking .env file..."
if [ ! -f .env ]; then
  echo "📄 .env not found, copying from .env.example"
  cp .env.example .env
else
  echo "✅ .env already exists"
fi

# Laravel セットアップ
echo "🔧 Running composer install..."
composer install
echo "🔧 Running npm install..."
npm install

# node_modules 内の Playwright のバージョンに完全に一致するブラウザをダウンロード
npx playwright install chromium

if [[ "${APP_ENV}" = "local" && "${CI}" != "true" ]]; then
  echo "🔧 Running npm run build..."
  npm run build || echo "Build failed: ignoring and continuing"
fi

echo "🔐 Generating app key..."
php artisan key:generate

echo "🔒 Fixing permissions..."
chmod -R 777 bootstrap/cache storage

if [ "${CI}" != "true" ]; then
  ## Minio にバケットを作成
  echo "🪣 Setup Bucket for Minio..."
  mc alias set minio http://laraec-s3:9000 admin password
  # 初回なら Minio にバケットを作成
  if ! mc ls minio/laraec.isystk.com >/dev/null 2>&1; then
      mc mb minio/laraec.isystk.com
      mc anonymous set download minio/laraec.isystk.com
  else
    echo "✅ Minio Setup skipped (Bucket already exist)"
  fi
fi

if [[ "${APP_ENV}" = "local" && "${CI}" != "true" ]]; then
  echo "🧪 Running migrations..."
  php artisan migrate --force
  # 初回なら seeder 実行（users テーブルが空かチェック）
  echo "📊 Checking if seeding is needed..."
  if [ "$(php artisan tinker --execute "echo \App\Domain\Entities\User::count();")" = "0" ]; then
    echo "🌱 Seeding database..."
    php artisan db:seed --force
  else
    echo "✅ Database seeding skipped (users already exist)"
  fi
fi

if [ "${CI}" != "true" ]; then
  ## Laravel キューリスナをバックグラウンドで実行
  echo "🎧 Starting queue listener..."
  php artisan queue:listen --timeout=0 &

  ## Laravel スケジューラをバックグラウンドで実行
  echo "⏰ Starting scheduler..."
  php artisan schedule:work &
fi

## Storybook バックグラウンドで実行 (local環境のみ)
if [[ "${APP_ENV}" = "local" && "${CI}" != "true" ]]; then
  echo "📖 Starting Storybook..."
  npm run storybook > /dev/null 2>&1 &
fi

# セットアップ完了フラグ
touch /tmp/entrypoint-done

# Apache をフォアグラウンドで起動
echo "🚀 Starting Apache..."
exec apache2-foreground
