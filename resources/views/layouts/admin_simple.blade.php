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
<body class="bg-body-secondary">
<div class="d-flex vh-100 justify-content-center align-items-center">
    <div class="container" style="max-width: 800px;">
        <div class="card card-outline card-primary shadow">
            <div class="card-header text-center">
                <h1 class="mb-0"><b>@yield('title')</b></h1>
            </div>
            <div class="card-body">
                @yield('content')
            </div>
        </div>
    </div>
</div>
</body>
</html>
