@extends('layouts.app')

@section('title', '購入完了')

@section('content')

<div class="contentsArea">
    <h2 class="heading02" style="color:#555555;  font-size:1.2em; padding:24px 0px;">
        {{ Auth::user()->name }}さんご購入ありがとうございました
    </h2>

    <div class="ta-center">
        <p>ご登録頂いたメールアドレスへ決済情報をお送りしております。お手続き完了次第商品を発送致します。<br>
            (メールは送信されません)</p>
        <a href="{{ route('shop.list') }}" class="btn text-danger mt40">商品一覧へ</a>
    </div>
</div>

@endsection
