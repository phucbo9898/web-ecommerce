@extends('backend.layouts.main')

@section('title', 'Danh sách đánh giá sản phẩm')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3>Danh sách đánh giá sản phẩm</h3>
            </div>
            @if (isset($ratings))
                <div class="card-body">
                    <table class="table table-striped table-list">
                        <thead class="thead-dark">
                            <th style="width: 5%">ID</th>
                            <th style="width: 15%">Người đánh giá</th>
                            <th style="width: 25%">Sản phẩm</th>
                            <th style="width: 30%">Nội dung</th>
                            <th style="width: 7%">Rating</th>
                            <th style="width: 13%">Ngày tạo</th>
                            {{-- <th style="width: 5%">Hành động</th> --}}
                        </thead>
                        <tbody>
                            @foreach ($ratings as $rating)
                                <tr>
                                    <td>{{ $rating->id ?? '' }}</td>
                                    <td>{{ optional($rating->User)->name ?? '' }}</td>
                                    <td>{{ $rating->Product->name ?? '' }}</td>
                                    <td>{{ $rating->content ?? '' }}</td>
                                    <td style="text-align: center; width: 5%;">{{ $rating->number ?? '' }} sao</td>
                                    <td>
                                        <input type="hidden" class="convert-time"
                                               value="{{ date('Y-m-d h:i:s A', strtotime($rating->created_at ?? '')) }}">
                                        {{ $rating->created_at ?? '' }}
                                    </td>
                                    {{-- <td style="text-align: center; width: 10%;"><a
                                            href="{{ route('admin.comment.action', ['delete', $rating->id]) }}"
                                            class="btn_delete_sweet btn btn-danger btn-circle"
                                            data-id="{{ $rating->id }}"><i class="fas fa-trash-alt"></i></a></td> --}}
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
        $('.table-list').find('.convert-time').each(function () {
            var a = moment.tz($(this).val(), Intl.DateTimeFormat().resolvedOptions().timeZone)
            console.log(a)
            $(this).parent('td').html(a.format('YYYY-MM-DD HH:mm:ss'))
        });
    </script>
@endsection
