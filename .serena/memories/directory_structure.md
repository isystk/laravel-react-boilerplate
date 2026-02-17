# Directory Structure

## バックエンド (app/)

```
app/
├── Console/           # Artisanコマンド
├── Domain/
│   ├── Entities/      # Eloquentモデル (※ app/Models/ ではない)
│   │   ├── User.php, Admin.php, Stock.php, Cart.php
│   │   ├── Order.php, OrderStock.php, Contact.php
│   │   ├── Image.php, ImportHistory.php, MonthlySale.php
│   └── Repositories/  # リポジトリパターン
│       ├── BaseRepository.php (interface)
│       ├── BaseEloquentRepository.php (abstract class)
│       └── {Entity}/  # 各エンティティのリポジトリ
├── Dto/
│   ├── Request/       # リクエストDTO (Admin/, Api/)
│   └── Response/      # レスポンスDTO
├── Enums/             # PHP Enum (AdminRole, Gender, Age, ImageType, etc.)
├── Exceptions/        # 例外クラス
├── FileIO/            # ファイル入出力
├── Helpers/           # ヘルパー関数
├── Http/
│   └── Controllers/
│       ├── BaseController.php
│       ├── Front/     # フロントコントローラ (ReactController, Auth/)
│       ├── Admin/     # 管理画面コントローラ (Stock, User, Contact, etc.)
│       └── Api/       # APIコントローラ (Auth, Profile, Stock, Cart, etc.)
├── Jobs/              # キュージョブ
├── Mails/             # メール
├── Observers/         # Eloquent Observers
├── Providers/         # サービスプロバイダ
├── Rules/             # バリデーションルール
├── Services/          # ビジネスロジック
│   ├── BaseService.php
│   ├── Common/        # 共通サービス (ImageService)
│   ├── Front/         # フロント用サービス (Auth/)
│   ├── Admin/         # 管理画面用サービス
│   ├── Api/           # API用サービス
│   ├── Commands/      # コマンド用サービス
│   └── Jobs/          # ジョブ用サービス
└── Utils/             # ユーティリティ
```

## フロントエンド

```
resources/
├── assets/
│   ├── front/         # React SPA
│   │   ├── app.tsx        # エントリポイント
│   │   ├── router.tsx     # ルーティング
│   │   ├── components/    # 共通コンポーネント
│   │   ├── pages/         # ページコンポーネント
│   │   ├── states/        # 状態管理
│   │   ├── services/      # APIサービス
│   │   ├── constants/     # 定数
│   │   ├── @types/        # TypeScript型定義
│   │   └── assets/        # 静的アセット
│   └── admin/         # 管理画面
│       ├── js/            # JavaScript (vanilla)
│       └── sass/          # SASS
└── views/             # Bladeテンプレート
```

## ルーティング

```
routes/
├── api.php            # APIルート (JWT認証)
├── web.php            # フロントルート
├── admin.php          # 管理画面ルート
├── console.php        # コンソールルート
└── breadcrumbs/       # パンくずリスト
```

## テスト

```
tests/
├── BaseTest.php       # テスト基底クラス (ファクトリヘルパー付き)
├── Unit/              # ユニットテスト
│   ├── Domain/        # Entities, Repositories
│   ├── Dto/           # Request/Response DTO
│   ├── Enums/         # Enum
│   ├── Rules/         # バリデーションルール
│   ├── Helpers/       # ヘルパー
│   ├── Requests/      # FormRequest
│   ├── Mails/         # メール
│   ├── Observers/     # Observer
│   ├── Services/      # サービス (Common, Front, Admin, Api, Commands)
│   └── Utils/         # ユーティリティ
└── Feature/           # フィーチャーテスト
    ├── Console/       # コマンド
    ├── Http/Controllers/  # コントローラ (Front, Admin, Api)
    └── Middleware/     # ミドルウェア
```

## パスエイリアス
- `@` -> `resources/assets/front` (Vite resolve alias, tsconfig paths)
