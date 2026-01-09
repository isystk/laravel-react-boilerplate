@extends('layouts.mail_html')

@section('content')
    <div class="header">パスワードリセットのご案内</div>

    <p>{{ $user->name }} 様</p>

    <p>パスワードのリセットリクエストを受け取りました。<br>
        以下のボタンをクリックして、新しいパスワードを設定してください。</p>

    <p style="text-align: center;">
        <a href="{{ $resetUrl }}"
           class="button">パスワードをリセットする</a>
    </p>

    <p>このリンクは、一定時間が経過すると無効になります。</p>

    <p>もしこのリクエストに心当たりがない場合は、このメールは破棄してください。<br>
        パスワードの変更は行われません。</p>

    @include('mails.parts.common_footer_html')
@endsection
