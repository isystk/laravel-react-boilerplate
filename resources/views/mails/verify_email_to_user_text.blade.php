{{ $user->name }} 様
メールアドレス確認のお願い

「Laraec」へのご登録ありがとうございます。
ご登録を完了するには、以下のボタンをクリックしてメールアドレスの確認をお願いします。

メールアドレスを確認する: {{ $verifyUrl }}

このリンクは {{ config('auth.passwords.users.expire') }} 分間のみ有効です。

心当たりがない場合は、このメールは破棄してください。

@include('mails.parts.common_footer_text')
