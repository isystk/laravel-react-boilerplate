
## with関数の使い方

```
// 1つだけ指定（今回紹介したのがこちら）.
$books = App\Book::with('author')->get();

// 複数指定.
$books = App\Book::with(['author', 'publisher'])->get();

// ネストした先も取得.
$books = App\Book::with('author.contacts')->get();

// 指定したカラムのみ取得（注意：IDは必ず含める必要がある）.
$users = App\Book::with('author:id,name')->get();

// 条件指定を追加したい場合.
$users = App\User::with(['posts' => function ($query) {
    $query->where('title', 'like', '%first%');
}])->get();

// ソートしたい場合.
$users = App\User::with(['posts' => function ($query) {
    $query->orderBy('created_at', 'desc');
}])->get();
```

# Laravel Execlインストール
composer require maatwebsite/excel

ァサードを登録
config/app.php
    'providers' => [
+        Maatwebsite\Excel\ExcelServiceProvider::class,
    ],
    'aliases' => [
+        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
    ],
設定ファイルの生成
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"

