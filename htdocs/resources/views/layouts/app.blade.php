<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')｜{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('/assets/front/js/app.js') }}" defer></script>
    <script src="{{ asset('/assets/front/js/jquery-plugins.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('/assets/front/css/app.css') }}" rel="stylesheet">

</head>

<body>
    <div>
        <header class="header shadow-sm">
            <nav class="navbar navbar-expand-md navbar-light bg-white headerNav">
                <a class="header_logo" href="{{ url('/') }}">
                    <img src="{{ asset('/assets/front/image/logo.png') }}" alt="" class="">
                </a>

                <div class="" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                        <li class="nav-item">
                            <a class="btn btn-danger mr-3" href="{{ route('login') }}">{{ __('ログイン') }}</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="btn btn-link text-danger" href="{{ route('register') }}">{{ __('新規登録') }}</a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="btn btn-link text-danger" href="{{ route('contact.index') }}">
                                お問い合わせ
                            </a>
                        </li>

                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#" onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                    {{ __('ログアウト') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>

                                <a class="dropdown-item" href="#" onclick="event.preventDefault();
                                                        document.getElementById('shop-form').submit();">
                                    カートを見る
                                </a>

                                <form id="shop-form" action="{{ route('shop.mycart') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>

                            </div>
                        </li>

                        <a href="#" onclick="event.preventDefault();
                                                        document.getElementById('shop-form').submit();">
                            <img src="{{ asset('/assets/front/image/cart.png') }}" class="cartImg ml-3">
                        </a>
                        <li class="nav-item">
                            <a class="btn btn-link text-danger" href="{{ route('contact.index') }}">
                                お問い合わせ
                            </a>
                        </li>

                        @endguest

                    </ul>
                </div>
            </nav>
        </header>
        <main class="main">
            @yield('content')
        </main>

        <footer class="footer">
            <div class="footer_inner">
                <p class="mt20"><img src="{{ asset('/assets/front/image/logo_02.png') }}" alt=""></p>
                <p class="mt10"><small class="fz-s">©️isystk このページは架空のページです。実際の人物・団体とは関係ありません。</small></p>
            </div>
        </footer>

    </div>

</body>

</html>
