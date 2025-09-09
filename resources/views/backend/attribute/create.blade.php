@extends('backend.layouts.main')

@section('title', 'Thêm mới thuộc tính')
<?php use App\Enums\TypeAttribute; ?>
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3>Thêm mới thuộc tính</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.attribute.store') }}" method="POST" class="col-md-10 mx-auto form-create">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2 text-right">
                                <label>Tên thuộc tính</label>
                                <span style="color: red;">*</span>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="row">
                            <div class="col-md-2 text-right">
                                <label>Loại</label>
                                <span style="color: red;">*</span>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control" name="type" id="selectForAttribute" value="{{ old('type') }}">
                                    <option value="">@lang('Chọn loại thuộc tính')</option>
                                    @foreach(TypeAttribute::getValues() as $type)
                                        <option value="{{ $type }}"
                                        @if ( old('type') == $type) {{ 'selected' }} @endif>
                                            @lang(TypeAttribute::getTypeAttr($type))
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group {{ old('type') && old('type') == TypeAttribute::SELECT ? '' : 'd-none' }}" id="textAreaForAttribute" >
                        <div class="row">
                            <div class="col-md-2 text-right">
                                <label>Giá trị</label>
                                <span style="color: red;">*</span> <br>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="5" name="value"
                                          id="contentTextAreaForAttribute" placeholder="Các giá trị ngăn cách nhau bởi dấu (;)">{{ old('value') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div style="padding: 0.5rem!important;"></div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-8">
                                <input type="submit" value="Lưu" class="btn btn-success btn_save_attribute" style="margin-right: 2px;"/>
                                <button class="btn btn-secondary btn-create-attribute-cancel" type="button">Hủy bỏ</button>
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
        $(function () {
            //check current selected of selectbox
            var curentSelectedForAttribute = $("#selectForAttribute").children("option:selected").val();
            //*if value not equal(text;number;numberfloat) - display value textarea
            if (curentSelectedForAttribute == 'select') {
                $("#textAreaForAttribute").removeClass('d-none')
            }
            //*if valuet equal(text;number;numberfloat) -  no display value textarea
            if (curentSelectedForAttribute != 'select') {
                $("#textAreaForAttribute").addClass('d-none')
            }

            // change selected box
            $("#selectForAttribute").change(function () {
                //*get selected value
                var selected = $(this).children("option:selected").val();
                //*if value is select - display value textarea
                if (selected == 'select') {
                    $("#textAreaForAttribute").removeClass('d-none')
                }
                //*if value not is select - no display value textarea
                if (selected != 'select') {
                    $("#textAreaForAttribute").addClass('d-none')
                    //**reset value textarea
                    $("#contentTextAreaForAttribute").val('');
                }
            });
            setTimeout(function () {
                $('.alert-danger').remove();
            }, 3000)
        });
    </script>
@endsection

