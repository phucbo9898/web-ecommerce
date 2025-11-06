@extends('fe.layout.master')
@section('content')
    <style>
        .btn-primary {
            color: #fff !important;
            background-color: #007bff !important;
            border-color: #007bff !important;
        }

        .btn-primary:hover {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
        }

        .btn-warning {
            color: #212529 !important;
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
        }

        .btn-warning:hover {
            background-color: #47ea36 !important;
            border-color: #47ea36 !important;
        }
    </style>
    <!-- Begin Li's Breadcrumb Area -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="breadcrumb-content">
                <ul>
                    <li><a href="{{ route('frontend.home') }}">@lang('Home')</a></li>
                    <li class="active">@lang('Shopping')</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Li's Breadcrumb Area End Here -->
    <!--Shopping Cart Area Strat-->
    <div class="Shopping-cart-area pt-60 pb-60">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @if (\Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>@lang('Success')!</strong> {{ \Session::get('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (\Session::has('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>@lang('Error')!</strong> {{ \Session::get('warning') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="table-content table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="li-product-remove">@lang('Delete')</th>
                                    <th class="li-product-thumbnail">@lang('Image')</th>
                                    <th class="cart-product-name">@lang('Product name')</th>
                                    <th class="li-product-price">@lang('Price')</th>
                                    <th class="li-product-quantity">@lang('Quantity')</th>
                                    <th class="li-product-subtotal">@lang('Total')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key => $product)
                                    <tr>
                                        <td class="li-product-remove"><a
                                                href="{{ route('frontend.shopping.delete.product', $key) }}"><i
                                                    class="fa fa-times"></i></a></td>
                                        @if ($product->attributes->image)
                                            <td class="li-product-thumbnail" style="width: 16%"><a href="#"><img
                                                        style="width: 100%; height: 150px;"
                                                        src="{{ asset($product->attributes->image) }}"
                                                        alt="Li's Product Image"></a></td>
                                        @else
                                            <td class="li-product-thumbnail" style="width: 16%"><a href="#"><img
                                                        style="width: 100%; height: 150px;" src="{{ asset('noimg.png') }}"
                                                        alt="Li's Product Image"></a></td>
                                        @endif
                                        <td class="li-product-name"><a
                                                href="{{ route('frontend.product.show', [$product->attributes->uuid]) }}">{{ $product->name }}</a>
                                        </td>
                                        <td class="li-product-price"><span
                                                class="amount">{{ number_format($product->price, 2, ',', '.') }}
                                                @lang('VND')</span>
                                        </td>
                                        <td class="quantity">
                                            <form action="{{ route('frontend.shopping.edit.product') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="pro_id" value="{{ $product->id }}" />
                                                <span
                                                    style="color: #242424l;font-size: 16px;font-weight: 700;">{{ $product->quantity }}
                                                    @if ($product->quantity > 1)
                                                        @lang('products')
                                                    @else
                                                        @lang('product')
                                                    @endif
                                                </span>
                                                <div class="cart-plus-minus">
                                                    <input class="cart-plus-minus-box" value="{{ $product->quantity }}"
                                                        name="number_product_edit" type="number" min="1">
                                                    <div class="dec qtybutton"><i class="fa fa-angle-down"></i></div>
                                                    <div class="inc qtybutton"><i class="fa fa-angle-up"></i></div>
                                                </div>
                                                <button class="btn btn-primary mt-2">@lang('Update')</button>
                                            </form>
                                        </td>
                                        <td class="product-subtotal"><span
                                                class="amount">{{ number_format($product->price * $product->quantity, 2, ',', '.') }}
                                                @lang('VND')<br /> @lang('Into money'):
                                                {{ number_format($product->price * $product->quantity, 0, ',', '.') }}
                                                @lang('VND')</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-5 ml-auto">
                            <div class="cart-page-total">
                                <h2>@lang('Total Amount To Pay'):</h2>
                                <div class="mb-2">
                                    {{-- <form action="{{ route('frontend.shopping.add-coupon') }}" method="post">
                                        @csrf

                                    </form> --}}
                                    <div class="row">
                                        <div class="col-md-3">
                                            <span>@lang('Voucher')</span>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="coupon" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-success submit-add-coupon">@lang('Apply')</button>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <hr style="padding: 0; margin: 1rem 0;">
                                    @if (count(\Cart::session(auth()->id())->getConditions()) > 0)
                                        <div class="d-flex justify-content-between">
                                            @lang('Before the discount'):
                                            <div>
                                                {{ number_format(\Cart::session(Auth::id())->getSubTotal()) }}
                                                @lang('VND')
                                            </div>
                                        </div>
                                        <hr style="padding: 0; margin: 1rem 0;">
                                        <div class="d-flex justify-content-between">
                                            @lang('Voucher'):
                                            <div>
                                                @php
                                                    $cartInfo = \Cart::session(auth()->id())->getConditions();
                                                    $cartInfo = $cartInfo->first();
                                                @endphp
                                                @if ($cartInfo)
                                                {{-- @dd(count($cartInfo->getAttributes())) --}}
                                                    @foreach ($cartInfo->getAttributes() as $coupon)
                                                        <div class="mb-1 d-block">
                                                            <span>{{ $coupon['coupon_code'] . ' (-' . $coupon['discount'] . '%)' }}</span>
                                                            <button class="btn btn-danger delete-coupon"
                                                                data-id="{{ $coupon['coupon_code'] }}">x</button>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <hr style="padding: 0; margin: 1rem 0;">
                                        <div class="d-flex justify-content-between">
                                            @lang('Total')
                                            <span>
                                                {{ number_format(\Cart::session(Auth::id())->getTotal()) }}
                                                @lang('VND')
                                            </span>
                                        </div>
                                    @else
                                        <div class="d-flex justify-content-between">
                                            @lang('Total')
                                            <span>
                                                {{ number_format(\Cart::session(Auth::id())->getTotal(), 0, ',', '.') }}
                                                @lang('VND')
                                            </span>
                                        </div>
                                    @endif
                                    <hr style="padding: 0; margin: 1rem 0;">
                                </div>
                                <input type="hidden" class="total" value="{{ \Cart::session(Auth::id())->getTotal() }}">
                                <div class="mt-15">
                                    @if (\Cart::session(Auth::id())->getTotal() > 0)
                                        <a class="btn btn-primary" href="{{ route('frontend.feature-user.checkout') }}"
                                            style="float: right; margin-left: 20px;">
                                            @lang('Order confirmation')
                                        </a>
                                    @endif
                                </div>
                                <div style="clear: both"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Shopping Cart Area End-->
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            $(".submit-add-coupon").click(function(e) {
                e.preventDefault();
                let coupon = $("input[name='coupon']").val();
                swal({
                        title: "Bạn có chắc chắn?",
                        text: "Bạn có chắc chắn muốn thêm Coupon không ?",
                        icon: "warning",
                        buttons: ["Không", "Có"],
                        dangerMode: true,
                    })
                    .then((result) => {
                        if (result) {
                            $.ajax({
                                url: "{{ route('frontend.shopping.add-coupon') }}",
                                type: 'GET',
                                data: {
                                    coupon: coupon,
                                },
                                dataType: 'json',
                                success: function(data) {
                                    if (data.status == 'success') {
                                        swal(
                                            "Thành công",
                                            "Đã thêm coupon!",
                                            'success'
                                        ).then((result) => {
                                            if (result) {
                                                location.reload();
                                            }
                                        })
                                    } else if (data.status == 'warning') {
                                        swal({
                                            title: "Cảnh báo!",
                                            text: "Voucher đã được áp dụng rồi!",
                                            icon: "warning",
                                            showConfirmButton: false,
                                            showCancelButton: false,
                                            dangerMode: true,
                                            timer: 1500
                                        });
                                    } else {
                                        swal({
                                            title: "Có lỗi xảy ra!",
                                            text: "",
                                            icon: "error",
                                            showConfirmButton: false,
                                            dangerMode: true,
                                            timer: 1500
                                        });
                                    }
                                }
                            });
                        }
                    });
            });

            $(".delete-coupon").click(function(e) {
                e.preventDefault();
                let coupon_code = $(this).data('id');
                swal({
                        title: "Bạn có chắc chắn?",
                        text: "Bạn có chắc chắn muốn xóa Coupon không ?",
                        icon: "warning",
                        buttons: ["Không", "Có"],
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: "{{ route('frontend.shopping.remove-coupon') }}",
                                type: 'GET',
                                data: {
                                    coupon_code: coupon_code,
                                },
                                dataType: 'json',
                                success: function(data) {
                                    if (data.status == 'success') {
                                        swal(
                                            "Thành công",
                                            "Đã xóa coupon!",
                                            'success'
                                        ).then(
                                            function() {
                                                location.reload();
                                            });
                                    } else {
                                        swal({
                                            title: "Có lỗi xảy ra!",
                                            text: "",
                                            icon: "error",
                                            showConfirmButton: false,
                                            dangerMode: true,
                                            timer: 1500
                                        });
                                    }
                                }
                            });
                        }
                    });
            });
        })
    </script>
@endsection
