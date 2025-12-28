<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')｜{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/assets/admin/sass/app.scss', 'resources/assets/admin/js/app.js', 'resources/assets/admin/js/pages/common.js'])
    @yield('scripts')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div id="app" class="app-wrapper">
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>

            <div class="ms-auto d-flex align-items-center">
                <div class="dropdown">
                    <button
                        type="button"
                        id="dropdown1"
                        class="btn btn-secondary dropdown-toggle"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown1">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.passwordChange') }}">
                                {{ __('common.Password Change') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('common.Logout') }}
                            </a>
                        </li>
                    </ul>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @include('admin.common.sidemenu')

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">@yield('title')</h3>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-end">
                            @yield('breadcrumbs')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </main>
    <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Anything you want</div>
        <strong>Copyright &copy; 2019-{{ date('Y') }}
            <a href="https://isystk.com" class="text-decoration-none">isystk.com</a>.
        </strong>
        All rights reserved.
    </footer>
</div>
</body>
</html>
