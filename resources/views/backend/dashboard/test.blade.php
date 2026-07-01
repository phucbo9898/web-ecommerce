@extends('backend.layouts.main')

@section('title', 'Trang quản trị')

@section('css')
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('admin_lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('admin_lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_lte/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('admin_lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('admin_lte/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('admin_lte/plugins/summernote/summernote-bs4.css') }}">

    <style>
        .content .container-fluid {
            padding-top: 4px;
        }

        .section-title {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #9a9ca3;
            font-weight: 700;
            margin: 26px 0 12px;
        }

        .row+.section-title {
            margin-top: 30px;
        }

        /* ===== info-box chính (hero) ===== */
        .info-box {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
            background: #fff;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .info-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .10);
        }

        .info-box-icon {
            border-radius: 10px 0 0 10px;
        }

        .info-box-number {
            font-weight: 700;
            font-size: 21px;
        }

        .progress-description {
            font-size: 12.5px;
        }

        /* ===== card chung ===== */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            border-radius: 10px 10px 0 0 !important;
        }

        .card-header h6 {
            font-size: 14.5px;
            letter-spacing: .2px;
        }

        /* ===== bảng dữ liệu ===== */
        .table thead th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #8b8d94;
            border-top: none;
            border-bottom: 1px solid #f0f0f0;
        }

        .table td {
            font-size: 13.5px;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fc;
        }

        .badge {
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 30px;
            font-size: 11.5px;
        }

        /* ===== order status pills ===== */
        .order-status-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
        }

        .order-status-item {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
            padding: 16px;
            text-align: center;
            border-top: 3px solid var(--status-color, #ccc);
        }

        .order-status-item .status-count {
            font-size: 24px;
            font-weight: 700;
            color: #2c2c2c;
        }

        .order-status-item .status-label {
            font-size: 12.5px;
            color: #888;
            margin-top: 2px;
        }

        .order-status-item .status-icon {
            font-size: 18px;
            margin-bottom: 6px;
        }

        /* ===== top sản phẩm bán chạy ===== */
        .rank-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #f0f1f5;
            color: #888;
            font-size: 12px;
            font-weight: 700;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .rank-badge.top1 {
            background: #fff3cd;
            color: #c79100;
        }

        .rank-badge.top2 {
            background: #e9ecef;
            color: #6c757d;
        }

        .rank-badge.top3 {
            background: #f8d7da;
            color: #c2496a;
        }

        .product-thumb {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            background: #f0f1f5;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #b3b5bd;
            font-size: 14px;
            margin-right: 10px;
            flex-shrink: 0;
            object-fit: cover;
        }

        /* ===== tổng quan hệ thống: 4 khối riêng biệt (hạ cấp, gọn) ===== */
        .mini-overview-item {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            text-decoration: none;
            color: inherit;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .mini-overview-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .10);
            color: inherit;
        }

        .mini-overview-item .icon-wrap {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .mini-overview-item .mini-num {
            font-size: 17px;
            font-weight: 700;
            line-height: 1.1;
        }

        .mini-overview-item .mini-label {
            font-size: 12px;
            color: #9a9ca3;
        }

        @media (max-width: 992px) {
            .order-status-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* ===== list-group items gọn hơn ===== */
        .list-group-item {
            font-size: 13.5px;
            border-color: #f0f0f0;
            padding: .65rem 1.25rem;
        }

        .list-group-item:hover {
            background: #f8f9fc;
        }

        .text-muted-light {
            color: #aaa;
        }
    </style>
@endsection
@section('content')
    <span class="chart_seven_days" data-chart="{{ $chart['total_price_seven_days_edit'] ?? '' }}"></span>
    <span class="chart_time_seven_days" data-chart-time="{{ $chart['time_chart'] ?? '' }}"></span>
    <!-- Main content -->
    <section class="content mt-3">
        <div class="container-fluid">

            {{-- ===== HÀNG 1: SỐ LIỆU QUAN TRỌNG NHẤT — CẦN HÀNH ĐỘNG NGAY ===== --}}
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary"><i class="fas fa-coins"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">@lang('Doanh thu hôm nay')</span>
                            <span class="info-box-number">{{ number_format($todayRevenue ?? 0) }} ₫</span>
                            @if ($revenueDeltaPercent > 0)
                                <span class="progress-description text-success">
                                    <i class="fas fa-arrow-up"></i> {{ $revenueDeltaPercent ?? 0 }}% @lang('so với hôm qua')
                                </span>
                            @else
                                <span class="progress-description text-danger">
                                    <i class="fas fa-arrow-down"></i> {{ $revenueDeltaPercent ?? 0 }}% @lang('so với hôm qua')
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <a href="#" class="info-box" style="color:inherit;">
                        <span class="info-box-icon bg-info"><i class="ion ion-bag"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">@lang('Đơn cần xử lí')</span>
                            <span class="info-box-number">{{ $transaction ?? 0 }}</span>
                            <span class="progress-description text-muted">@lang('Chờ xác nhận + đang xử lí')</span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6">
                    <a href="{{ route('admin.product.index') }}" class="info-box" style="color:inherit;">
                        <span class="info-box-icon bg-danger"><i class="fas fa-box-open"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">@lang('Sản phẩm sắp hết hàng')</span>
                            <span class="info-box-number">{{ $lowStockCount ?? 0 }}</span>
                            <span class="progress-description text-muted">@lang('Cần nhập thêm hàng')</span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-star"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">@lang('Đánh giá mới')</span>
                            <span class="info-box-number">{{ $newReviews ?? 0 }}</span>
                            <span class="progress-description text-muted">@lang('Trong 7 ngày qua')</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            {{-- ===== HÀNG 2: CHI TIẾT TRẠNG THÁI ĐƠN HÀNG ===== --}}
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="order-status-grid">
                        <div class="order-status-item" style="--status-color:#f1b40f;">
                            <div class="status-icon text-warning"><i class="fas fa-hourglass-half"></i></div>
                            <div class="status-count">{{ $orderStatus['pending'] ?? 0 }}</div>
                            <div class="status-label">@lang('Chờ xác nhận')</div>
                        </div>
                        <div class="order-status-item" style="--status-color:#17a2b8;">
                            <div class="status-icon text-info"><i class="fas fa-box"></i></div>
                            <div class="status-count">{{ $orderStatus['processing'] ?? 0 }}</div>
                            <div class="status-label">@lang('Đang xử lí')</div>
                        </div>
                        <div class="order-status-item" style="--status-color:#6f42c1;">
                            <div class="status-icon" style="color:#6f42c1;"><i class="fas fa-truck"></i></div>
                            <div class="status-count">{{ $orderStatus['shipping'] ?? 0 }}</div>
                            <div class="status-label">@lang('Đang giao')</div>
                        </div>
                        <div class="order-status-item" style="--status-color:#28a745;">
                            <div class="status-icon text-success"><i class="fas fa-circle-check"></i></div>
                            <div class="status-count">{{ $orderStatus['completed'] ?? 0 }}</div>
                            <div class="status-label">@lang('Hoàn tất')</div>
                        </div>
                        <div class="order-status-item" style="--status-color:#dc3545;">
                            <div class="status-icon text-danger"><i class="fas fa-circle-xmark"></i></div>
                            <div class="status-count">{{ $orderStatus['cancelled'] ?? 0 }}</div>
                            <div class="status-label">@lang('Đã huỷ / Trả hàng')</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            {{-- ===== HÀNG 3: BIỂU ĐỒ DOANH SỐ + TỶ TRỌNG TRẠNG THÁI ĐƠN ===== --}}
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">@lang('7 ngày bán hàng gần đây')</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="myAreaChart" style="height: 300px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">@lang('Tỷ trọng trạng thái đơn hàng')</h6>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <canvas id="orderStatusChart" style="height: 260px; max-width: 260px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            {{-- ===== HÀNG 4 (MỚI): SẢN PHẨM BÁN CHẠY TRONG THÁNG ===== --}}
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">@lang('Sản phẩm bán chạy trong tháng')</h6>
                            <a href="{{ route('admin.product.index') }}" class="small text-muted">@lang('Xem tất cả')</a>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:46%;">@lang('Sản phẩm')</th>
                                        <th class="text-right">@lang('Đã bán')</th>
                                        <th class="text-right">@lang('Tồn kho')</th>
                                        <th class="text-right">@lang('Doanh thu')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topSellingProducts ?? [] as $index => $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span
                                                        class="rank-badge {{ $index === 0 ? 'top1' : ($index === 1 ? 'top2' : ($index === 2 ? 'top3' : '')) }}">
                                                        {{ $index + 1 }}
                                                    </span>
                                                    @if (!empty($product->image))
                                                        <img src="{{ asset($product->image) }}" class="product-thumb"
                                                            alt="">
                                                    @else
                                                        <span class="product-thumb"><i class="fas fa-image"></i></span>
                                                    @endif
                                                    <span>{{ $product->name }}</span>
                                                </div>
                                            </td>
                                            <td class="text-right">{{ $product->sold_quantity }}</td>
                                            <td class="text-right {{ $product->checkQuantity($product->quantity) }}">
                                                {{ $product->quantity }}
                                            </td>
                                            <td class="text-right">{{ number_format($product->revenue) }} ₫</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted-light py-4">
                                                @lang('Chưa có dữ liệu')</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            {{-- ===== HÀNG 5: ĐƠN HÀNG GẦN ĐÂY + KHO SẮP HẾT ===== --}}
            <div class="row">
                <div class="col-lg-7">
                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">@lang('Đơn hàng gần đây')</h6>
                            <a href="#" class="small text-muted">@lang('Xem tất cả')</a>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>@lang('Mã đơn')</th>
                                        <th>@lang('Khách hàng')</th>
                                        <th class="text-right">@lang('Giá trị')</th>
                                        <th class="text-right">@lang('Trạng thái')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentOrders ?? [] as $order)
                                        <tr>
                                            <td>#{{ $order->code }}</td>
                                            <td>{{ $order->customer_name }}</td>
                                            <td class="text-right">{{ number_format($order->total) }} ₫</td>
                                            <td class="text-right">
                                                <span
                                                    class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted-light py-4">
                                                @lang('Chưa có dữ liệu')</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">@lang('Kho hàng sắp hết')</h6>
                            <a href="{{ route('admin.product.index') }}" class="small text-muted">@lang('Xem tất cả')</a>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <tbody>
                                    @forelse($lowStockProducts ?? [] as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td
                                                class="text-right {{ $product->stock <= 5 ? 'text-danger' : 'text-warning' }}">
                                                {{ $product->stock }} @lang('còn lại')
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted-light py-4">
                                                @lang('Chưa có dữ liệu')</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            {{-- ===== HÀNG 6 (HẠ CẤP): TỔNG QUAN HỆ THỐNG ===== --}}
            <p class="section-title">@lang('Tổng quan hệ thống')</p>
            <div class="row">
                <div class="col-lg-3 col-6 mb-4">
                    <a href="{{ route('admin.product.index') }}" class="mini-overview-item">
                        <span class="icon-wrap" style="background:#e6f7ee;color:#1d9e75;"><i
                                class="ion ion-monitor"></i></span>
                        <div>
                            <div class="mini-num">{{ $products ?? 0 }}</div>
                            <div class="mini-label">@lang('Sản phẩm')</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6 mb-4">
                    <a href="{{ route('admin.user.index') }}" class="mini-overview-item">
                        <span class="icon-wrap" style="background:#fff3cd;color:#c79100;"><i
                                class="ion ion-person-stalker"></i></span>
                        <div>
                            <div class="mini-num">{{ $users ?? 0 }}</div>
                            <div class="mini-label">@lang('Thành viên')</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6 mb-4">
                    <a href="#" class="mini-overview-item">
                        <span class="icon-wrap" style="background:#fde2e4;color:#c2496a;"><i
                                class="ion ion-ios-paper-outline"></i></span>
                        <div>
                            <div class="mini-num">{{ $articles ?? 0 }}</div>
                            <div class="mini-label">@lang('Tin tức')</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6 mb-4">
                    <div class="mini-overview-item">
                        <span class="icon-wrap" style="background:#e6f1fb;color:#185fa5;"><i
                                class="fas fa-ticket-alt"></i></span>
                        <div>
                            <div class="mini-num">{{ $activeCoupons ?? 0 }}</div>
                            <div class="mini-label">
                                @lang('Phiếu giảm giá đang chạy')
                                @if (($expiringCoupons ?? 0) > 0)
                                    <span class="text-danger">· {{ $expiringCoupons }} @lang('sắp hết hạn')</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            {{-- ===== HÀNG 7: BÀI VIẾT / ĐÁNH GIÁ / PHIẾU GIẢM GIÁ CHI TIẾT ===== --}}
            <div class="row">
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">@lang('Bài viết gần đây')</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            @forelse($recentPosts ?? [] as $post)
                                <li class="list-group-item">
                                    <i class="far fa-file-alt text-muted mr-1"></i> {{ $post->title }}
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted-light">@lang('Chưa có dữ liệu')</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">@lang('Đánh giá sản phẩm gần đây')</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            @forelse($recentReviews ?? [] as $review)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $review->product_name }}</span>
                                    <span class="text-warning">
                                        {!! str_repeat('★', $review->rating) . str_repeat('☆', 5 - $review->rating) !!}
                                    </span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted-light">@lang('Chưa có dữ liệu')</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">@lang('Phiếu giảm giá đang chạy')</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            @forelse($activeCouponList ?? [] as $coupon)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $coupon->code }}</span>
                                    <span>{{ $coupon->display_value }}</span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted-light">@lang('Chưa có dữ liệu')</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('javascript')
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('admin_lte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- ChartJS -->
    <script src="{{ asset('admin_lte/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('admin_lte/plugins/sparklines/sparkline.js') }}"></script>
    <script src="{{ asset('admin_lte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('admin_lte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin_lte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('admin_lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('admin_lte/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('admin_lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    {{-- chart area gốc (biểu đồ doanh số 7 ngày) --}}
    <script src="{{ asset('js/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/Chart.min.js') }}"></script>

    {{-- biểu đồ donut tỷ trọng trạng thái đơn hàng --}}
    @php
        $orderStatusChartData = [
            'pending' => $orderStatus['pending'] ?? 0,
            'processing' => $orderStatus['processing'] ?? 0,
            'completed' => $orderStatus['completed'] ?? 0,
            'cancelled' => $orderStatus['cancelled'] ?? 0,
        ];
    @endphp
    <script>
        $(function() {
            var statusData = @json($orderStatusChartData);

            var ctx = document.getElementById('orderStatusChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Chờ xác nhận', 'Đang xử lí', 'Đang giao', 'Hoàn tất', 'Đã huỷ / Trả hàng'],
                    datasets: [{
                        data: [
                            statusData.pending,
                            statusData.processing,
                            statusData.completed,
                            statusData.cancelled
                        ],
                        backgroundColor: ['#f1b40f', '#17a2b8', '#6f42c1', '#28a745', '#dc3545'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutoutPercentage: 65,
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            fontSize: 11
                        }
                    }
                }
            });
        });
    </script>
@endsection
