@extends('backend.layouts.main')

@section('title', 'Chỉnh sửa danh mục')
<?php
use App\Enums\PublishEnum;
?>
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3>Chỉnh sửa danh mục</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.category.update', $category->id) }}" method="POST" class="col-md-10 mx-auto form-create">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2 text-right">
                                <label>Tên danh mục</label>
                                <span class="text-red">*</span>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name"
                                    value="{{ old('name') ?? ($category->name ?? '') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2 text-right">
                                <label>Thuộc tính</label>
                            </div>
                            <div class="col-md-8">
                                @foreach ($attributes as $attribute)
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="{{ $attribute->id }}"
                                                {{ isset($arrayCategoryAttribute) ? (in_array($attribute->id, $arrayCategoryAttribute) ? 'checked' : '') : '' }}>{{ $attribute->name ?? '' }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div style="padding: 0.5rem!important;"></div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-success btn-common float-left"
                                    style="margin-right: 2px;">
                                    Lưu
                                </button>
                                <a href="{{ route('admin.category.index') }}" class="btn btn-secondary btn-create-category-cancel btn-common"
                                    type="button">@lang('Hủy bỏ')</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
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
