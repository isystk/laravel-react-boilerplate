<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >
    <title>@yield('title')｜{{ config('app.name', 'Laravel') }}</title>
    @vite('resources/assets/admin/sass/app.scss')
    @vite('resources/assets/admin/js/app.js')
    @vite('resources/assets/admin/js/plugins/index.js')
    @vite('resources/assets/admin/js/pages/common.js')
    @yield('scripts')
</head>
<body class="hold-transition sidebar-mini {{ auth()->guard('admin')->check() ? '' : 'no-sidemenu' }}">
<div
    id="app"
    class="wrapper"
>
    @auth('admin')
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a
                        class="nav-link"
                        data-widget="pushmenu"
                        href="#"
                    >
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
            <div class="dropdown ml-auto">
                <button
                    type="button"
                    id="dropdown1"
                    class="btn btn-secondary dropdown-toggle"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    {{ Auth::user()->name }}
                </button>
                <div
                    class="dropdown-menu dropdown-menu-right"
                    aria-labelledby="dropdown1"
                >
                    <a
                        class="dropdown-item"
                        href="{{ route('admin.passwordChange') }}"
                    >
                        {{ __('common.Password Change') }}
                    </a>
                    <a
                        class="dropdown-item"
                        href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    >
                        {{ __('common.Logout') }}
                    </a>
                    <form
                        id="logout-form"
                        action="{{ route('admin.logout') }}"
                        method="POST"
                        style="display: none;"
                    >
                        @csrf
                    </form>
                </div>
            </div>
        </nav>
        @include('admin.common.sidemenu')
    @endauth
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <div class="d-inline-block">
                        <h1>@yield('title')</h1>
                    </div>
                    <div
                        class="d-inline-block"
                        style="float: right"
                    >
                        @yield('breadcrumbs')
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2019-2020
            <a href="#">isystk.com</a>
            .
        </strong>
        All rights reserved.
    </footer>
</div>
</body>
</html>
