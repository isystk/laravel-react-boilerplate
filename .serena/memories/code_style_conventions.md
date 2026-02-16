# Code Style & Conventions

## PHP

### 全般
- PHP 8.2+ の機能を活用 (readonly, enum, named arguments, etc.)
- `declare(strict_types=1)` は rector.php 等で使用
- Pint (preset: laravel) でフォーマット

### Pint スタイルルール (pint.json)
- `array_syntax`: short (`[]`)
- `binary_operator_spaces`: `=` と `=>` はalign (位置揃え)
- `blank_line_before_statement`: return, try, throw の前に空行
- `braces_position`: クラス・関数の開き波括弧は改行（シグネチャが複数行の場合を除く）
- `concat_space`: 連結演算子の前後にスペース1つ (`'hello' . ' world'`)
- `no_unused_imports`: 未使用importの除去
- `ordered_imports`: アルファベット順
- `single_quote`: シングルクォート
- `trailing_comma_in_multiline`: 複数行の末尾カンマ
- `php_unit_method_casing`: false (テストメソッド名の制約なし)

### 命名規約
- クラス: PascalCase
- メソッド・変数: camelCase
- テストメソッド: 日本語も可（Pintで`php_unit_method_casing: false`）
- 定数: UPPER_SNAKE_CASE
- 変数名は対象が明確になるように命名 (例: `$userIds` not `$ids`)

### アーキテクチャパターン
- **リポジトリパターン**: `BaseRepository` (interface) -> `BaseEloquentRepository` (abstract) -> 具象クラス
- **サービスレイヤー**: ビジネスロジックは `app/Services/` に集約、コントローラは薄く保つ
- **DTOパターン**: Request/Response に DTO を使用
- **ドメインモデル**: `app/Domain/Entities/` に配置 (標準の `app/Models/` ではない)
- **テスト**: BaseTest に共通ファクトリヘルパー (`createDefaultUser()`, `createDefaultStock()` 等)

### 日付・時刻
- 必ず Carbon を明示的に使用 (`Carbon::now()` not `now()`)

### メール
- テキスト形式を使用 (`->text()` not `->view()`)

### 静的解析
- PHPStan level 6 (Larastan)
- Rector: PHP 8.2 + Laravel 11 ルールセット

## TypeScript / React

### 全般
- TypeScript strict mode (全オプション有効)
- React 19 + JSX Transform (import React不要)
- パスエイリアス: `@/` -> `resources/assets/front/`

### ESLint ルール
- `react-hooks/rules-of-hooks`: error
- `react-hooks/exhaustive-deps`: warn
- `react/jsx-uses-react`: off (JSX Transform)
- `react/react-in-jsx-scope`: off (JSX Transform)

### Prettier ルール (.prettierrc)
- `semi`: true (セミコロンあり)
- `singleQuote`: true (シングルクォート)
- `trailingComma`: all (末尾カンマ)
- `tabWidth`: 2
- `printWidth`: 100
- `arrowParens`: avoid

### EditorConfig
- 文字エンコーディング: UTF-8
- 改行コード: LF
- PHP/Blade: インデント4スペース
- JS/TS/JSON/YAML: インデント2スペース
- 末尾の空白: 削除
- 最終行: 改行あり
