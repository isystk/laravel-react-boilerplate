# Laravel コーディング規約

このプロジェクトでは、[PSR-1](https://www.php-fig.org/psr/psr-1/) および [PSR-12](https://www.php-fig.org/psr/psr-12/) に準拠したコーディング規約を採用します。加えて、Laravel におけるベストプラクティスに従います。

---

## 目次

- [基本方針](#基本方針)
- [ファイル構成](#ファイル構成)
- [名前空間とクラス名](#名前空間とクラス名)
- [コーディングスタイル](#コーディングスタイル)
- [コントローラ](#コントローラ)
- [サービス](#サービス)
- [リポジトリ](#リポジトリ)
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

## Request

- バリデーションは可能な限り Request クラスで定義し、コントローラに記述しない。
- バリデーションルールは配列形式で定義し、`string` / `integer` などの型を明示する。
- ルールの順序は **型指定 → 条件 → DB関連** の順に統一する。
- `nullable` と `required` は同時に使用しない。

```php
public function rules(): array
{
    $maxlength = config('const.maxlength.stocks');
    return [
        'name' => [
            'required',
            'string',
            'max:' . $maxlength['name'],
        ],
    ];
}
```

## コントローラ

- コントローラは極力薄く保ち、サービスクラスへ処理を委譲します。
- Laravel のリソースコントローラ構成（index, show, store など）に従います。
- ルートモデルバインディングを活用します。
- トランザクションが必要な場合は、コントローラクラス内で行う。
- コントローラーの1アクションにつき1サービスクラスを作成する

```php
public function store(StoreRequest $request): RedirectResponse
{
    DB::beginTransaction();
    try {
        /** @var CreateService $service */
        $service = app(CreateService::class);
        $service->save($request);
        DB::commit();
    } catch (Throwable $e) {
        DB::rollBack();
        throw $e;
    }
    return redirect(route('admin.stock'));
}
```

## サービス

- ビジネスロジックに集中させます。
- Eloquent は基本的にリポジトリ経由で利用する。
- サービスは都度呼び出しの柔軟性を重視し、明示的に app() によるサービスロケーターを採用する。
 
## リポジトリ

- 原則として1メソッド1クエリとし条件分岐や繰り返し等は記述しない。
- メソッド名はクエリの内容に合わせる。
- リポジトリクラスのDIは、コンストラクタインジェクションを採用する

## マイグレーション

- 命名規則：create_users_table, add_status_to_orders_table など。
- 各カラムには、`comment()` を付ける。
- `nullable()` や `default()` も積極的に活用して、意図を明確にする。

```php
$table->string('status')->comment('注文ステータス（例：pending, shipped）');
```

## ルーティング

- `Route::resource()` はルートの定義が暗黙的であり、保守性・可読性に欠けるため、明示的なルート定義をする。
- ネストしたルートは `Route::group()` を活用して整理します。
- 名前付きルートを活用し、users.index や orders.show など一貫性を保ちます。

```php
Route::prefix('admin')->group(function ()
{
    Route::get('stock', [\App\Http\Controllers\Admin\Stock\ListController::class, 'index'])->name('admin.stock');
    Route::get('stock/create', [\App\Http\Controllers\Admin\Stock\CreateController::class, 'create'])->name('admin.stock.create');
    Route::post('stock/store', [\App\Http\Controllers\Admin\Stock\CreateController::class, 'store'])->name('admin.stock.store');
    Route::get('stock/{stock}', [\App\Http\Controllers\Admin\Stock\DetailController::class, 'show'])->name('admin.stock.show');
});
```

## Blade テンプレート

- `@extends`, `@section`, `@yield` を使用してレイアウトを管理します。
- ビジネスロジックはテンプレートに記述しないようにします。
- Blade 構文もコードと同様にスペース4つを使用してインデントします。

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
    public function test_更新処理(): void
    {
        $user = $this->createDefaultUser([
            'name' => 'aaa',
            'email' => 'aaa@test.com',
        ]);

        $request = new UpdateRequest();
        $request['name'] = 'bbb';
        $request['email'] = 'bbb@test.com';
        $this->service->update($user->id, $request);

        $user->refresh();
        $this->assertEquals('bbb', $user->name, '名前が変更される事');
        $this->assertEquals('bbb@test.com', $user->email, 'メールアドレスが変更される事');
    }
```

## 使用ツール

- 以下のツールを導入してコーディング規約を自動でチェック・整形します。
- PHP-CS-Fixer: PSR-12 に基づいたコード整形
- PHPStan: 静的解析 (./vendor/bin/phpstan analyse --memory-limit=1G)
- PHPUnit: テスト実行 (./vendor/bin/phpunit tests)

## 備考

- コードは読みやすく、保守しやすく保つことを最優先とします。
- 複雑な構成は避け、Laravel の「シンプルで表現力豊かな構文」を尊重します。
- 迷った場合は、Laravel公式ドキュメント を参照してください。
