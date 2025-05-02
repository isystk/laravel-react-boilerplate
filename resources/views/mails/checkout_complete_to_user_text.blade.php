ご注文ありがとうございます

{{ $user->name }} 様

このたびは「Laraec」をご利用いただき、誠にありがとうございます。
以下の内容でご注文を承りました。

───────────────────────
{{--■ ご注文番号：#123456--}}
{{--■ ご注文日時：2025年5月2日 14:35--}}
■ ご請求金額：¥{{ number_format($amount) }}
■ ご注文商品：
@foreach($orderItems as $item)
　- 商品名：{{ $item['name'] }}
　- 数量：{{ $item['quantity'] }}
　- 価格：¥{{ number_format($item['price']) }}

@endforeach

───────────────────────

@include('mails.parts.common_footer_text')
