@extends('layouts.mail_html')

@section('content')
    <div class="header">ご注文ありがとうございます</div>

    <p>{{ $user->name }} 様</p>

    <p>このたびは「Laraec」をご利用いただき、誠にありがとうございます。<br>
        以下の内容でご注文を承りました。</p>

    <div class="section-title">■ ご注文情報</div>
    <div class="order-detail">
        {{--        ご注文番号：#{{ $order->id }}<br> --}}
        {{--        ご注文日時：{{ $order->created_at->format('Y年m月d日 H:i') }} --}}
        ご請求金額：¥{{ number_format($amount) }}
    </div>

    <div class="section-title">■ ご注文商品</div>
    <div class="order-detail">
        @foreach ($orderItems as $item)
            {{ $item['name'] }} × {{ $item['quantity'] }}　¥{{ number_format($item['price']) }}<br>
        @endforeach
    </div>

    @include('mails.parts.common_footer_html')
@endsection
