<aside class="app-sidebar bg-body-secondary shadow"
       data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ url('/admin') }}"
           class="brand-link">
            <span class="text-white">LaraEC</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false">
                @php
                    $mainMenu = trim($__env->yieldContent('mainMenu'));
                    $subMenu = trim($__env->yieldContent('subMenu'));
                @endphp

                <li class="nav-item {{ $mainMenu === 'master' ? 'menu-open' : '' }}">
                    <a href="#"
                       class="nav-link {{ $mainMenu === 'master' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            商品管理
                            <i class="nav-arrow fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/admin/stock') }}"
                               class="nav-link {{ $subMenu === 'stock' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-box-open"></i>
                                <p>在庫管理</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/admin/order') }}"
                               class="nav-link {{ $subMenu === 'order' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cart-arrow-down"></i>
                                <p>注文履歴</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ $mainMenu === 'user' ? 'menu-open' : '' }}">
                    <a href="#"
                       class="nav-link {{ $mainMenu === 'user' ? 'active' : '' }}">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            ユーザ管理
                            <i class="nav-arrow fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/admin/user') }}"
                               class="nav-link {{ $subMenu === 'user' ? 'active' : '' }}">
                                <i class="nav-icon fa fa-layer-group"></i>
                                <p>顧客管理</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/admin/contact') }}"
                               class="nav-link {{ $subMenu === 'contact' ? 'active' : '' }}">
                                <i class="nav-icon far fa-comment"></i>
                                <p>お問い合わせ</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ $mainMenu === 'system' ? 'menu-open' : '' }}">
                    <a href="#"
                       class="nav-link {{ $mainMenu === 'system' ? 'active' : '' }}">
                        <i class="nav-icon fa fa-cogs"></i>
                        <p>
                            システム管理
                            <i class="nav-arrow fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/admin/staff') }}"
                               class="nav-link {{ $subMenu === 'staff' ? 'active' : '' }}">
                                <i class="nav-icon fa fa-layer-group"></i>
                                <p>スタッフ管理</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/admin/photo') }}"
                               class="nav-link {{ $subMenu === 'photo' ? 'active' : '' }}">
                                <i class="nav-icon fa fa-file-image"></i>
                                <p>画像管理</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
