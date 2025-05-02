@extends('layouts.mail_html')

@section('content')
    <h2>メールアドレス確認のお願い</h2>

    <p>{{ $user->name }} 様</p>

    <p>「Laraec」へのご登録ありがとうございます。<br>
        ご登録を完了するには、以下のボタンをクリックしてメールアドレスの確認をお願いします。</p>

    <p style="text-align: center;">
        <a href="{{ $verifyUrl }}" class="button">メールアドレスを確認する</a>
    </p>

    <p>このリンクは {{ config('auth.passwords.users.expire') }} 分間のみ有効です。</p>

    <p>心当たりがない場合は、このメールは破棄してください。</p>

    @include('mails.parts.common_footer_html')
@endsection
