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
    @vite('resources/assets/admin/js/pages/common.js')
    @yield('scripts')
</head>
<body>
    <div class="content d-flex vh-100 m-auto justify-content-center align-items-center">
        <div class="w-100 bg-white p-5">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="mb-2">
                        <div class="d-inline-block">
                            <h1>@yield('title')</h1>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            @yield('content')
        </div>
    </div>
</body>
</html>
