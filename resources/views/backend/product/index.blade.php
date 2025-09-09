@extends('backend.layouts.main')

@section('title', 'Danh sách sản phẩm')
@php use App\Enums\ActiveStatus; @endphp
@section('content')
    <style>
        .rating .active {
            color: #ff9705 !important;
        }
    </style>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3>Danh sách sản phẩm</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="">
                        <form action="{{ url()->full() }}" method="GET">
                            <div class="row">
                                <div class="col-md-3 mt-3">
                                    <label for="name" class="col-form-label">@lang('Tên sản phẩm')</label>
                                    <input type="text" autocomplete="off" name="name" class="form-control"
                                        value="{{ $options['name'] ?? '' }}">
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="entertainment" class="col-form-label">@lang('Tên danh mục')</label> <br>
                                    <select name="category_id" class="form-control select2-blue">
                                        <option value="">@lang('Chọn danh mục')</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id ?? $options['category_id'] }}"
                                                @if (isset($options['category_id']) && $category->id == $options['category_id']) {{ 'selected' }} @endif>
                                                @lang($category->name)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="name" class="col-form-label">@lang('Sắp xếp theo giá')</label>
                                    <select name="filter_price" class="form-control">
                                        <option value="">Chọn loại sắp xếp</option>
                                        <option value="asc"
                                            {{ ($options['filter_price'] ?? '') == 'asc' ? 'selected' : '' }}>Sắp xếp tăng
                                            dần</option>
                                        <option value="desc"
                                            {{ ($options['filter_price'] ?? '') == 'desc' ? 'selected' : '' }}>Sắp xếp giảm
                                            dần</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="name" class="col-form-label">@lang('Sắp xếp theo số lượng đã bán')</label>
                                    <select name="filter_sold" class="form-control">
                                        <option value="">Chọn loại sắp xếp</option>
                                        <option value="asc"
                                            {{ ($options['filter_sold'] ?? '') == 'asc' ? 'selected' : '' }}>Sắp xếp tăng
                                            dần</option>
                                        <option value="desc"
                                            {{ ($options['filter_sold'] ?? '') == 'desc' ? 'selected' : '' }}>Sắp xếp giảm
                                            dần</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-3">
                                    <label class="col-form-label mr-2">@lang('Trạng thái')</label>
                                    @foreach (ActiveStatus::getValues() as $status)
                                        <span class="mr-2">
                                            <input id="{{ $status }}"
                                                {{ isset($options['publish']) && in_array($status, $options['publish']) ? 'checked' : '' }}
                                                type="checkbox" name="publish[]" value="{{ $status }}">
                                            <label for="">@lang(ActiveStatus::getStatusName($status))</label>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <button class="btn btn-info min-w-100" type="submit">@lang('Tìm kiếm')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <th>Sản phẩm</th>
                        <th>UUID</th>
                        <th>Loại sản phẩm</th>
                        <th>Ảnh</th>
                        <th style="width: 11%;">Trạng thái</th>
                        <th>Nổi bật</th>
                        <th style="width: 12%;">Hành động</th>
                    </thead>
                    <tbody>
                        @if (isset($products))
                            @foreach ($products as $product)
                                <tr>
                                    <td>
                                        <b>{{ $product->name }}</b><br />
                                        <ul>
                                            <li>Số lượng tồn kho: {{ $product->quantity > 0 ? $product->quantity : 0 }}
                                            </li>
                                            @if ($product->sale)
                                                <li>Đang giảm giá ( -{{ $product->sale }}% )</li>
                                            @else
                                                <li>Không giảm giá</li>
                                            @endif
                                            <li>
                                                Giá: {{ number_format($product->price, 0, ',', '.') }} VNĐ
                                            </li>
                                            <li>
                                                <span>Số lượng đã bán:
                                                    {{ $product->qty_pay > 0 ? $product->qty_pay : 0 }}</span>
                                            </li>
                                        </ul>
                                    </td>
                                    <td>{{ $product->uuid }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>
                                        @if ($product->image)
                                            <img style="width:80px;height:80px"
                                                src="{{ asset($product->image) }}"
                                                alt="No Avatar" />
                                        @else
                                            <img style="width:80px;height:80px" src="{{ asset('noimg.png') }}"
                                                alt="No Avatar" />
                                        @endif
                                    </td>
                                    <td><a href="{{ route('admin.product.handle', ['status', $product->id]) }}"
                                            class="badge badge-{{ $product->publish == 1 ? 'success' : 'danger' }}">{{ $product->publish == 1 ? 'Công khai' : 'Riêng tư' }}</a>
                                    </td>
                                    <td><a href="{{ route('admin.product.handle', ['hot', $product->id]) }}"
                                            class="badge badge-{{ $product->hot == 1 ? 'success' : 'secondary' }}">{{ $product->hot == 1 ? 'Có' : 'Không' }}</a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.product.edit', $product->id) }}"
                                            class="btn btn-success btn-circle"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('admin.product.handle', ['delete', $product->id]) }}"
                                            data-id="{{ $product->id }}"
                                            class="btn btn-danger btn-circle"><i
                                                class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </section>
@endsection
