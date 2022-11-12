<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')｜{{ config('app.name', 'Laravel') }}</title>

    <script src="{{ asset('/assets/admin/js/app.js') }}" ></script>
    <script src="{{ asset('/assets/admin/js/jquery-plugins.js') }}" ></script>
    <script src="https://kit.fontawesome.com/eea364082e.js" crossorigin="anonymous"></script>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link href="{{ asset('/assets/admin/css/app.css') }}" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
    <div id="app" class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            @guest('admin')
            @else
            <div class="dropdown ml-auto">
                <button type="button" id="dropdown1" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ Auth::user()->name }}
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown1">
                    <a class="dropdown-item" href="#" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                        {{ __('common.Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
            @endguest
        </nav>

        @include('admin.common.sidemenu')

        <div class="content-wrapper">
          <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>@yield('title')</h1>
                    </div>
                    <div class="col-sm-6">
                      @yield('breadcrumbs')
                    </div>
                </div>
            </div>
          </div>
          <div class="content">
              <div class="container-fluid">
                  <div class="row">
                      <div class="col-12">
                        @yield('content')
                      </div>
                  </div>
              </div>
          </div>
        </div>
        <footer class="main-footer">
            <strong>Copyright &copy; 2019-2020 <a href="#">isystk.com</a>.</strong> All rights reserved.
        </footer>
    </div>
    @yield('scripts')
</body>
</html>
