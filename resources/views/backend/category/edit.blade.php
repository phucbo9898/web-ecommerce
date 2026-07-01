@extends('backend.layouts.main')

@section('title', 'Chỉnh sửa danh mục')
<?php
use App\Enums\PublishEnum;
?>
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">Chỉnh sửa danh mục</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.category.index') }}">Danh mục</a></li>
                        <li class="breadcrumb-item active">Chỉnh sửa</li>
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
                        <i class="fas fa-edit text-primary mr-2"></i>Chỉnh sửa danh mục
                        @isset($category)
                            <span class="text-muted font-weight-normal">— {{ $category->name }}</span>
                        @endisset
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.category.update', $category->id) }}" method="POST"
                        class="col-md-10 mx-auto form-create">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right font-weight-bold">
                                Tên danh mục <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" id="name" class="form-control" name="name"
                                    placeholder="Nhập tên danh mục..."
                                    value="{{ old('name') ?? ($category->name ?? '') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right font-weight-bold">
                                Thuộc tính
                            </label>
                            <div class="col-md-8">
                                <div class="border rounded p-3 attribute-box">
                                    <div class="row">
                                        @forelse ($attributes as $attribute)
                                            <div class="col-md-6 col-lg-4 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="attribute_{{ $attribute->id }}"
                                                        name="{{ $attribute->id }}"
                                                        {{ isset($arrayCategoryAttribute) ? (in_array($attribute->id, $arrayCategoryAttribute) ? 'checked' : '') : '' }}>
                                                    <label class="form-check-label"
                                                        for="attribute_{{ $attribute->id }}">{{ $attribute->name ?? '' }}</label>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-muted text-center py-2">
                                                Chưa có thuộc tính nào. Vui lòng tạo thuộc tính trước.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                                <small class="form-text text-muted">Chọn một hoặc nhiều thuộc tính áp dụng cho danh
                                    mục này.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <button type="submit"
                                    class="btn btn-success btn-common px-4 d-inline-flex align-items-center justify-content-center"
                                    style="white-space: nowrap;">
                                    <i class="fas fa-save mr-1"></i>Lưu
                                </button>
                                <a href="{{ route('admin.category.index') }}"
                                    class="btn btn-secondary btn-create-category-cancel btn-common px-4 d-inline-flex align-items-center justify-content-center"
                                    style="white-space: nowrap;">
                                    <i class="fas fa-times mr-1"></i>@lang('Hủy bỏ')
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

        .attribute-box {
            max-height: 260px;
            overflow-y: auto;
            background-color: #f8f9fa;
        }

        .form-check-input {
            margin-top: 0.3rem;
        }

        /* Ép icon + chữ trong nút luôn nằm ngang hàng, tránh xung đột với style btn-common của theme */
        .btn-common {
            flex-direction: row !important;
            gap: 0;
        }

        .btn-common i {
            display: inline-block;
        }
    </style>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert-danger').remove();
            }, 3000)
        });
    </script>
@endsection
