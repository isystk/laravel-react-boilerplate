
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


## Collectionクラスの使い方

```
// 確認したいSQLの前にこれを仕込むとSQLの実行結果が確認できる。
\Illuminate\Support\Facades\DB::enableQueryLog();
// DBからEloquentで値を取得する（返り値は、get → Collection、findとfirst → Modelのオブジェクト）
$stocks = Stock::get();
print_r(\Illuminate\Support\Facades\DB::getQueryLog());

// Webの場合はログファイルに出力すると確認できる
\Illuminate\Support\Facades\Log::debug(\Illuminate\Support\Facades\DB::getQueryLog());

// 配列に変換するとデバックで参照しやすくなる。
print_r($stocks->toArray());

// 価格が10000円以上のものを1行ずつログで表示する
$minPrice = 10000;
$more10000 = ($stocks ?? collect([]))->filter(function($item, $key) use($minPrice) {
    return ($item->price >= $minPrice);
});
// 1行ずつログで表示する
$more10000->each(function($item, $key) {
    print_r($item->name .': '. $item->price . "\n");
});

// 商品名だけを抜き出してカンマ区切りで表示する
$names = ($stocks ?? collect([]))->pluck('name');
print_r($names->join('、') . "\n");

// 商品IDをキーにしたMapを作成する
$stockMap = ($stocks ?? collect([]))->mapWithKeys(function($stock) {
    return [$stock['id'] => $stock];
});
print_r($stockMap['1'] . "\n");

// 氏名から名字だけを抜き出して、重複しない値だけを取得する
$users = User::get()->map(function($item, $key){
    return explode(" ", $item->name)[0];
});
$unique = ($users ?? collect([]))->unique();
print_r($unique->toArray());

// JSONファイルを出力
file_put_contents("test.json" , json_encode($unique));
```