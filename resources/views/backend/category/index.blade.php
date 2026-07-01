@extends('backend.layouts.main')

@section('title', 'Danh sách danh mục')
<?php
use App\Enums\PublishEnum;
?>

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">Danh sách danh mục</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Danh mục</li>
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
                        <i class="fas fa-sitemap text-primary mr-2"></i>@lang('Danh sách danh mục')
                        @isset($categories)
                            <span class="badge badge-light border ml-2">{{ $categories->total() }} danh mục</span>
                        @endisset
                    </h3>
                    <a href="{{ route('admin.category.create') }}" class="btn btn-success btn-sm flex-shrink-0" style="color: white !important;">
                        <i class="fas fa-plus mr-1"></i>@lang('Thêm mới danh mục')
                    </a>
                </div>

                @if (isset($categories))
                    <div class="card-body">
                        {{-- Bộ lọc tìm kiếm --}}
                        <div class="bg-light rounded p-3 mb-4">
                            <form action="{{ route('admin.category.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-4 mt-2">
                                        <label for="category_name" class="col-form-label font-weight-bold">
                                            <i class="fas fa-tag text-muted mr-1"></i>@lang('Tên danh mục')
                                        </label>
                                        <input type="text" id="category_name" autocomplete="off" name="category_name"
                                            class="form-control" placeholder="Nhập tên danh mục..."
                                            value="{{ $categoryName ?? '' }}">
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <label for="attribute_id" class="col-form-label font-weight-bold">
                                            <i class="fas fa-list text-muted mr-1"></i>@lang('Thuộc tính')
                                        </label>
                                        <select id="attribute_id" name="attribute_id" class="form-control">
                                            <option value="">@lang('Chọn thuộc tính')</option>
                                            @foreach ($attributes as $attribute)
                                                <option value="{{ $attribute->id ?? $options['attribute'] }}"
                                                    {{ !empty($attributeId) && $attribute->id == $attributeId ? 'selected' : '' }}>
                                                    @lang($attribute->name)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <label class="col-form-label font-weight-bold d-block">
                                            <i class="fas fa-toggle-on text-muted mr-1"></i>@lang('Trạng thái')
                                        </label>
                                        @foreach (PublishEnum::getValues() as $status)
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input id="status_{{ $status }}" class="custom-control-input"
                                                    {{ !empty($categoryStatus) && $categoryStatus == $status ? 'checked' : '' }}
                                                    type="radio" name="status" value="{{ $status }}">
                                                <label class="custom-control-label"
                                                    for="status_{{ $status }}">{{ __(PublishEnum::getStatusName($status)) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mt-3 text-right">
                                        <a href="{{ route('admin.category.index') }}" class="btn btn-outline-secondary">
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
                                        <th style="width: 40px;">#</th>
                                        <th style="width: 140px;">UUID</th>
                                        <th style="width: 20%;">Tên danh mục</th>
                                        <th class="text-center" style="width: 140px;">Trạng thái</th>
                                        <th style="width: 260px;">Thuộc tính</th>
                                        <th class="text-center" style="width: 110px;">Số sản phẩm</th>
                                        <th class="text-center" style="width: 100px;">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $category)
                                        <tr>
                                            <td class="text-muted">{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                                            <td><code class="small">{{ $category->uuid }}</code></td>
                                            <td class="font-weight-bold">{{ $category->name }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('admin.category.handle', ['status', $category->id]) }}"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status"
                                                        value="{{ $category->status == 1 ? 2 : 1 }}">
                                                    <button type="submit"
                                                        class="badge badge-pill badge-{{ $category->status == PublishEnum::PUBLISHED ? 'success' : 'danger' }} px-3 py-2 border-0"
                                                        title="Nhấn để đổi trạng thái">
                                                        <i class="fas {{ $category->status == PublishEnum::PUBLISHED ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                                        {{ $category->status == PublishEnum::PUBLISHED ? __('Đã xuất bản') : __('Không công bố') }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                @php
                                                    $attrs = $category->categoryAttribute ?? [];
                                                    $visibleLimit = 4;
                                                    $hiddenCount = max(count($attrs) - $visibleLimit, 0);
                                                @endphp
                                                @if (count($attrs))
                                                    <div class="attr-badges">
                                                        @foreach ($attrs as $index => $attribute)
                                                            <span
                                                                class="badge badge-light border mr-1 mb-1 attr-item {{ $index >= $visibleLimit ? 'attr-hidden d-none' : '' }}">
                                                                {{ $attribute->name }}
                                                            </span>
                                                        @endforeach
                                                        @if ($hiddenCount > 0)
                                                            <span class="badge badge-info badge-pill attr-toggle"
                                                                role="button" data-hidden-count="{{ $hiddenCount }}">
                                                                +{{ $hiddenCount }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info badge-pill px-3 py-2">
                                                    {{ $category->products_count ?? ($category->products->count() ?? 0) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ route('admin.category.edit', $category->id) }}"
                                                        class="btn btn-sm btn-outline-primary btn-circle mr-1"
                                                        title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.category.handle', ['delete', $category->id]) }}"
                                                        method="post"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục &quot;{{ $category->name }}&quot; không?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger btn-circle"
                                                            title="Xóa">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-5">
                                                <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                                Không tìm thấy danh mục nào phù hợp.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap bg-white">
                        <div class="text-muted small">
                            Hiển thị {{ $categories->firstItem() ?? 0 }}–{{ $categories->lastItem() ?? 0 }}
                            trên tổng số {{ $categories->total() }} danh mục
                        </div>
                        <div>
                            {{ $categories->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('css')
    <style>
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

        .card {
            border-radius: 0.5rem;
        }

        .attr-badges {
            max-width: 260px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }

        .attr-toggle {
            cursor: pointer;
            user-select: none;
        }

        .attr-toggle:hover {
            opacity: 0.85;
        }
    </style>
@endsection

@section('javascript')
    {{-- Ghi chú: bảng đang dùng phân trang phía server (Laravel paginate), nên KHÔNG khởi tạo
         DataTables ở đây để tránh xung đột. Nếu muốn tìm kiếm/sắp xếp phía client, hãy chuyển
         sang lấy toàn bộ dữ liệu và loại bỏ $categories->links(). --}}
    <script>
        $(document).ready(function() {
            // Tooltip cho các nút hành động (yêu cầu bootstrap.js đã include)
            $('[title]').tooltip();

            // Mở rộng / thu gọn danh sách thuộc tính khi có nhiều hơn giới hạn hiển thị
            $(document).on('click', '.attr-toggle', function() {
                var $toggle = $(this);
                var $container = $toggle.closest('.attr-badges');
                var $hiddenItems = $container.find('.attr-hidden');
                var isExpanded = !$hiddenItems.first().hasClass('d-none');

                $hiddenItems.toggleClass('d-none');

                $toggle.text(isExpanded ?
                    '+' + $toggle.data('hidden-count') :
                    'Thu gọn'
                );
            });
        });
    </script>
@endsection
