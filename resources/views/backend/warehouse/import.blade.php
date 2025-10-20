@extends('backend.layouts.main')

@section('title', 'Nhập hàng')

@section('content')
    <style>
        .rating .active {
            color: #ff9705 !important;
        }
    </style>
    @dump(session()->all())
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3>Nhập hàng</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <th>ID</th>
                        <th style="width:36%">Tên sản phẩm</th>
                        <th>Loại sản phẩm</th>
                        <th>Ảnh</th>
                        <th>Số lượng</th>
                        <th style="width: 10%;">Hành động</th>
                    </thead>
                    <tbody>
                        @if (isset($products))
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->uuid }}</td>
                                    <td>
                                        <b>{{ $product->name }}</b><br />
                                    </td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>
                                        @if ($product->image)
                                            <img style="width:80px;height:80px" src="{{ asset($product->image) }}"
                                                alt="No Avatar" />
                                        @else
                                            <img style="width:80px;height:80px" src="{{ asset('noimg.png') }}"
                                                alt="No Avatar" />
                                        @endif
                                    </td>
                                    <td>
                                        @if ($product->quantity > 0)
                                            {{ $product->quantity }}
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.warehouse.import.product', $product->id) }}"
                                            data-name="{{ $product->name }}"
                                            class="btn_import_product btn btn-success btn-circle">
                                            <i class="fa fa-plus-circle"></i></a>
                                        <a href="{{ route('admin.warehouse.export.product', $product->id) }}"
                                            data-name="{{ $product->name }}"
                                            class="btn_export_product btn btn-danger btn-circle"
                                            style="visibility: {{ $product->quantity > 0 ? '' : 'hidden;' }}">
                                            <i class="fa fa-minus-circle"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->

    {{-- modal import products --}}
    <div class="modal fade" id="modal_import_product" tabindex="-1" role="dialog" aria-labelledby="modal_import_product"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Nhập sản phẩm " <span
                            class="name_product_import"></span> "</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form_import_product">
                        <div class="form-group">
                            Số lượng
                            <input type="number" name="product_number" class="form-control" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary btn_accept_import">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal-import products --}}

    {{-- modal export products --}}
    <div class="modal fade" id="modal_export_product" tabindex="-1" role="dialog" aria-labelledby="modal_export_product"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Xuất sản phẩm " <span
                            class="name_product_export"></span> "</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form_export_product">
                        <div class="form-group">
                            Số lượng
                            <input type="number" name="product_number" class="form-control" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary btn_accept_export">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal-export products --}}

@endsection
@section('javascript')
    <script>
        $(".btn_import_product").click(function(e) {
            e.preventDefault();
            url = $(this).attr('href');
            name = $(this).attr('data-name');
            $(".name_product_import").text(name);
            $(".form_import_product").attr("action", url);
            $("#modal_import_product").modal('show');
        });

        $(".btn_accept_import").click(function(e) {
            e.preventDefault();
            $(".form_import_product").submit();
        });

        $(".btn_export_product").click(function(e) {
            e.preventDefault();
            url = $(this).attr('href');
            name = $(this).attr('data-name');
            $(".name_product_export").text(name);
            $(".form_export_product").attr("action", url);
            $("#modal_export_product").modal('show');
        });

        $(".btn_accept_export").click(function(e) {
            e.preventDefault();
            $(".form_export_product").submit();
        });
    </script>
@endsection
