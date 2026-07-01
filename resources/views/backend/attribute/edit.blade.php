@extends('backend.layouts.main')

@section('title', 'Cập nhật thuộc tính')
<?php use App\Enums\TypeAttribute; ?>
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">Cập nhật thuộc tính</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.attribute.index') }}">Thuộc tính</a></li>
                        <li class="breadcrumb-item active">Cập nhật</li>
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
                        <i class="fas fa-edit text-primary mr-2"></i>Cập nhật thuộc tính
                        @isset($attribute)
                            <span class="text-muted font-weight-normal">— {{ $attribute->name }}</span>
                        @endisset
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.attribute.update', ['id' => $attribute->id]) }}" method="POST"
                        class="col-md-10 mx-auto form-update" id="form-update">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right font-weight-bold">
                                Tên thuộc tính <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" id="name" class="form-control" name="name"
                                    placeholder="Nhập tên thuộc tính..."
                                    value="{{ old('name', $attribute->name ?? '') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="selectForAttribute" class="col-md-2 col-form-label text-md-right font-weight-bold">
                                Loại thuộc tính <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-8">
                                <select class="form-control" name="type" id="selectForAttribute">
                                    @foreach (TypeAttribute::getValues() as $type)
                                        <option value="{{ $type }}"
                                            {{ old('type', $attribute->type ?? '') == $type ? 'selected' : '' }}>
                                            @lang(TypeAttribute::getTypeAttr($type))
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @php
                            $currentType = old('type', $attribute->type ?? '');
                            $showValueField = $currentType == TypeAttribute::SELECT;
                        @endphp
                        <div class="form-group row {{ $showValueField ? '' : 'd-none' }}" id="textAreaForAttribute">
                            <label for="contentTextAreaForAttribute"
                                class="col-md-2 col-form-label text-md-right font-weight-bold">
                                Giá trị <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="5" name="value" id="contentTextAreaForAttribute"
                                    placeholder="Các giá trị phân cách bằng dấu chấm phẩy( ; )">{{ old('value', $attribute->value ?? '') }}</textarea>
                                <small class="form-text text-muted">Ví dụ: S;M;L;XL</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <button type="submit"
                                    class="btn btn-success btn_save_attribute px-4 d-inline-flex align-items-center justify-content-center"
                                    style="white-space: nowrap;">
                                    <i class="fas fa-save mr-1"></i>Lưu thông tin
                                </button>
                                <a href="{{ route('admin.attribute.index') }}"
                                    class="btn btn-secondary btn-update-attribute-cancel px-4 d-inline-flex align-items-center justify-content-center"
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

            // Kiểm tra giá trị đang chọn khi tải trang
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
