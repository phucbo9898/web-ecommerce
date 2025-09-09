@extends('backend.layouts.main')

@section('title', 'Danh sách danh mục')
<?php
use App\Enums\PublishEnum;
?>

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3>@lang('Danh sách danh mục')</h3>
            </div>
            @if (isset($categories))
                <div class="card-body">
                    <div class="form-group">
                        <form action="{{ route('admin.category.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-3 mt-3">
                                    <label for="name" class="col-form-label">@lang('Tên danh mục')</label>
                                    <input type="text" autocomplete="off" name="category_name" class="form-control"
                                        value="{{ $categoryName ?? '' }}">
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="entertainment" class="col-form-label">@lang('Thuộc tính')</label> <br>
                                    <select name="attribute_id" class="form-control">
                                        <option value="">@lang('Chọn thuộc tính')</option>
                                        @foreach ($attributes as $attribute)
                                            <option value="{{ $attribute->id ?? $options['attribute'] }}"
                                                {{ !empty($attributeId) && $attribute->id == $attributeId ? 'selected' : '' }}>
                                                @lang($attribute->name)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-3">
                                    <label class="col-form-label mr-3">@lang('Trạng thái')</label>
                                    @foreach (PublishEnum::getValues() as $status)
                                        <span class="mr-2">
                                            <input id="{{ $status }}"
                                                {{ !empty($categoryStatus) && $categoryStatus == $status ? 'checked' : '' }}
                                                type="radio" name="status" value="{{ $status }}">
                                            <label for="">@lang(PublishEnum::getStatusName($status))</label>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <button class="btn btn-info min-w-100" type="submit">@lang('Tìm kiếm')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                            <th>UUID</th>
                            <th>Tên</th>
                            <th>Trạng thái</th>
                            <th>Thuộc tính</th>
                            <th>Hành động</th>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->uuid }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        <form action="{{ route('admin.category.handle', ['status', $category->id]) }}"
                                            method="post">
                                            @csrf
                                            <input type="hidden" name="status"
                                                value="{{ $category->status == 1 ? 2 : 1 }}">
                                            <button type="submit"
                                                class="badge badge-{{ $category->status == PublishEnum::PUBLISHED ? 'success' : 'danger' }}">
                                                {{ $category->status == PublishEnum::PUBLISHED ? __('Đã xuất bản') : __('Không công bố') }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <ul>
                                            @foreach ($category->categoryAttribute ?? '' as $attribute)
                                                <li>{{ $attribute->name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="d-flex">
                                        <a href="{{ route('admin.category.edit', $category->id) }}"
                                            class="btn btn-primary btn-circle"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.category.handle', ['delete', $category->id]) }}"
                                            method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-circle">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "order": [
                    [0, "desc"]
                ],
                // "language": {
                //     "decimal": "",
                //     "emptyTable": "Không có dữ liệu hiển thị trong bảng",
                //     "info": "Đang hiển thị bản ghi _START_ đến _END_ trên _TOTAL_ bản ghi",
                //     "infoEmpty": "Hiển thị 0 đến 0 của 0 bản ghi",
                //     "infoFiltered": "(đã lọc từ _MAX_ bản ghi)",
                //     "infoPostFix": "",
                //     "thousands": ",",
                //     "lengthMenu": "Hiển thị _MENU_ bản ghi",
                //     "loadingRecords": "Đang tải...",
                //     "processing": "Đang xử lý...",
                //     "search": "Tìm kiếm:",
                //     "zeroRecords": "Không có bản ghi nào được tìm thấy",
                //     "paginate": {
                //         "first": "Đầu",
                //         "last": "Cuối",
                //         "next": "Tiếp",
                //         "previous": "Trước"
                //     },
                //     "aria": {
                //         "sortAscending": ": activate to sort column ascending",
                //         "sortDescending": ": activate to sort column descending"
                //     }
                // }
            });
        });
    </script>
    {{-- <script>
        $(".btn_delete_sweet").click(function (e) {
            e.preventDefault();
            url = $(this).attr('href');
            id = $(this).attr('data-id');
            swal({
                title: "Bạn có chắc chắn?",
                text: "Bạn có chắc chắn muốn xóa loại sản phẩm ID=" + id +
                    " không ? Điều này sẽ ảnh hưởng đến liên kết dữ liệu ! Bạn có thể chuyển trạng thái sang private để ngừng hiển thị sản phẩm ở giao diện khách hàng !! Xin cảm ơn !!!",
                icon: "info",
                buttons: ["Không", "Có"],
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("Thành công", "Hệ thống chuẩn bị xóa loại sản phẩm mang ID =" + id + " !",
                            'success').then(function () {
                            window.location.href = url;
                        });
                    }
                });
        });
    </script> --}}
@endsection
