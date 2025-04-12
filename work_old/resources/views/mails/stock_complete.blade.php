<!DOCTYPE html>
<html lang="ja">
<style>
    body {
        background-color: #fffacd;
    }

    h1 {
        font-size: 16px;
        color: #ff6666;
    }
</style>
<body>
<h1>
    商品の購入が完了しました。
</h1>
<div>
    {{ $data->name }}さん。<br/><br/>
    この度は、商品をご購入頂き、誠にありがとうございました。<br/>
    <br/>
    合計金額：{{ $data->amount }}円<br/>
    <br/>
    内訳：<br/>
    <ul>
        @foreach($data->stocks as $stock)
            <li>{{ $stock->name }} {{ $stock->quantity }}個 {{ $stock->price }}円</li>
        @endforeach
    </ul>
</div>
</body>
</html>
