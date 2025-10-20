@extends('backend.layouts.main')

@section('title', 'Danh sách thuộc tính')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3>Danh sách thuộc tính</h3>
            </div>
            @if (isset($attributes))
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                            <th>UUID</th>
                            <th>Tên</th>
                            <th>Loại</th>
                            <th>Giá trị</th>
                            <th>Hành động</th>
                        </thead>
                        <tbody>
                            @foreach ($attributes as $attribute)
                                <tr>
                                    <td>{{ $attribute->uuid }}</td>
                                    <td>{{ $attribute->name }}</td>
                                    <td>{{ $attribute->type }}</td>
                                    <td>
                                        @if ($attribute->value)
                                            <ul>
                                                @foreach (explode(';', $attribute->value) as $attr)
                                                    <li>{{ $attr }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td class="d-flex">
                                        <a href="{{ route('admin.attribute.edit', $attribute->id) }}"
                                            class="btn btn-success btn-circle"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.attribute.handle', ['delete', $attribute->id]) }}"
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
                <div class="d-flex justify-content-center">
                {{ $attributes->links() }}
            </div>
            @endif
        </div>
    </section>
@endsection
@section('javascript')
    <script>
        $(".btn_delete_sweet").click(function(e) {
            e.preventDefault();
            url = $(this).attr('href');
            id = $(this).attr('data-id');
            swal({
                    title: "Bạn có chắc chắn?",
                    text: "Bạn có chắc chắn muốn xóa thuộc tính ID=" + id + " không ? Xin cảm ơn !!!",
                    icon: "info",
                    buttons: ["Không", "Có"],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("Thành công", "Hệ thống chuẩn bị xóa thuộc tính mang ID =" + id + " !", 'success')
                            .then(function() {
                                window.location.href = url;
                            });
                    }
                });
        });
    </script>
@endsection
