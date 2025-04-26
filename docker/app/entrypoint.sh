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
echo "🔧 Running npm run build..."
npm run build

echo "🔐 Generating app key..."
php artisan key:generate
echo "🧪 Running migrations and seeders..."
php artisan migrate:fresh --seed

echo "🔐 Fixing permissions..."
chmod -R 777 bootstrap/cache storage

# Apache をフォアグラウンドで起動
echo "🚀 Starting Apache..."
exec apache2-foreground
