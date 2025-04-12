
商品の購入が完了しました。

{{ $data->name }}さん

この度は、商品をご購入頂き、誠にありがとうございました。

合計金額：{{ $data->amount }}円


内訳：

@foreach($data->stocks as $stock)
{{ $stock->name }} {{ $stock->quantity }}個 {{ $stock->price }}円
@endforeach
