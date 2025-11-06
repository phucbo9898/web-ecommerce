@extends('backend.layouts.main')

@section('title', 'Lịch sử nhập-xuất hàng')

@section('content')
    <style>
        .rating .active {
            color: #ff9705 !important;
        }
    </style>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3>Lịch sử nhập-xuất hàng</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="">
                        <form action="{{ url()->full() }}" method="GET">
                            <div class="row">
                                <div class="col-md-3 mt-3">
                                    <label for="name" class="col-form-label">@lang('Tên sản phầm')</label>
                                    <input type="text" autocomplete="off" name="name" class="form-control"
                                        value="{{ $options['name'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="entertainment" class="col-form-label">@lang('Tên danh mục')</label> <br>
                                    <select name="category_id" class="form-control">
                                        <option value="">@lang('Choose category')</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id ?? $options['category_id'] }}"
                                                @if (isset($options['category_id']) && $category->id == $options['category_id']) {{ 'selected' }} @endif>
                                                @lang($category->name)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="name" class="col-form-label">@lang('Ngày bắt đầu')</label>
                                    <input type="date" autocomplete="off" name="start_time" class="form-control"
                                        value="{{ isset($options['start_time']) ? date('Y-m-d', strtotime($options['start_time'])) : '' }}">
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="name" class="col-form-label">@lang('Ngày kết thúc')</label>
                                    <input type="date" autocomplete="off" name="end_time" class="form-control"
                                        value="{{ isset($options['end_time']) ? date('Y-m-d', strtotime($options['end_time'])) : '' }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <button class="btn btn-info min-w-100" type="submit">@lang('Tìm kiếm')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table table-striped table-list">
                    <thead class="thead-dark">
                        <th style="width: 20px">ID</th>
                        <th style="width:28%">Tên sản phẩm</th>
                        <th style="width: 185px">Loại sản phẩm</th>
                        <th style="width: 125px">Ảnh</th>
                        <th style="width: 150px;">Số lượng hàng nhập-xuất</th>
                        <th style="width: 15%;">Thời gian nhập-xuất</th>
                    </thead>
                    <tbody>
                        @if (isset($productHistory))
                            @foreach ($productHistory as $history)
                                <tr>
                                    <td>{{ $history->id }}</td>
                                    <td>
                                        <b>{{ isset($history->product->name) ? $history->product->name : 'Đã bị xóa' }}</b><br />
                                    </td>
                                    <td>{{ isset($history->product->category->name) ? $history->product->category->name : 'Đã bị xóa' }}
                                    </td>
                                    <td style="text-align: center;">
                                        @if (isset($history->product->image))
                                            <img style="width:80px;height:80px" src="{{ asset('upload/product/image/' . $history->product->image) }}"
                                                alt="No Avatar" />
                                        @else
                                            <img style="width:80px;height:80px" src="{{ asset('noimg.png') }}"
                                                alt="No Avatar" />
                                        @endif
                                    </td>
                                    <td style="text-align: center">
                                        @if (!empty($history->number_import))
                                            <span class="text-green"> + {{ $history->number_import }} sản phẩm</span>
                                        @else
                                            <span class="text-danger"> - {{ $history->number_export }} sản phẩm</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center">
                                        <input type="hidden" class="convert-time"
                                            value="{{ date('Y-m-d h:i:s A', strtotime($history->time_import ?? $history->time_export ?? '')) }}">
                                        {{ $history->time_import ?? $history->time_export ?? '' }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $productHistory->links() }}
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection
@section('javascript')
    <script>
        $('.table-list').find('.convert-time').each(function() {
            var a = moment.tz($(this).val(), Intl.DateTimeFormat().resolvedOptions().timeZone)
            console.log(a)
            $(this).parent('td').html(a.format('YYYY-MM-DD HH:mm:ss'))
        });
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "language": {
                    "decimal": "",
                    "emptyTable": "Không có dữ liệu hiển thị trong bảng",
                    "info": "Đang hiển thị bản ghi _START_ đến _END_ trên _TOTAL_ bản ghi",
                    "infoEmpty": "Hiển thị 0 đến 0 của 0 bản ghi",
                    "infoFiltered": "(đã lọc từ _MAX_ bản ghi)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Hiển thị _MENU_ bản ghi",
                    "loadingRecords": "Đang tải...",
                    "processing": "Đang xử lý...",
                    "search": "Tìm kiếm:",
                    "zeroRecords": "Không có bản ghi nào được tìm thấy",
                    "paginate": {
                        "first": "Đầu",
                        "last": "Cuối",
                        "next": "Tiếp",
                        "previous": "Trước"
                    },
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    }
                }
            });
        });
    </script>
@endsection
