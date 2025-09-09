@extends('backend.layouts.main')

@section('title', 'Tạo mới danh mục')
<?php
    use App\Enums\PublishEnum;
?>
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3>Tạo mới danh mục</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.category.store') }}" method="POST" class="col-md-10 mx-auto form-create">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2 text-right">
                                <label>Tên danh mục</label>
                                <span class="text-red">*</span>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name"
                                       value="{{ old('name') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2 text-right">
                                <label>Thuộc tính <span class="text-red">*</span></label>
                            </div>
                            <div class="col-md-8">
                                @foreach ($attributes as $attribute)
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="attributes[]" value="{{ $attribute->id }}"
                                                {{ old('attributes') && in_array($attribute->id, old('attributes')) ? 'checked' : '' }}>{{ $attribute->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{--                    <div class="form-group">--}}
                    {{--                        <div class="row">--}}
                    {{--                            <div class="col-md-2 text-right">--}}
                    {{--                                <label style="margin-right: 2px;">Trạng thái</label>--}}
                    {{--                            </div>--}}
                    {{--                            <div class="col-md-8">--}}
                    {{--                                @foreach(ActiveStatus::getValues() as $status)--}}
                    {{--                                    <span class="mr-2">--}}
                    {{--                                        <input--}}
                    {{--                                            {{ old('status') == $status || $status == 'active' ? "checked" : '' }} type="radio"--}}
                    {{--                                            name="status" value="{{ $status }}">--}}
                    {{--                                        <label for="">@lang(ActiveStatus::getStatusName($status))</label>--}}
                    {{--                                    </span>--}}
                    {{--                                @endforeach--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}

                    <div style="padding: 0.5rem!important;"></div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-success btn-common float-left" style="margin-right: 2px;">
                                    Lưu
                                </button>
                                <button class="btn btn-secondary btn-create-category-cancel btn-common" type="button">@lang('Hủy bỏ')</button>
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
        $(document).ready(function () {
            setTimeout(function () {
                $('.alert-danger').remove();
            }, 3000)
        });
    </script>
@endsection
