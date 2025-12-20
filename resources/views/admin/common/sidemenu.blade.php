<aside class="main-sidebar sidebar-dark-purple elevation-4">
    <a
        href="{{ url('/admin') }}"
        class="brand-link"
    >
        <img
            src="{{ Vite::asset('resources/assets/admin/images/AdminLTELogo.png') }}"
            alt="AdminSample Logo"
            class="brand-image img-circle elevation-3"
            style="opacity: .8"
        />
        <span class="brand-text font-weight-light">LaraEC</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul
                class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false"
            >
                @php
                    $mainMenu = trim($__env->yieldContent('mainMenu'));
                    $subMenu = trim($__env->yieldContent('subMenu'));
                @endphp
                <li class="nav-item has-treeview {{ $mainMenu === 'master' ? 'menu-open' : '' }} ">
                    <a
                        href="#"
                        class="nav-link {{ $mainMenu === 'master' ? 'active' : '' }} "
                    >
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            {{ __('menu.Product') }}
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul
                        class="nav nav-treeview"
                        style="{{ $mainMenu === 'master' ? 'display:block;' : '' }} "
                    >
                        <li class="nav-item">
                            <a
                                href="{{ url('/admin/stock') }}"
                                class="nav-link {{ $subMenu === 'stock' ? 'active' : '' }}"
                            >
                                <i class="fas fa-box-open nav-icon"></i>
                                <p>{{ __('menu.Inventories') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                href="{{ url('/admin/order') }}"
                                class="nav-link {{ $subMenu === 'order' ? 'active' : '' }}"
                            >
                                <i class="fas fa-cart-arrow-down nav-icon"></i>
                                <p>{{ __('menu.Orders') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview {{ $mainMenu === 'user' ? 'menu-open' : '' }}">
                    <a
                        href="#"
                        class="nav-link {{ $mainMenu === 'user' ? 'active' : '' }}"
                    >
                        <i class="far fa-frown"></i>
                        <p>
                            {{ __('menu.User') }}
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul
                        class="nav nav-treeview"
                        style="{{ $mainMenu === 'user' ? 'display:block;' : '' }}"
                    >
                        <li class="nav-item">
                            <a
                                href="{{ url('/admin/user') }}"
                                class="nav-link {{ $subMenu === 'user' ? 'active' : '' }}"
                            >
                                <i class="fa fa-layer-group nav-icon"></i>
                                <p>{{ __('menu.Customers') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                href="{{ url('/admin/contact') }}"
                                class="nav-link {{ $subMenu === 'contact' ? 'active' : '' }}"
                            >
                                <i class="far fa-comment nav-icon"></i>
                                <p>{{ __('menu.Inquiries') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview {{ $mainMenu === 'system' ? 'menu-open' : '' }}">
                    <a
                        href="#"
                        class="nav-link {{ $mainMenu === 'system' ? 'active' : '' }}"
                    >
                        <i class="fa fa-cogs"></i>
                        <p>
                            {{ __('menu.System') }}
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul
                        class="nav nav-treeview"
                        style="{{ $mainMenu === 'system' ? 'display:block;' : '' }}"
                    >
                        <li class="nav-item">
                            <a
                                href="{{ url('/admin/staff') }}"
                                class="nav-link {{ $subMenu === 'staff' ? 'active' : '' }}"
                            >
                                <i class="fa fa-layer-group nav-icon"></i>
                                <p>{{ __('menu.Staffs') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                href="{{ url('/admin/photo') }}"
                                class="nav-link {{ $subMenu === 'photo' ? 'active' : '' }}"
                            >
                                <i class="fa fa-file-image nav-icon"></i>
                                <p>{{ __('menu.Photos') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
