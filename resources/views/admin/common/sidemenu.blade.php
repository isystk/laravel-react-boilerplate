<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ url('/admin') }}" class="brand-link">
            <img
                src="{{ Vite::asset('resources/assets/admin/images/AdminLTELogo.png') }}"
                alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow"
            />
            <span class="brand-text fw-light">LaraEC</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul
                class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false"
            >
                @php
                    $mainMenu = trim($__env->yieldContent('mainMenu'));
                    $subMenu = trim($__env->yieldContent('subMenu'));
                @endphp

                <li class="nav-item {{ $mainMenu === 'master' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $mainMenu === 'master' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            {{ __('menu.Product') }}
                            <i class="nav-arrow fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/admin/stock') }}" class="nav-link {{ $subMenu === 'stock' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-box-open"></i>
                                <p>{{ __('menu.Inventories') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/admin/order') }}" class="nav-link {{ $subMenu === 'order' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cart-arrow-down"></i>
                                <p>{{ __('menu.Orders') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ $mainMenu === 'user' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $mainMenu === 'user' ? 'active' : '' }}">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            {{ __('menu.User') }}
                            <i class="nav-arrow fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/admin/user') }}" class="nav-link {{ $subMenu === 'user' ? 'active' : '' }}">
                                <i class="nav-icon fa fa-layer-group"></i>
                                <p>{{ __('menu.Customers') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/admin/contact') }}" class="nav-link {{ $subMenu === 'contact' ? 'active' : '' }}">
                                <i class="nav-icon far fa-comment"></i>
                                <p>{{ __('menu.Inquiries') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ $mainMenu === 'system' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $mainMenu === 'system' ? 'active' : '' }}">
                        <i class="nav-icon fa fa-cogs"></i>
                        <p>
                            {{ __('menu.System') }}
                            <i class="nav-arrow fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/admin/staff') }}" class="nav-link {{ $subMenu === 'staff' ? 'active' : '' }}">
                                <i class="nav-icon fa fa-layer-group"></i>
                                <p>{{ __('menu.Staffs') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/admin/photo') }}" class="nav-link {{ $subMenu === 'photo' ? 'active' : '' }}">
                                <i class="nav-icon fa fa-file-image"></i>
                                <p>{{ __('menu.Photos') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
