@extends('backend.layouts.main')

@section('title', 'Thêm mới slide')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3>Thêm mới Slide</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.slide.store') }}" method="POST" class="col-md-10 mx-auto form-create" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2 text-right">
                                <label>Ảnh mô tả</label>
                                <span style="color: red;">*</span>
                            </div>
                            <div class="col-md-8">
                                <img id="img_output" class="form-control d-none"
                                     style="width: 220px; height: auto; margin-bottom: 10px;"
                                     src="{{ asset('noimg.png') }}"/>
                                <input type="file" id="img_input" class="form-control col-sm-4" name="image"/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2 text-right">
                                <label>Tên Slide</label>
                                <span style="color: red;">*</span>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                       placeholder="Nhập tên slide...">
                            </div>
                        </div>
                    </div>

                    <div style="padding: 0.5rem!important;"></div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-success" style="float: left; margin-right: 5px;">
                                    Lưu thông tin
                                </button>
                                <button class="btn btn-secondary btn-create-slide-cancel" type="button">Hủy bỏ</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
@section('javascript')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#img_output').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $("#img_input").change(function () {
            $("#img_output").removeClass('d-none');
            readURL(this);
        });
        setTimeout(function () {
            $('.alert-danger').remove();
        }, 3000)
    </script>
@endsection
