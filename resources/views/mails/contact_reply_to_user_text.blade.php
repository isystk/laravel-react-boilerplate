お問い合わせへのご返信

{{ $contact->user->name }} 様

この度はお問い合わせいただき、誠にありがとうございます。
以下の内容にてご返信いたします。

───────────────────────
■ お問い合わせ件名：{{ $contact->title }}

■ 返信内容：
{{ $replyBody }}
───────────────────────

@include('mails.parts.common_footer_text')
