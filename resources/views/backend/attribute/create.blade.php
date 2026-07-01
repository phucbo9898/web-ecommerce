@extends('backend.layouts.main')

@section('title', 'Thêm mới thuộc tính')
<?php use App\Enums\TypeAttribute; ?>
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">Thêm mới thuộc tính</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.attribute.index') }}">Thuộc tính</a></li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus-circle text-primary mr-2"></i>Thêm mới thuộc tính
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.attribute.store') }}" method="POST"
                        class="col-md-10 mx-auto form-create">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right font-weight-bold">
                                Tên thuộc tính <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" id="name" class="form-control" name="name"
                                    placeholder="Nhập tên thuộc tính..." value="{{ old('name') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="selectForAttribute" class="col-md-2 col-form-label text-md-right font-weight-bold">
                                Loại <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-8">
                                <select class="form-control" name="type" id="selectForAttribute">
                                    <option value="">@lang('Chọn loại thuộc tính')</option>
                                    @foreach (TypeAttribute::getValues() as $type)
                                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                            @lang(TypeAttribute::getTypeAttr($type))
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row {{ old('type') && old('type') == TypeAttribute::SELECT ? '' : 'd-none' }}"
                            id="textAreaForAttribute">
                            <label for="contentTextAreaForAttribute"
                                class="col-md-2 col-form-label text-md-right font-weight-bold">
                                Giá trị <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="5" name="value" id="contentTextAreaForAttribute"
                                    placeholder="Các giá trị ngăn cách nhau bởi dấu (;)">{{ old('value') }}</textarea>
                                <small class="form-text text-muted">Ví dụ: S;M;L;XL</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <button type="submit"
                                    class="btn btn-success btn_save_attribute px-4 d-inline-flex align-items-center justify-content-center"
                                    style="white-space: nowrap;">
                                    <i class="fas fa-save mr-1"></i>Lưu
                                </button>
                                <a href="{{ route('admin.attribute.index') }}"
                                    class="btn btn-secondary btn-create-attribute-cancel px-4 d-inline-flex align-items-center justify-content-center"
                                    style="white-space: nowrap;">
                                    <i class="fas fa-times mr-1"></i>Hủy bỏ
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('css')
    <style>
        .card {
            border-radius: 0.5rem;
        }
    </style>
@endsection

@section('javascript')
    <script>
        $(function() {
            var typeSelectValue = '{{ TypeAttribute::SELECT }}';

            function toggleValueTextarea(selected) {
                if (selected == typeSelectValue) {
                    $("#textAreaForAttribute").removeClass('d-none');
                } else {
                    $("#textAreaForAttribute").addClass('d-none');
                    $("#contentTextAreaForAttribute").val('');
                }
            }

            // Kiểm tra giá trị đang chọn khi tải trang (giữ lại khi validate lỗi / old input)
            toggleValueTextarea($("#selectForAttribute").val());

            // Khi đổi loại thuộc tính
            $("#selectForAttribute").change(function() {
                toggleValueTextarea($(this).val());
            });

            setTimeout(function() {
                $('.alert-danger').remove();
            }, 3000)
        });
    </script>
@endsection
