@extends('backend.layouts.main')

@section('title', 'Danh sách sản phẩm')
@php use App\Enums\ActiveStatus; @endphp
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">Danh sách sản phẩm</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Sản phẩm</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0 flex-grow-1 mr-2">
                        <i class="fas fa-box text-primary mr-2"></i>@lang('Danh sách sản phẩm')
                        @isset($products)
                            <span class="badge badge-light border ml-2">{{ $products->total() }} sản phẩm</span>
                        @endisset
                    </h3>
                     <a href="{{ route('admin.product.create') }}" class="btn btn-success btn-sm flex-shrink-0"
                        style="color:white !important;">
                        <i class="fas fa-plus mr-1"></i>@lang('Thêm mới sản phẩm')
                    </a>
                </div>

                <div class="card-body">
                    {{-- Bộ lọc tìm kiếm --}}
                    <div class="bg-light rounded p-3 mb-4">
                        <form action="{{ url()->full() }}" method="GET">
                            <div class="row">
                                <div class="col-md-3 mt-2">
                                    <label for="name" class="col-form-label font-weight-bold">
                                        <i class="fas fa-tag text-muted mr-1"></i>@lang('Tên sản phẩm')
                                    </label>
                                    <input type="text" id="name" autocomplete="off" name="name" class="form-control"
                                        placeholder="Nhập tên sản phẩm..." value="{{ $options['name'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mt-2">
                                    <label for="category_id" class="col-form-label font-weight-bold">
                                        <i class="fas fa-sitemap text-muted mr-1"></i>@lang('Tên danh mục')
                                    </label>
                                    <select id="category_id" name="category_id" class="form-control select2-blue">
                                        <option value="">@lang('Chọn danh mục')</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id ?? $options['category_id'] }}"
                                                {{ isset($options['category_id']) && $category->id == $options['category_id'] ? 'selected' : '' }}>
                                                @lang($category->name)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mt-2">
                                    <label for="filter_price" class="col-form-label font-weight-bold">
                                        <i class="fas fa-sort-amount-down text-muted mr-1"></i>@lang('Sắp xếp theo giá')
                                    </label>
                                    <select id="filter_price" name="filter_price" class="form-control">
                                        <option value="">Chọn loại sắp xếp</option>
                                        <option value="asc" {{ ($options['filter_price'] ?? '') == 'asc' ? 'selected' : '' }}>
                                            Sắp xếp tăng dần</option>
                                        <option value="desc" {{ ($options['filter_price'] ?? '') == 'desc' ? 'selected' : '' }}>
                                            Sắp xếp giảm dần</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mt-2">
                                    <label for="filter_sold" class="col-form-label font-weight-bold">
                                        <i class="fas fa-sort-amount-down text-muted mr-1"></i>@lang('Sắp xếp theo số lượng đã bán')
                                    </label>
                                    <select id="filter_sold" name="filter_sold" class="form-control">
                                        <option value="">Chọn loại sắp xếp</option>
                                        <option value="asc" {{ ($options['filter_sold'] ?? '') == 'asc' ? 'selected' : '' }}>
                                            Sắp xếp tăng dần</option>
                                        <option value="desc" {{ ($options['filter_sold'] ?? '') == 'desc' ? 'selected' : '' }}>
                                            Sắp xếp giảm dần</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <label class="font-weight-bold d-block">
                                        <i class="fas fa-toggle-on text-muted mr-1"></i>@lang('Trạng thái')
                                    </label>
                                    @foreach (ActiveStatus::getValues() as $status)
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input id="status_{{ $status }}" class="custom-control-input"
                                                {{ isset($options['publish']) && in_array($status, $options['publish']) ? 'checked' : '' }}
                                                type="checkbox" name="publish[]" value="{{ $status }}">
                                            <label class="custom-control-label"
                                                for="status_{{ $status }}">{{ __(ActiveStatus::getStatusName($status)) }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mt-3 text-right">
                                    <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo mr-1"></i>@lang('Đặt lại')
                                    </a>
                                    <button class="btn btn-info" type="submit">
                                        <i class="fas fa-search mr-1"></i>@lang('Tìm kiếm')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Bảng dữ liệu --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 80px;">Ảnh</th>
                                    <th>Sản phẩm</th>
                                    <th style="width: 140px;">UUID</th>
                                    <th style="width: 14%;">Loại sản phẩm</th>
                                    <th class="text-center" style="width: 110px;">Trạng thái</th>
                                    <th class="text-center" style="width: 90px;">Nổi bật</th>
                                    <th class="text-center" style="width: 100px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products ?? [] as $product)
                                    <tr>
                                        <td>
                                            @if ($product->image)
                                                <img class="rounded shadow-sm" style="width:64px;height:64px;object-fit:cover;"
                                                    src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                                            @else
                                                <img class="rounded shadow-sm" style="width:64px;height:64px;object-fit:cover;"
                                                    src="{{ asset('noimg.png') }}" alt="No Avatar">
                                            @endif
                                        </td>
                                        <td>
                                            <b class="d-block mb-1">{{ $product->name }}</b>
                                            <span class="badge badge-light border mr-1 mb-1">
                                                <i class="fas fa-warehouse mr-1 text-muted"></i>Tồn: {{ $product->quantity > 0 ? $product->quantity : 0 }}
                                            </span>
                                            <span class="badge badge-primary badge-pill mr-1 mb-1">
                                                {{ number_format($product->price, 0, ',', '.') }} đ
                                            </span>
                                            @if ($product->sale)
                                                <span class="badge badge-danger badge-pill mr-1 mb-1">
                                                    <i class="fas fa-percent mr-1"></i>-{{ $product->sale }}%
                                                </span>
                                            @endif
                                            <span class="badge badge-success badge-pill mr-1 mb-1">
                                                <i class="fas fa-shopping-cart mr-1"></i>Đã bán: {{ $product->qty_pay > 0 ? $product->qty_pay : 0 }}
                                            </span>
                                        </td>
                                        <td><code class="small">{{ $product->uuid }}</code></td>
                                        <td>{{ $product->category->name ?? '—' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.product.handle', ['status', $product->id]) }}"
                                                class="badge badge-pill badge-{{ $product->publish == 1 ? 'success' : 'danger' }} px-3 py-2"
                                                title="Nhấn để đổi trạng thái">
                                                {{ $product->publish == 1 ? 'Công khai' : 'Riêng tư' }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.product.handle', ['hot', $product->id]) }}"
                                                class="badge badge-pill badge-{{ $product->hot == 1 ? 'success' : 'secondary' }} px-3 py-2"
                                                title="Nhấn để đổi trạng thái nổi bật">
                                                {{ $product->hot == 1 ? 'Có' : 'Không' }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('admin.product.edit', $product->id) }}"
                                                    class="btn btn-sm btn-outline-primary btn-circle mr-1" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.product.handle', ['delete', $product->id]) }}"
                                                    method="post">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger btn-circle btn_delete_sweet"
                                                        data-id="{{ $product->id }}"
                                                        data-name="{{ $product->name }}" title="Xóa">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                            Không tìm thấy sản phẩm nào phù hợp.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @isset($products)
                    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap bg-white">
                        <div class="text-muted small">
                            Hiển thị {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}
                            trên tổng số {{ $products->total() }} sản phẩm
                        </div>
                        <div>
                            {{ $products->links() }}
                        </div>
                    </div>
                @endisset
            </div>
        </div>
    </section>
@endsection

@section('css')
    <style>
        .rating .active {
            color: #ff9705 !important;
        }

        .card {
            border-radius: 0.5rem;
        }

        .btn-circle {
            width: 34px;
            height: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.04);
        }

        .badge-pill {
            font-weight: 500;
        }
    </style>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            $('[title]').tooltip();

            // Xác nhận xóa sản phẩm bằng SweetAlert trước khi submit form (POST)
            $(document).on('click', '.btn_delete_sweet', function(e) {
                e.preventDefault();
                var $form = $(this).closest('form');
                var name = $(this).data('name');

                swal({
                        title: "Bạn có chắc chắn?",
                        text: "Bạn có chắc chắn muốn xóa sản phẩm \"" + name +
                            "\" không? Điều này sẽ ảnh hưởng đến liên kết dữ liệu! Bạn có thể chuyển trạng thái sang riêng tư để ngừng hiển thị sản phẩm ở giao diện khách hàng. Xin cảm ơn!",
                        icon: "info",
                        buttons: ["Không", "Có"],
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $form.off('submit').submit();
                        }
                    });
            });
        });
    </script>
@endsection
