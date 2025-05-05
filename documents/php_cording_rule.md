# Laravel コーディング規約

このプロジェクトでは、[PSR-1](https://www.php-fig.org/psr/psr-1/) および [PSR-12](https://www.php-fig.org/psr/psr-12/) に準拠したコーディング規約を採用します。加えて、Laravel におけるベストプラクティスに従います。

---

## 目次

- [基本方針](#基本方針)
- [ファイル構成](#ファイル構成)
- [名前空間とクラス名](#名前空間とクラス名)
- [コーディングスタイル](#コーディングスタイル)
- [コントローラ](#コントローラ)
- [モデル](#モデル)
- [マイグレーション](#マイグレーション)
- [ルーティング](#ルーティング)
- [Blade テンプレート](#blade-テンプレート)
- [テスト](#テスト)
- [使用ツール](#使用ツール)

---

## 基本方針

- PHP タグは `<?php` を使用し、**閉じタグは省略**します。
- インデントは **スペース4つ**を使用し、タブは禁止します。
- クラス名：`StudlyCase`
- メソッド・変数名：`camelCase`
- 定数：`UPPER_SNAKE_CASE`
- PSR-1, PSR-12 に従った構文・命名を行います。

---

## ファイル構成

- Laravel の標準的なディレクトリ構成に従います。
- ビジネスロジックは `routes/` に直接記述せず、コントローラやサービス層に分離します。
- ファイルの役割ごとに適切な場所（例：`Http/Controllers`, `Models` など）へ配置します。

---

## 名前空間とクラス名

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
}
```

- 名前空間は App\〜 をベースに、Laravel の標準構造に従います。
- クラス名は単数形・役割に応じた明確な名前にします。

## コーディングスタイル

- 型宣言を可能な限り使用します。

```php
public function store(Request $request): JsonResponse
```

- データベースのカラム名は `snake_case`、コード上の変数は `camelCase`。
- 厳密な比較演算子 `===` / `!==` を使用します。
- 制御構文には必ず波括弧 `{}` を使用します（1行でも省略しない）。
- 制御構文の後にはスペースを挿入します。

```php
if ($user->isAdmin()) {
    // ...
}
```

## コントローラ

- コントローラは極力薄く保ち、サービスクラスやモデルへ処理を委譲します。
- Laravel のリソースコントローラ構成（index, show, store など）に従います。
- ルートモデルバインディングを活用します。

## モデル

- ビジネスロジックやリレーション定義に集中させます。
- クエリの再利用には Eloquent スコープを利用します。
- $fillable または $guarded を使用して、マスアサインメント対策を行います。

## マイグレーション

- 命名規則：create_users_table, add_status_to_orders_table など。
- 可能であれば各カラムに comment() を付けます。

```php
$table->string('status')->comment('注文ステータス（例：pending, shipped）');
```

## ルーティング

- ルートは `Route::group()` や `Route::resource()` を活用して整理します。

- 名前付きルートを活用し、users.index や orders.show など一貫性を保ちます。

```php
Route::resource('users', UserController::class);
```

## Blade テンプレート

- `@extends`, `@section`, `@yield` を使用してレイアウトを管理します。
- ビジネスロジックはテンプレートに記述しないようにします。
- Blade 構文もコードと同様にインデントします。

```blade
@if ($user->isAdmin())
    <p>管理者としてログインしています</p>
@endif
```

## テスト

- テストファイル名は UserControllerTest.php, OrderServiceTest.php など明確にします。
- Laravel のテストヘルパーを積極的に活用します（actingAs, assertDatabaseHas など）。
- テストメソッド名は動作を明確に記述します。

```php
public function test_guest_cannot_access_dashboard()
```

## 使用ツール

- 以下のツールを導入してコーディング規約を自動でチェック・整形します。
- PHP-CS-Fixer: PSR-12 に基づいたコード整形
- PHPStan / Larastan: 静的解析
- PHP_CodeSniffer: PSR ルールのリント
- Prettier（Blade plugin）: Blade テンプレートの整形

## 備考

- コードは読みやすく、保守しやすく保つことを最優先とします。
- 複雑な構成は避け、Laravel の「シンプルで表現力豊かな構文」を尊重します。
- 迷った場合は、Laravel公式ドキュメント を参照してください。
