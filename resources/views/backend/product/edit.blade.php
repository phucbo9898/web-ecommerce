@extends('backend.layouts.main')

@section('title', 'Chỉnh sửa sản phẩm')
<?php
use App\Enums\ActiveHot;
use App\Enums\ActiveStatus;
?>
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">Chỉnh sửa sản phẩm</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">Sản phẩm</a></li>
                        <li class="breadcrumb-item active">Chỉnh sửa</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('admin.product.update', ['id' => $product->id]) }}" method="POST"
                id="form-update" class="mx-auto form-update" enctype="multipart/form-data">
                @csrf
                {{-- @method('PUT') --}}

                <div class="row">
                    {{-- Cột trái: thông tin chính --}}
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white">
                                <h3 class="card-title mb-0">
                                    <i class="fas fa-box text-primary mr-2"></i>Thông tin sản phẩm
                                    @isset($product)
                                        <span class="text-muted font-weight-normal">— {{ $product->name }}</span>
                                    @endisset
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="font-weight-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name', $product->name ?? '') }}"
                                        placeholder="Nhập tên sản phẩm...">
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Tên đầy đủ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="full_name"
                                        value="{{ old('full_name', $product->full_name ?? '') }}"
                                        placeholder="Nhập tên đầy đủ sản phẩm...">
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Loại sản phẩm <span class="text-danger">*</span></label>
                                    <select name="category_id" id="select_category_id" class="form-control">
                                        <option value="">Chọn loại sản phẩm</option>
                                        @foreach ($categories as $key => $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Thuộc tính động theo danh mục, được load qua AJAX --}}
                                <div id="attribute_for_product"></div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Giá sản phẩm <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="price"
                                                    value="{{ old('price', $product->price ?? '') }}"
                                                    placeholder="Nhập giá sản phẩm...">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">VNĐ</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Giảm giá <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="sale"
                                                    value="{{ old('sale', $product->sale ?? 0) }}"
                                                    placeholder="Giảm giá sản phẩm...">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Thông số sản phẩm <span
                                            class="text-danger">*</span></label>
                                    <textarea name="description" rows="5" class="form-control ckeditor"
                                        placeholder="Nhập thông số sản phẩm...">{{ old('description', $product->description ?? '') }}</textarea>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="font-weight-bold">Nội dung sản phẩm <span
                                            class="text-danger">*</span></label>
                                    <textarea name="content" rows="5" class="form-control ckeditor"
                                        placeholder="Nhập nội dung sản phẩm...">{{ old('content', $product->content ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cột phải: ảnh + cài đặt --}}
                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white">
                                <h3 class="card-title mb-0">
                                    <i class="fas fa-image text-primary mr-2"></i>Ảnh minh họa
                                    <span class="text-danger">*</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="image-upload-wrapper" id="image-dropzone">
                                    <input type="file" name="image" id="img_input" accept="image/*" class="d-none">
                                    <div class="image-upload-placeholder {{ !empty($product->image) ? 'd-none' : '' }}"
                                        id="image-upload-placeholder">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <div class="font-weight-bold">Kéo thả ảnh vào đây</div>
                                        <div class="small">hoặc bấm để chọn ảnh từ máy tính</div>
                                    </div>
                                    <img class="img-preview {{ empty($product->image) ? 'd-none' : '' }}"
                                        id="img_output" src="{{ !empty($product->image) ? asset($product->image) : asset('unimg.jpg') }}"
                                        alt="Ảnh sản phẩm">
                                    <button type="button" id="img_remove_btn"
                                        class="btn btn-sm btn-danger btn-circle image-remove-btn {{ empty($product->image) ? 'd-none' : '' }}"
                                        title="Chọn ảnh khác">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted">Định dạng JPG, PNG. Để trống nếu không muốn đổi
                                    ảnh hiện tại.</small>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white">
                                <h3 class="card-title mb-0">
                                    <i class="fas fa-sliders-h text-primary mr-2"></i>Cài đặt
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="font-weight-bold d-block">Sản phẩm hot</label>
                                    @foreach (ActiveHot::getValues() as $hot)
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input id="hot_{{ $hot }}" class="custom-control-input" name="hot"
                                                type="radio"
                                                {{ old('hot', $product->hot ?? '') == $hot ? 'checked' : '' }}
                                                value="{{ $hot }}">
                                            <label class="custom-control-label"
                                                for="hot_{{ $hot }}">{{ __(ActiveHot::getHotName($hot)) }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="form-group mb-0">
                                    <label class="font-weight-bold d-block">Trạng thái</label>
                                    @foreach (ActiveStatus::getValues() as $status)
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input id="publish_{{ $status }}" class="custom-control-input"
                                                name="publish" type="radio"
                                                {{ old('publish', $product->publish ?? '') == $status ? 'checked' : '' }}
                                                value="{{ $status }}">
                                            <label class="custom-control-label"
                                                for="publish_{{ $status }}">{{ __(ActiveStatus::getStatusName($status)) }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="d-flex">
                            <button type="submit"
                                class="btn btn-success btn_save_product flex-grow-1 mr-2 d-inline-flex align-items-center justify-content-center"
                                style="white-space: nowrap;">
                                <i class="fas fa-save mr-1"></i>Lưu thông tin
                            </button>
                            <a href="{{ route('admin.product.index') }}"
                                class="btn btn-secondary btn-update-product-cancel d-inline-flex align-items-center justify-content-center"
                                style="white-space: nowrap;">
                                <i class="fas fa-times mr-1"></i>Hủy bỏ
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('css')
    <style>
        .card {
            border-radius: 0.5rem;
        }

        .btn-circle {
            width: 30px;
            height: 30px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .image-upload-wrapper {
            position: relative;
            border: 2px dashed #ced4da;
            border-radius: 0.5rem;
            background-color: #f8f9fa;
            min-height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            overflow: hidden;
            transition: border-color .2s ease, background-color .2s ease;
        }

        .image-upload-wrapper:hover,
        .image-upload-wrapper.dragover {
            border-color: #007bff;
            background-color: #eef6ff;
        }

        .image-upload-placeholder {
            text-align: center;
            color: #6c757d;
            padding: 1.5rem;
        }

        .image-upload-placeholder i {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            display: block;
            color: #adb5bd;
        }

        .img-preview {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .image-remove-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 2;
        }
    </style>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert-danger').remove();
            }, 3000);

            /* ================== Upload ảnh: kéo-thả + preview ================== */
            var $dropzone = $('#image-dropzone');
            var $imgInput = $('#img_input');
            var $imgOutput = $('#img_output');
            var $placeholder = $('#image-upload-placeholder');
            var $removeBtn = $('#img_remove_btn');
            var originalImageSrc = $imgOutput.attr('src');

            function showImagePreview(file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $imgOutput.attr('src', e.target.result).removeClass('d-none');
                    $placeholder.addClass('d-none');
                    $removeBtn.removeClass('d-none');
                };
                reader.readAsDataURL(file);
            }

            $dropzone.on('click', function(e) {
                // Bỏ qua nếu click bắt nguồn từ chính input file (tránh trigger click lặp vô hạn
                // khiến trình duyệt chặn mở hộp thoại chọn file) hoặc từ nút xóa ảnh.
                if ($(e.target).is('#img_input') || $(e.target).closest('#img_remove_btn').length) {
                    return;
                }
                $imgInput.trigger('click');
            });

            $imgInput.on('change', function() {
                if (this.files && this.files[0]) {
                    showImagePreview(this.files[0]);
                }
            });

            $dropzone.on('dragover dragenter', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $dropzone.addClass('dragover');
            });

            $dropzone.on('dragleave drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $dropzone.removeClass('dragover');
            });

            $dropzone.on('drop', function(e) {
                var files = e.originalEvent.dataTransfer ? e.originalEvent.dataTransfer.files : null;
                if (files && files.length) {
                    $imgInput[0].files = files;
                    showImagePreview(files[0]);
                }
            });

            // Ở màn cập nhật: nút "x" chỉ xóa ảnh MỚI vừa chọn (nếu có) để quay lại ảnh hiện tại
            // của sản phẩm, không xóa mất ảnh cũ trên server. Nếu sản phẩm chưa có ảnh thì quay
            // về placeholder như bình thường.
            $removeBtn.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $imgInput.val('');
                if (originalImageSrc && !originalImageSrc.includes('unimg.jpg')) {
                    $imgOutput.attr('src', originalImageSrc).removeClass('d-none');
                    $placeholder.addClass('d-none');
                } else {
                    $imgOutput.addClass('d-none');
                    $placeholder.removeClass('d-none');
                    $removeBtn.addClass('d-none');
                }
            });

            /* ================== Thuộc tính động theo danh mục ================== */
            $("#select_category_id").change(function() {
                var selected = $(this).children("option:selected").val();
                var id = "{{ isset($product) ? $product->id : '0' }}";
                var url = "{{ route('admin.product.ajax.get.attribute') }}";
                if (selected != '') {
                    $.ajax({
                        type: "GET",
                        url: url,
                        data: {
                            category_id: selected,
                            id: id
                        }
                    }).done(function(result) {
                        $("#attribute_for_product").html('').append(result);
                    });
                }
            });

            // Lấy thuộc tính của danh mục hiện tại khi load trang
            var valueCategoryCurrent = $("#select_category_id").children("option:selected").val();
            var url = "{{ route('admin.product.ajax.get.attribute') }}";
            var id = "{{ isset($product) ? $product->id : '0' }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    category_id: valueCategoryCurrent,
                    id: id
                }
            }).done(function(result) {
                $("#attribute_for_product").html('').append(result);
            });

            CKEDITOR.replace('ckeditor');
        });
    </script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
@endsection
