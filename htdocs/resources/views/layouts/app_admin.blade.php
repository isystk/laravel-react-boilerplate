<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')｜{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('/assets/admin/js/app.js') }}" defer></script>
    <script src="{{ asset('/assets/admin/js/jquery-plugins.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('/assets/admin/css/app.css') }}" rel="stylesheet">
</head>


<body class="hold-transition sidebar-mini">
    <div id="app" class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            @guest('admin')
            @else
            <div class="dropdown ml-auto">
                <button type="button" id="dropdown1" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ Auth::user()->name }}
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown1">
                    <a class="dropdown-item" href="#" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                        {{ __('ログアウト') }}
                    </a>

                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
            @endguest
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-purple elevation-4">
            <!-- Brand Logo -->
            <a href="{{ url('/admin') }}" class="brand-link">
                <img src="{{ asset('/assets/admin/image/AdminLTELogo.png') }}" alt="AdminSample Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">LaraEC</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item has-treeview @isset($menu){{ $menu === 'master' ? 'menu-open' : '' }} - @endisset">
                            <a href="#" class="nav-link @isset($menu){{ $menu === 'master' ? 'active' : '' }} - @endisset">
                                <i class="nav-icon fas fa-table"></i>
                                <p>
                                    商品管理
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="@isset($menu){{ $menu === 'master' ? 'display:block;' : '' }} - @endisset">
                                <li class="nav-item">
                                    <a href="{{ url('/admin/stock') }}" class="nav-link @isset($subMenu){{ $subMenu === 'stock' ? 'active' : '' }} - @endisset">
                                        <i class="fas fa-box-open nav-icon"></i>
                                        <p>在庫管理</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/admin/order') }}" class="nav-link @isset($subMenu){{ $subMenu === 'order' ? 'active' : '' }} - @endisset">
                                        <i class="fas fa-cart-arrow-down nav-icon"></i>
                                        <p>注文履歴</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview @isset($menu){{ $menu === 'user' ? 'menu-open' : '' }} - @endisset">
                            <a href="#" class="nav-link @isset($menu){{ $menu === 'user' ? 'active' : '' }} - @endisset">
                                <i class="far fa-frown"></i>
                                <p>
                                    ユーザー管理
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="@isset($menu){{ $menu === 'user' ? 'display:block;' : '' }} - @endisset">
                                <li class="nav-item">
                                    <a href="{{ url('/admin/user') }}" class="nav-link @isset($subMenu){{ $subMenu === 'user' ? 'active' : '' }} - @endisset">
                                        <i class="fa fa-layer-group nav-icon"></i>
                                        <p>顧客管理</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/admin/contact') }}" class="nav-link @isset($subMenu){{ $subMenu === 'contact' ? 'active' : '' }} - @endisset">
                                        <i class="far fa-comment nav-icon"></i>
                                        <p>お問い合わせ</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item has-treeview @isset($menu){{ $menu === 'system' ? 'menu-open' : '' }} - @endisset">
                            <a href="#" class="nav-link @isset($menu){{ $menu === 'system' ? 'active' : '' }} - @endisset">
                                <i class="fa fa-cogs"></i>
                                <p>
                                    システム管理
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="@isset($menu){{ $menu === 'system' ? 'display:block;' : '' }} - @endisset">
                                <li class="nav-item">
                                    <a href="{{ url('/admin/photo') }}" class="nav-link @isset($subMenu){{ $subMenu === 'photo' ? 'active' : '' }} - @endisset">
                                        <i class="fa fa-file-image nav-icon"></i>
                                        <p>画像管理</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- Default to the left -->
            <strong>Copyright &copy; 2019-2020 <a href="#">isystk.com</a>.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

</body>

</html>
