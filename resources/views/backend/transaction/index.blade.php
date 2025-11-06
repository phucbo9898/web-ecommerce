@extends('cms.layout.master')

@section('title', 'Danh sách giao dịch')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3>Danh sách giao dịch</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="">
                        <form action="{{ url()->full() }}" method="GET">
                            <div class="row">
                                <div class="col-md-3 mt-3">
                                    <label for="name" class="col-form-label">@lang('Customer Name')</label>
                                    <input type="text" autocomplete="off" name="name" class="form-control"
                                        value="{{ $options['name'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="name" class="col-form-label">@lang('Trading code')</label>
                                    <input type="text" autocomplete="off" name="code" class="form-control"
                                        value="{{ $options['code'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="entertainment" class="col-form-label">@lang('Status')</label> <br>
                                    <select name="status" class="form-control">
                                        <option value="">@lang('Choose status')</option>
                                        @foreach (StatusTransaction::getValues() as $status)
                                            <option value="{{ $status }}"
                                                @if (isset($options['status']) && $status == $options['status']) {{ 'selected' }} @endif>
                                                @lang($status)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-3">
                                    <label for="entertainment" class="col-form-label">@lang('Trạng thái thanh toán')</label> <br>
                                    <select name="status_payment" class="form-control">
                                        <option value="">@lang('Choose Status Payment')</option>
                                        <option value="Paуment received"
                                            {{ ($options['status_payment'] ?? '') == 'Paуment received' ? 'selected' : '' }}>
                                            Đã thanh toán</option>
                                        <option value="Paуment not received"
                                            {{ ($options['status_payment'] ?? '') == 'Paуment not received' ? 'selected' : '' }}>
                                            Chưa thanh toán</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="entertainment" class="col-form-label">@lang('Loại thanh toán')</label> <br>
                                    <select name="type_payment" class="form-control">
                                        <option value="">@lang('Choose Payment method')</option>
                                        <option value="momo"
                                            {{ ($options['type_payment'] ?? '') == 'momo' ? 'selected' : '' }}>Thanh toán
                                            qua Momo</option>
                                        <option value="vnpay"
                                            {{ ($options['type_payment'] ?? '') == 'vnpay' ? 'selected' : '' }}>Thanh toán
                                            qua VNPay</option>
                                        <option value="normal"
                                            {{ ($options['type_payment'] ?? '') == 'normal' ? 'selected' : '' }}>Thanh toán
                                            khi nhận hàng</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <button class="btn btn-info min-w-100" type="submit">@lang('Search')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table table-hover table-striped" id="dataTable"
                    style="overflow-y: scroll; white-space: nowrap; font-size: 16px !important;">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">MGD</th>
                            <th scope="col">Người mua</th>
                            <th scope="col">Trạng thái thanh toán</th>
                            <th scope="col">Loại thanh toán</th>
                            <th scope="col">Tổng tiền</th>
                            <th scope="col">Trạng thái đơn hàng</th>
                            <th scope="col">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td class="col-md-1">{{ $transaction->payment_code ?? '' }}</td>
                                <td class="col-md-2">{{ $transaction->customer_name ?? '' }}</td>
                                <td class="col-md-2">
                                    @if ($transaction->status_payment == 'Paуment not received')
                                        <a href="{{ route('admin.transaction.handle', ['change-status', $transaction->id]) }}"
                                            class="badge badge-warning"
                                            style="font-size: 14px;width: 113.11px; color: black">
                                            {{ $transaction->status_payment == 'Paуment not received' ? 'Chưa thanh toán' : 'Đã thanh toán' }}
                                        </a>
                                    @else
                                        <span class="badge badge-success"
                                            style="font-size: 14px;width: 113.11px;">{{ $transaction->status_payment == 'Paуment received' ? 'Đã thanh toán' : 'Chưa thanh toán' }}</span>
                                    @endif
                                </td>
                                <td class="col-md-2" class="get-payment">
                                    @if ($transaction->type_payment == 'momo')
                                        <span class="momo" style="font-size: 16px;">Momo</span>
                                    @elseif($transaction->type_payment == 'vnpay')
                                        <span class="banking" style="font-size: 16px;">VNPay</span>
                                    @else
                                        <span class="payment-normal" style="font-size: 16px;">Thông thường</span>
                                    @endif
                                </td>
                                <td class="col-md-1">{{ number_format($transaction->total, 0, ',', '.') }} VNĐ</td>
                                <td class="col-md-2" style="text-align: center;">
                                    @if ($transaction->status == 'completed')
                                        <a href="#"><span class="badge badge-success"
                                                style="font-size: 14px; width: 95.96px;">Đã nhận hàng</span></a>
                                    @endif
                                    @if ($transaction->status == 'processing')
                                        <a href="{{ route('admin.transaction.paid', $transaction->id) }}"><span
                                                class="badge badge-secondary text-white"
                                                style="font-size: 14px; width: 95.96px;">Đã gửi hàng</span></a>
                                    @endif
                                    @if ($transaction->status == 'pending')
                                        <a href="{{ route('admin.transaction.handle', ['send', $transaction->id]) }}"><span
                                                class="badge badge-warning" style="font-size: 14px; width: 95.96px;">Đang xử
                                                lý</span></a>
                                    @endif
                                    @if ($transaction->status == 'canceled')
                                        <span class="badge badge-danger" style="font-size: 14px; width: 95.96px;">Đã
                                            hủy</span>
                                    @endif
                                </td>
                                <td class="col-md-1">
                                    <a href="{{ route('admin.get.order.item', $transaction->id) }}"
                                        data-id="{{ $transaction->id }}" class="js_order_item btn btn-primary btn-circle"
                                        data-toggle="modal" data-target="#showOrderItem">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if ($transaction->status == 'pending' || $transaction->status == 'processing')
                                        <a href="{{ route('admin.transaction.handle', ['cancel', $transaction->id]) }}"
                                            data-id="{{ $transaction->id }}"
                                            class="btn_delete_sweet btn btn-danger btn-circle">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('admin.transaction.handle', ['delete', $transaction->id]) }}"
                                            data-id="{{ $transaction->id }}"
                                            class="btn_delete_sweet btn btn-danger btn-circle"
                                            style="visibility: hidden;">
                                            <i class="fas fa-window-close"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        {{-- custom modal by me --}}
        <div class="modal fade" id="showOrderItem" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">
                            Chi tiết đơn hàng #id
                            <span class="modal_id_transaction"></span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Chưa có dữ liệu hoặc bị lỗi !!!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- end custom modal by me --}}
    </section>
