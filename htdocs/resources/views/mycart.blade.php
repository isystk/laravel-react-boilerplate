@extends('layouts.app')

@section('title', 'マイカート')

@section('content')
<div class="contentsArea">
    <h2 class="heading02">{{ Auth::user()->name }}さんのカートの中身</h2>

    <div class="">
        <p class="text-center mt20">{{ $message ?? '' }}</p><br>

        @if($my_carts->isNotEmpty())
        <div class="block01">
            @foreach($my_carts as $my_cart)
            <div class="block01_item">

                <img src="{{ asset('/uploads/stock/' . $my_cart->stock->imgpath) }}" alt="" class="block01_img">
                <p>{{$my_cart->stock->name}} </p>
                <p class="c-red mb20">{{ number_format($my_cart->stock->price)}}円 </p>

                <form action="{{ route('shop.delete') }}" method="post">
                    @csrf
                    <input type="hidden" name="stock_id" value="{{ $my_cart->stock->id }}">
                    <input type="submit" value="カートから削除する" class="btn-01">
                </form>
            </div>
            @endforeach
        </div>
        <div class="block02">
            <p>合計個数：{{$count}}個</p>
            <p style="font-size:1.2em; font-weight:bold;">合計金額:{{number_format($sum)}}円</p>
        </div>
        <form action="{{ route('shop.check') }}" method="POST">
            @csrf
            <script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-key="{{ env('STRIPE_KEY') }}" data-amount="{{ $sum }}" data-name="Lara EC" data-label="決済をする" data-description="お支払い完了後にメールにてご連絡いたします。" data-image="https://stripe.com/img/documentation/checkout/marketplace.png" data-locale="auto" data-currency="JPY">
            </script>
        </form>

        @else
        <p class="text-center">カートに商品がありません。</p>
        @endif

        <p class="mt30 ta-center"><a href="{{ route('shop.list') }}" class="text-danger btn">商品一覧へ</a></p>
    </div>
</div>
@endsection
