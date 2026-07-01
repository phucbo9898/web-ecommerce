@extends('backend.layouts.main')

@section('title', 'Create new category')
<?php

use App\Enums\PublishEnum;

?>
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">Thêm mới danh mục</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.category.index') }}">Danh mục</a></li>
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
                        <i class="fas fa-plus-circle text-primary mr-2"></i>Create new category
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.category.store') }}" method="POST"
                        class="col-md-10 mx-auto form-create">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right font-weight-bold">
                                Name category <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-8">
                                <input type="text" id="name" class="form-control" name="name"
                                    placeholder="Nhập tên danh mục..." value="{{ old('name') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right font-weight-bold">
                                Attribute <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-8">
                                <div class="border rounded p-3 attribute-box">
                                    <div class="row">
                                        @forelse ($attributes as $attribute)
                                            <div class="col-md-6 col-lg-4 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="attribute_{{ $attribute->id }}" name="attributes[]"
                                                        value="{{ $attribute->id }}"
                                                        {{ old('attributes') && in_array($attribute->id, old('attributes')) ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="attribute_{{ $attribute->id }}">{{ $attribute->name }}</label>
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
                                    <i class="fas fa-save mr-1"></i>Save
                                </button>
                                <a href="{{ route('admin.category.index') }}"
                                    class="btn btn-secondary btn-common px-4 d-inline-flex align-items-center justify-content-center"
                                    style="white-space: nowrap;">
                                    <i class="fas fa-times mr-1"></i>@lang('Cancel')
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
