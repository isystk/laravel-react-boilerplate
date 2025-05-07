# Laravel コーディング規約

このプロジェクトでは、一貫性と可読性を高めるために、Laravelを使用したアプリケーションの開発において以下のコーディング規約に従います。

---

## 目次

- [基本方針](#基本方針)
- [ファイル構成](#ファイル構成)
- [名前空間とクラス名](#名前空間とクラス名)
- [コーディングスタイル](#コーディングスタイル)
- [リクエスト](#リクエスト)
- [コントローラ](#コントローラ)
- [サービス](#サービス)
- [リポジトリ](#リポジトリ)
- [エンティティ](#エンティティ)
- [マイグレーション](#マイグレーション)
- [ルーティング](#ルーティング)
- [Blade テンプレート](#blade-テンプレート)
- [テスト](#テスト)
- [使用ツール](#使用ツール)

---

## 基本方針

- PHP タグは `<?php` を使用し、**閉じタグは記述しません**。
- インデントは **スペース4つ**とし、タブは使用しません。
- クラス名：`StudlyCase`
- メソッド・変数名：`camelCase`
- 定数：`UPPER_SNAKE_CASE`
- [PSR-1](https://www.php-fig.org/psr/psr-1/) および [PSR-12](https://www.php-fig.org/psr/psr-12/) に準拠します。

---

## ファイル構成

- Laravel 標準のディレクトリ構成に従います。
- ビジネスロジックは `routes/` に記述せず、コントローラやサービス層へ分離します。
- ファイルはその役割に応じて、適切なディレクトリ（例：`Http/Controllers`, `Entities` など）に配置します。

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

- 名前空間は App\〜 を基準とし、Laravel の標準構成に従います。
- クラス名は単数形で、役割が明確に分かる名前にします。

---

## コーディングスタイル

可能な限り型宣言を使用します。

```php
public function store(Request $request): JsonResponse
```

- データベースのカラム名は `snake_case`、コード上の変数は `camelCase` を用います。
- 比較には、厳密な比較演算子 `===` / `!==` を使用します。
- 制御構文では、1行でも必ず波括弧 `{}` を使用します（省略禁止）。

---

## リクエスト

- バリデーションは可能な限り Request クラスに定義し、コントローラには記述しません。
- バリデーションルールは配列形式で記述し、`string` / `integer` など型を明示します。
- ルールの記述順は、型指定 → 条件 → DB 関連 の順とします。
- `nullable` と `required` を同時に使用しないようにします。

```php
public function rules(): array
{
    $maxlength = config('const.maxlength.stocks');
    return [
        'rate' => ['required', 'numeric', 'min:0', 'max:100'],
        'user_id' => ['required', 'integer', 'exists:users,id'],
    ];
}
```

---

## コントローラ

- コントローラはできるだけ薄く保ち、処理はサービスクラスに委譲します。
- Laravel のリソースコントローラ構成（index, show, store など）に準拠します。
- ルートモデルバインディングを活用します。
- トランザクションはコントローラクラス内で実装します。
- 1つのアクションにつき、1つのサービスクラスを作成します。
- サービスクラスのインスタンス生成には app() を使用します。

```php
public function store(StoreRequest $request): RedirectResponse
{
    DB::beginTransaction();
    try {
        /** @var StoreService $service */
        $service = app(StoreService::class);
        $service->createStock($request);
        DB::commit();
    } catch (Throwable $e) {
        DB::rollBack();
        throw $e;
    }
    return redirect(route('stock.index'));
}
```

---

## サービス

- ビジネスロジックの記述に集中します。
- DB へのアクセスは Eloquent を直接使用せず、リポジトリを介します。
- リポジトリの注入には、コンストラクタインジェクションを使用します。

---

## リポジトリ

- リポジトリは `app/Domain/Repositories` に配置します。
- `create`、`update`、`delete`、`getAll`、`findById` などは BaseRepository を継承して実装します。
- 1メソッドで使用する SQL は原則1クエリとし、シンプルな構成にします。
- 関数名は、単一レコードを返す場合は findXXX、複数の場合は getXXX とします。
- すべての関数に、引数と戻り値の型定義を明記します。

```php
/**
 * 指定された orderId に紐づくレコードを返却します。
 */
public function getByOrderId(int $orderId): Collection
{
    /** @var Collection<int, OrderStock> $items */
    return $this->model
        ->where('order_id', $orderId)
        ->get();
}
```

## エンティティ

- エンティティは `app/Domain/Entities` に配置します。
- リレーションは `hasOne` 及び `belongsTo` のみとし、`hasMany` はリポジトリの責務を明確にするため極力避けます。
- 型定義は `@property` アノテーションで記述し、IDE 補完を効かせます。

```php
/**
 * @property int $id
 * @property string|null $name
 * @property int|null $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
 ```

- 日付型カラムは `$casts` に `datetime` を指定し、Carbon インスタンスとして扱います。

```php
protected $casts = [
    'import_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];
```

## マイグレーション

- 命名規則：create_users_table、add_status_to_orders_table など。
- 各カラムには `comment()` を必ず記述します。
- `nullable()` や `default()` を積極的に活用し、意図を明示します。

```php
$table->string('status')->comment('注文ステータス（例：pending, shipped）');
```

## ルーティング

- 保守性とルートの明示性を重視するため `resource()` は使用せず、すべてのルートを明示的に定義します。
- ネストしたルートは `Route::group()` を活用して整理します。
- 名前付きルートを利用し、users.index、orders.show など一貫性を保ちます。

```php
Route::prefix('admin')->group(function () {
    Route::get('stock', [\App\Http\Controllers\Admin\Stock\ListController::class, 'index'])->name('admin.stock');
    Route::get('stock/create', [\App\Http\Controllers\Admin\Stock\CreateController::class, 'create'])->name('admin.stock.create');
    Route::post('stock/store', [\App\Http\Controllers\Admin\Stock\CreateController::class, 'store'])->name('admin.stock.store');
    Route::get('stock/{stock}', [\App\Http\Controllers\Admin\Stock\DetailController::class, 'show'])->name('admin.stock.show');
});
```

## Blade テンプレート

- `@extends`、`@section`、`@yield` を使ってレイアウトを管理します。
- if 文などは簡易な表示制御として許容。ただしデータ加工・判断はサービスまたは ViewModel に移す。
- インデントはスペース4つを使用し、コードと統一します。

## テスト

- テストファイル名は UserControllerTest.php、OrderServiceTest.php のように明確にします。
- Laravel のテストヘルパー（actingAs、assertDatabaseHas など）を積極的に活用します。
- テストメソッド名は、動作の内容がわかるように記述します。

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
    $this->assertEquals('bbb', $user->name, '名前が変更されていること');
    $this->assertEquals('bbb@test.com', $user->email, 'メールアドレスが変更されていること');
}
```

## 使用ツール

- 以下のツールを使用して、コードの品質を自動でチェック・整形します。
- PHPStan：静的解析（./vendor/bin/phpstan analyse --memory-limit=1G）
- PHPUnit：テスト実行（./vendor/bin/phpunit tests）

## 備考

- コードは読みやすく、保守しやすく保つことを最優先とします。
- 複雑な構成は避け、Laravel の「シンプルで表現力豊かな構文」を尊重します。
- 判断に迷った場合は、Laravel 公式ドキュメントを参照してください。

