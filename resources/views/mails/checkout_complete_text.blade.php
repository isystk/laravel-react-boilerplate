商品の購入が完了しました。
{{ $user->name }}さん
この度は、商品をご購入頂き、誠にありがとうございました。
合計金額：{{ $amount }}円
内訳：

@foreach($orderItems as $items)
    {{ $items['name'] }} {{ $items['quantity'] }}個 {{ $items['price'] }}円
@endforeach
