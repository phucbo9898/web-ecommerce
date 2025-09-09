{{-- Main Sidebar Container --}}
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    {{-- Brand Logo --}}
    <a href="#" class="brand-link">
        <img src="{{ asset('admin_lte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Trang quản trị</span>
    </a>

    <!--Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                {{-- Tab category --}}
                <li class="nav-item has-treeview {{ request()->is('admin/categories*') ? 'menu-open' : '' }}">
                    <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-cubes"></i>
                        <p>
                            @lang('Danh mục sản phẩm')
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ Route('admin.category.index') }}" style="margin-left: 15%;padding-left: 0px;"
                               class="nav-link {{ request()->is('admin/categories') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('Danh sách')</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.category.create') }}" style="margin-left: 15%;padding-left: 0px;"
                               class="nav-link {{ request()->is('admin/categories/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('Tạo mới')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- End tab Category --}}

                {{-- Tab Attribute --}}
                <li class="nav-item has-treeview {{ request()->is('admin/attribute*') ? 'menu-open' : '' }}">
                    <a class="nav-link {{ request()->is('admin/attribute*') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-flask"></i>
                        <p>
                            @lang('Thuộc tính')
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ Route('admin.attribute.index') }}" style="margin-left: 15%;padding-left: 0px;"
                                class="nav-link {{ request()->is('admin/attribute') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('Danh sách')</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ Route('admin.attribute.create') }}" style="margin-left: 15%;padding-left: 0px;"
                                class="nav-link {{ request()->is('admin/attribute/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('Tạo mới')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- End tab Attribute --}}

                {{-- Tab product --}}
                <li class="nav-item has-treeview {{ request()->is('admin/product*') ? 'menu-open' : '' }}">
                    <a class="nav-link {{ request()->is('admin/product*') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-desktop"></i>
                        <p>
                            @lang('Sản phẩm')
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.product.index') }}" style="margin-left: 15%;padding-left: 0px;"
                                class="nav-link {{ request()->is('admin/product') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('Danh sách')</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.product.create') }}" style="margin-left: 15%;padding-left: 0px;"
                                class="nav-link {{ request()->is('admin/product/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('Tạo mới')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- End tab product --}}

                {{-- Tab warehouse --}}
                <li class="nav-item has-treeview {{ request()->is('admin/warehouse*') ? 'menu-open' : '' }} ">
                    <a class="nav-link {{ request()->is('admin/warehouse*') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-archive"></i>
                        <p>
                            @lang('Kho hàng')
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.warehouse.import') }}" style="margin-left: 15%;padding-left: 0px;"
                                class="nav-link {{ request()->is('admin/warehouse') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('Nhập hàng')</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.warehouse.history-import') }}" style="margin-left: 15%;padding-left: 0px;"
                                class="nav-link {{ request()->is('admin/warehouse/history-import') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('Lịch sử nhập hàng')</p>
                            </a>
                        </li>
                    </ul>
                    {{--                    <ul class="nav nav-treeview"> --}}
                    {{--                        <li class="nav-item"> --}}
                    {{--                            <a href="{{ route('admin.warehouse.history-export') }}" --}}
                    {{--                               style="margin-left: 15%;padding-left: 0px;" --}}
                    {{--                               class="nav-link {{ request()->is('admin/warehouse/history-export') ? 'active' : '' }}"> --}}
                    {{--                                <i class="far fa-circle nav-icon"></i> --}}
                    {{--                                <p>@lang('Lịch sử xuất hàng')</p> --}}
                    {{--                            </a> --}}
                    {{--                        </li> --}}
                    {{--                    </ul> --}}
                </li>
                {{-- End tab warehouse --}}

                {{-- Tab Slide --}}
                <li class="nav-item has-treeview {{ request()->is('admin/slide*') ? 'menu-open' : '' }}">
                    <a class="nav-link {{ request()->is('admin/slide*') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-window-maximize"></i>
                        <p>
                            @lang('Slide')
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.slide.index') }}" style="margin-left: 15%;padding-left: 0px;"
                                class="nav-link {{ request()->is('admin/slide') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('Danh sách')</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.slide.create') }}" style="margin-left: 15%;padding-left: 0px;"
                                class="nav-link {{ request()->is('admin/slide/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('Tạo mới')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Entab Slide --}}

                {{-- Tab article --}}
                <li class="nav-item has-treeview {{ request()->is('admin/article*') ? 'menu-open' : '' }}">
                    <a class="nav-link {{ request()->is('admin/article*') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-newspaper"></i>
                        <p>
                            @lang('Bài viết')
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.article.index') }}" style="margin-left: 15%;padding-left: 0px;"
                                class="nav-link {{ request()->is('admin/article') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('Danh sách')</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.article.create') }}" style="margin-left: 15%;padding-left: 0px;"
                                class="nav-link {{ request()->is('admin/article/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('Tạo mới')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- End tab article --}}

                {{-- Tab user --}}
                <li class="nav-item has-treeview {{ request()->is('admin/user*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/user*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            @lang('Người dùng')
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.user.index') }}" style="margin-left: 15%;padding-left: 0px;"
                                class="nav-link {{ request()->is('admin/user') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('Danh sách')</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.user.create') }}" style="margin-left: 15%;padding-left: 0px;"
                                class="nav-link {{ request()->is('admin/user/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('Tạo mới')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- End tab user --}}

                {{-- Tab voucher --}}
                <li class="nav-item has-treeview {{ request()->is('admin/voucher*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/voucher*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-gift"></i>
                        <p>
                            @lang('Phiếu giảm giá')
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.voucher.index') }}" style="margin-left: 15%;padding-left: 0px;"
                                class="nav-link {{ request()->is('admin/voucher') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('Danh sách')</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.voucher.create') }}" style="margin-left: 15%;padding-left: 0px;"
                                class="nav-link {{ request()->is('admin/voucher/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang('Tạo mới')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- End tab voucher --}}

                {{-- Tab comment --}}
                <li class="nav-item has-treeview {{ request()->is('admin/comment*') ? 'menu-open' : '' }}">
                    <a href="{{ route('admin.comment.index') }}" class="nav-link {{ request()->is('admin/comment*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-american-sign-language-interpreting"></i>
                        <p>@lang('Đánh giá sản phẩm')</p>
                    </a>
                </li>
                {{-- End tab comment --}}

                {{-- Tab transaction --}}
                <li class="nav-item has-treeview {{ request()->is('admin/transaction*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/transaction*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>@lang('Giao dịch')</p>
                    </a>
                </li>
                {{-- End tab transaction --}}

                {{-- Tab transaction --}}
                <li class="nav-item has-treeview {{ request()->is('admin/statistics*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/statistics*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-print"></i>
                        <p>@lang('Thống kê')</p>
                    </a>
                </li>
                {{-- End tab transaction --}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
