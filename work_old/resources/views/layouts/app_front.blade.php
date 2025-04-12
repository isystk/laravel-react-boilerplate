<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link href="{{ asset('/assets/front/css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="react-root"></div>
<script>
    var laravelSession = {};
    laravelSession['status'] = @if(session('status'))'{{session('status')}}'@else''@endif;
    laravelSession['resent'] = @if(session('resent'))'{{session('resent')}}'@else''@endif;
    var laravelErrors =@php print(htmlspecialchars_decode($errors))@endphp;
</script>
<script src="{{ asset('/assets/front/js/app.js') }}" defer></script>
<script src="https://js.stripe.com/v3/"></script>

</body>
</html>
