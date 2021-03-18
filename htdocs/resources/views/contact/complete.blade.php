@extends('layouts.app')

@section('title', 'お問い合わせ完了')

@section('content')
<div class="contentsArea">
    <h2 class="heading02" style="color:#555555;  font-size:1.2em; padding:24px 0px;">
        お問い合わせが完了しました。
    </h2>

    <div class="ta-center">
        <p>お問い合わせが完了しました。担当者から連絡があるまでお待ち下さい。</p>
        <a href="{{ route('shop.list') }}" class="btn text-danger mt40">トップ画面へ</a>
    </div>
</div>
@endsection
