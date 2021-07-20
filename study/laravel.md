
## Eloquentの withの使い方

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
    $query->orderBy('created_at', 'asc');
}])->get();

// sortByを使う方法
$posts = App\User::with('posts')->get();
$posts = $posts->sortBy('posts.created_at')->values();
```


## SQLのデバック
```
// 確認したいSQLの前にこれを仕込むとSQLの実行結果が確認できる。
\Illuminate\Support\Facades\Log::debug('========== My Debug ===========');
\Illuminate\Support\Facades\DB::enableQueryLog();
$stocks = $this->stockRepository->findById($id, []);
\Illuminate\Support\Facades\Log::debug(\Illuminate\Support\Facades\DB::getQueryLog());
\Illuminate\Support\Facades\Log::debug($stocks->toArray());
```
```
$ tail -f ./storage/logs/laravel.log
```