@endsection
@section('javascript')
    <script>
        $(function() {
            $(".js_order_item").click(function(event) {
                event.preventDefault();
                var id = $(this).attr('data-id');
                var url = $(this).attr('href');
                $.ajax({
                    method: "GET",
                    url: url
                }).done(function(result) {
                    if (result) {
                        $(".modal_id_transaction").text(id);
                        $(".modal-body").html('').append(result);

                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                scrollX: true,
                "order": [
                    [0, "desc"]
                ],
                "language": {
                    "decimal": "",
                    "emptyTable": "Không có dữ liệu hiển thị trong bảng",
                    "info": "Đang hiển thị bản ghi _START_ đến _END_ trên _TOTAL_ bản ghi",
                    "infoEmpty": "Hiển thị 0 đến 0 của 0 bản ghi",
                    "infoFiltered": "(đã lọc từ _MAX_ total bản ghi)",
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
    <script>
        $(".btn_delete_sweet").click(function(e) {
            e.preventDefault();
            url = $(this).attr('href');
            id = $(this).attr('data-id');
            swal({
                    title: "Bạn có chắc chắn?",
                    text: "Bạn có chắc chắn muốn hủy giao dịch ID=" + id +
                        " không ? Điều này sẽ ảnh hưởng đến liên kết dữ liệu !",
                    icon: "info",
                    buttons: ["Không", "Có"],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("Thành công", "Hệ thống chuẩn bị hủy giao dịch mang ID =" + id + " !", 'success')
                            .then(
                                function() {
                                    window.location.href = url;
                                });
                    }
                });
        });
    </script>
@endsection
