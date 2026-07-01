@extends('backend.layouts.main')

@section('title', 'Danh sách thuộc tính')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">Danh sách thuộc tính</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Thuộc tính</li>
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
                        <i class="fas fa-list-ul text-primary mr-2"></i>@lang('Danh sách thuộc tính')
                        @isset($attributes)
                            <span class="badge badge-light border ml-2">{{ $attributes->total() }} thuộc tính</span>
                        @endisset
                    </h3>
                    <a href="{{ route('admin.attribute.create') }}" class="btn btn-success btn-sm flex-shrink-0" style="color:white !important;">
                        <i class="fas fa-plus mr-1"></i>@lang('Thêm mới thuộc tính')
                    </a>
                </div>

                @if (isset($attributes))
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 40px;">#</th>
                                        <th style="width: 140px;">UUID</th>
                                        <th style="width: 20%;">Tên</th>
                                        <th class="text-center" style="width: 130px;">Loại</th>
                                        <th style="width: 300px;">Giá trị</th>
                                        <th class="text-center" style="width: 100px;">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($attributes as $attribute)
                                        <tr>
                                            <td class="text-muted">{{ $loop->iteration + ($attributes->currentPage() - 1) * $attributes->perPage() }}</td>
                                            <td><code class="small">{{ $attribute->uuid }}</code></td>
                                            <td class="font-weight-bold">{{ $attribute->name }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-secondary badge-pill px-3 py-2">{{ $attribute->type }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $values = $attribute->value ? array_filter(explode(';', $attribute->value)) : [];
                                                    $visibleLimit = 4;
                                                    $hiddenCount = max(count($values) - $visibleLimit, 0);
                                                @endphp
                                                @if (count($values))
                                                    <div class="attr-badges">
                                                        @foreach ($values as $index => $value)
                                                            <span
                                                                class="badge badge-light border mr-1 mb-1 attr-item {{ $index >= $visibleLimit ? 'attr-hidden d-none' : '' }}">
                                                                {{ trim($value) }}
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
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ route('admin.attribute.edit', $attribute->id) }}"
                                                        class="btn btn-sm btn-outline-primary btn-circle mr-1"
                                                        title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.attribute.handle', ['delete', $attribute->id]) }}"
                                                        method="post" class="btn_delete_sweet_form">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger btn-circle btn_delete_sweet"
                                                            data-id="{{ $attribute->id }}"
                                                            data-url="{{ route('admin.attribute.handle', ['delete', $attribute->id]) }}"
                                                            title="Xóa">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-5">
                                                <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                                Không có thuộc tính nào.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap bg-white">
                        <div class="text-muted small">
                            Hiển thị {{ $attributes->firstItem() ?? 0 }}–{{ $attributes->lastItem() ?? 0 }}
                            trên tổng số {{ $attributes->total() }} thuộc tính
                        </div>
                        <div>
                            {{ $attributes->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('css')
    <style>
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

        .attr-badges {
            max-width: 300px;
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
    <script>
        $(document).ready(function() {
            $('[title]').tooltip();

            // Mở rộng / thu gọn danh sách giá trị khi có nhiều hơn giới hạn hiển thị
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

            // Xác nhận xóa thuộc tính bằng SweetAlert
            $(document).on('click', '.btn_delete_sweet', function(e) {
                e.preventDefault();
                var $form = $(this).closest('form');
                var id = $(this).data('id');
                var url = $(this).data('url');

                swal({
                        title: "Bạn có chắc chắn?",
                        text: "Bạn có chắc chắn muốn xóa thuộc tính ID=" + id + " không ? Xin cảm ơn !!!",
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
