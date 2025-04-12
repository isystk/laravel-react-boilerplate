<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    @vite('resources/assets/front/sass/app.scss')
</head>
<body>
<div id="react-root"></div>
<script type="module">
    const laravelSession = {};
    laravelSession['status'] = @if(session('status'))'{{session('status')}}'@else''@endif;
    laravelSession['resent'] = @if(session('resent'))'{{session('resent')}}'@else''@endif;
    const laravelErrors =@php print(htmlspecialchars_decode($errors))@endphp;
</script>
@vite('resources/assets/front/ts/app.tsx')
<script src="https://js.stripe.com/v3/"></script>

</body>
</html>
