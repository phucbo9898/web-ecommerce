@extends('backend.layouts.main')

@section('title', 'Danh sách đánh giá sản phẩm')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3>Danh sách đanh giá sản phẩm</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped table-list">
                    <thead class="thead-dark">
                        <th>ID</th>
                        <th>Mã voucher</th>
                        <th>Giảm giá</th>
                        <th>Ngày tạo</th>
                        <th>Ngày hết hạn</th>
                        <th>Hành động</th>
                    </thead>
                    <tbody>
                        @foreach ($vouchers as $voucher)
                            <tr>
                                <td>{{ $voucher->id ?? '' }}</td>
                                <td>{{ $voucher->code ?? '' }}</td>
                                <td>{{ $voucher->sale ?? '' }}%</td>
                                <td>
                                    <input type="hidden" class="convert-time"
                                           value="{{ date('Y-m-d h:i:s A', strtotime($voucher->created_at ?? '')) }}">
                                    {{ $voucher->created_at ?? '' }}
                                </td>
                                <td>
                                    <input type="hidden" class="convert-time"
                                           value="{{ date('Y-m-d h:i:s A', strtotime($voucher->expire_date ?? '')) }}">
                                    {{ $voucher->expire_date ?? '' }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.voucher.edit', $voucher->id) }}"
                                       class="btn btn-success btn-circle"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin.voucher.action', ['delete', $voucher->id]) }}"
                                        class="btn_delete_sweet btn btn-danger btn-circle"
                                        data-id="{{ $voucher->id }}"><i class="fas fa-trash-alt"></i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
@section('javascript')
    <script>
        $('.table-list').find('.convert-time').each(function () {
            var a = moment.tz($(this).val(), Intl.DateTimeFormat().resolvedOptions().timeZone)
            $(this).parent('td').html(a.format('YYYY-MM-DD HH:mm:ss'))
        });
    </script>
@endsection
