# Laravel React Boilerplate

## Project Overview

Laravel 12 + React(TypeScript) のボイラープレートプロジェクト。
管理画面(Blade + vanilla JS)とフロント画面(React SPA)の2つのフロントエンドを持つ。

## Tech Stack

- **Backend**: PHP 8.2+ / Laravel 12
- **Frontend (Admin)**: Blade templates + vanilla JS + SASS
- **Frontend (Front)**: React + TypeScript (SPA)
- **CSS**: Tailwind CSS (Front) / SASS (Admin)
- **Build**: Vite
- **Auth**: JWT (tymon/jwt-auth), Laravel Fortify, Sanctum, Socialite
- **Testing**: PHPUnit (PHP), Vitest (JS/TS)
- **Linting**: Pint (PHP), ESLint + Prettier (JS/TS)
- **Storybook**: コンポーネントカタログ
- **Infrastructure**: Docker (`make` コマンドで操作)

## Directory Structure

```
app/
  Domain/          # ドメインモデル(Eloquent)
  Dto/             # Data Transfer Objects
  Enums/           # Enum定義
  Http/Controllers # コントローラ
  Services/        # ビジネスロジック
  Helpers/         # ヘルパー関数
  FileIO/          # ファイル入出力
  Jobs/            # キュージョブ
  Mails/           # メール
  Observers/       # Eloquent Observers
  Rules/           # バリデーションルール
  Utils/           # ユーティリティ
resources/
  assets/
    admin/         # 管理画面 (Blade + JS + SASS)
    front/         # フロント画面 (React SPA)
      components/  # Reactコンポーネント
      pages/       # ページコンポーネント
      states/      # 状態管理
      services/    # APIサービス
      constants/   # 定数
      @types/      # TypeScript型定義
  views/           # Bladeテンプレート
tests/
  Unit/            # ユニットテスト
  Feature/         # フィーチャーテスト
```

## Commands

```bash
# Docker操作
make up            # コンテナ起動
make down          # コンテナ停止
make init          # 初期化
make ps            # コンテナ状態確認
make logs          # ログ表示

# テスト
make test

# フロントエンドのみテスト (Dockerコンテナ内で実行)
npm run test       # Vitest (フロントエンド)

# Lint / Format (Dockerコンテナ内で実行)
npm run lint       # ESLint
npm run prettier   # Prettier

# ビルド (Dockerコンテナ内で実行)
npm run build      # Vite本番ビルド
npm run dev        # Vite開発サーバー

# PHPのみテスト (Dockerコンテナ内で実行)
php -d memory_limit=1G ./vendor/bin/phpunit

# Pint / Rector / Bladeフォーマッタ (Dockerコンテナ内で実行)
./vendor/bin/pint    # Pint
./vendor/bin/rector  # Rector
npx -y blade-formatter --write # Bladeフォーマッタ

# Storybook
npm run storybook  # Storybook開発サーバー
```

## Path Aliases

- `@` -> `resources/assets/front` (Vite resolve alias)

## Conventions

- フロントエンド(React)のコードは `resources/assets/front/` 配下に配置
- 管理画面のJSは `resources/assets/admin/js/` 配下に配置
- ビジネスロジックは `app/Services/` に集約し、コントローラを薄く保つ
- ドメインモデルは `app/Domain/` に配置（標準の `app/Models/` ではない）
