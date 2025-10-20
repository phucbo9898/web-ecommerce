@extends('fe.layout.master')
@section('content')
    <style>
        .product-description p {
            color: black;
        }

        .product-description p h1, h2, h3 {
            font-size: 22px;
        }

        .product-description img {
            display: block;
            margin: 0 auto;
            width: 56%;
            height: 50%;
            overflow: hidden;
        }

        .list_text {
            display: inline-block;
            margin-left: 10px;
            position: relative;
            background: #52b858;
            color: #fff;
            padding: 2px 8px;
            box-sizing: border-box;
            font-size: 12px;
            border-radius: 2px;
            display: none;
        }

        .list_text::after {
            right: 100%;
            top: 50%;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
            border-color: rgba(82, 184, 88, 0);
            border-right-color: #52b858;
            border-width: 6px;
            margin-top: -6px;
        }

        .list_start .rating_active, .pro-rating .active {
            color: #ff9705 !important;
        }

        .content_product_description img {
            width: 50%;
        }
        .breadcrumb-hover:hover {
            color: #1e1ec4 !important;
        }
    </style>
    <!-- Begin Li's Breadcrumb Area -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="breadcrumb-content">
                <ul>
                    <li>
                        <a class="breadcrumb-hover" href="{{ route('frontend.home') }}">@lang('Home')</a>
                    </li>
                    <li>
                        <a class="breadcrumb-hover" href="{{ route('category.index', [$product->category->slug, $product->category->id]) }}">{{ $product->category->name }}</a>
                    </li>
                    <li class="active">{{ $product->name }}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Li's Breadcrumb Area End Here -->
    <!-- content-wraper start -->
    <div class="content-wraper">
        <div class="container">
            <div class="row single-product-area">
                <div class="col-lg-5 col-md-6">
                    <!-- Product Details Left -->
                    <div class="product-details-left">
                        <div class="product-details-images slider-navigation-1">
                            <div class="lg-image">
                                @if (isset($product->image))
                                    <img src="{{ asset($product->image) }}" alt="Li's Product Image">
                                @else
                                    <img src="{{ asset('noimg.png') }}" alt="Li's Product Image">
                                @endif
                            </div>
                        </div>
                    </div>
                    <!--// Product Details Left -->
                </div>

                <div class="col-lg-7 col-md-6">
                    <div class="product-details-view-content sp-normal-content pt-30">
                        <div class="product-info">
                            <h2>{{ $product->name_full }}</h2>
                            <span class="product-details-ref">@lang('Status'):
                                @if ($product->quantity > 10)
                                    <b style="color: #3d3de3;">@lang('Stocking')</b>
                                @elseif($product->quantity <= 10 && $product->quantity > 0)
                                    <b style="color: #bfbf50;">@lang('Almost out of stock')</b>
                                @elseif($product->quantity <= 0)
                                    <b style="color: red;">@lang('Out of stock')</b>
{{--                                @else--}}
{{--                                    <b>@lang('Unknown')</b>--}}
                                @endif
                            </span>
                            <div class="rating-box pt-20">
                                <?php
                                $point = 0;
                                if ($product->number_of_reviewers > 0) {
                                    $point = round($product->total_star / $product->number_of_reviewers);
                                } else {
                                    $point = -1;
                                }
                                ?>
                                <ul class="rating rating-with-review-item">
                                    @lang('Evaluate') :
                                    @if ($point == -1)
                                        <li style="color: #a4a4a4;
                                font-size: 13px;
                                text-transform: capitalize;
                                transition: all 0.3s ease-in-out;">@lang('Not Yet Rated')</li>
                                    @else
                                        @for ($i = 1; $i <= 5; $i++)
                                            <li class="{{ $i <= $point ? '' : 'no-star' }}">
                                                <i class="fa fa-star" style="font-size: medium"></i>
                                            </li>
                                        @endfor
                                    @endif
                                </ul>
                            </div>
                            <ul>
                                @foreach ($product->productAttributeValue as $attribute)
                                    <li class="pt-5">{{ $attribute->attribute->name }}: {{ $attribute->value }}</li>
                                @endforeach
                            </ul>
                            <div class="price-box pt-20">
                                @if ($product->sale > 0)
                                    <div class="new-price new-price-2" style="padding-top: 6px; color: black">
                                        @lang('Discount') {{ $product->sale }}% @lang('only'):
                                        <span style="color: red">{{ number_format(($product->price * (100 - $product->sale)) / 100, 0, ',', '.') }}@lang('VND')</span>
                                        <span class="new-price" style="color: black; text-decoration: line-through; font-size: 15px;">
                                            {{ number_format($product->price, 0, ',', '.') }}@lang('VND')
                                        </span>
                                    </div>
                                @else
                                    <span class="new-price">{{ number_format($product->price, 0, ',', '.') }} @lang('VND')</span>
                                @endif

                            </div>
                            <div class="product-desc">
                                {{-- <p>
                                <span>{{$product->pro_description}}
                                </span>
                            </p> --}}
                            </div>
                            <div class="single-add-to-cart">
                                <div style="display: flex">
                                    <div class="hm-wishlist mr-3">
                                        <a class="button_add_favorite_product" id="hm-wishlist_by_me"
                                           data-product-name="{{ $product->name }}"
                                           href="{{ route('favorite-product.get.add', $product->id) }}">
                                            <i class="fa fa-heart-o" id="add_to_wishlist_by_me"></i>
                                            <style>
                                                #hm-wishlist_by_me {
                                                    background-color: #fed700;
                                                    color: black;
                                                }

                                                #hm-wishlist_by_me:hover {
                                                    background-color: #242424;
                                                    color: white;
                                                }
                                            </style>
                                        </a>
                                    </div>

                                    <div class="cart-quantity">
                                        <a href="{{ route('shopping.add.product', $product->id) }}"
                                           data-product-name="{{ $product->name }}" class="button_add_cart">
                                            <button class="add-to-cart" type="submit">@lang('Buy product')</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="product-additional-info">
                            <div class="product-social-sharing">
                                <ul>
                                    <li class="facebook"><a href="#"><i class="fa fa-facebook"></i>Facebook</a></li>
                                    <li class="twitter"><a href="#"><i class="fa fa-twitter"></i>Twitter</a></li>
                                    <li class="google-plus"><a href="#"><i class="fa fa-google-plus"></i>Google +</a></li>
                                    <li class="instagram"><a href="#"><i class="fa fa-instagram"></i>Instagram</a></li>
                                </ul>
                            </div>
                        </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wraper end -->
    <!-- Begin Product Area -->
    <div class="product-area pt-40">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="li-product-tab">
                        <ul class="nav li-product-menu">
                            <li><a class="active" data-toggle="tab"
                                   href="#description"><span>@lang('Information detail')</span></a></li>
                            {{-- <li><a data-toggle="tab" href="#product-details"><span>Chi tiết sản phẩm</span></a></li> --}}
                            <li><a data-toggle="tab" href="#information-product"><span>@lang('Thông số')</span></a></li>
                            <li><a data-toggle="tab" href="#reviews"><span>@lang('Evaluate')</span></a></li>
                        </ul>
                    </div>
                    <!-- Begin Li's Tab Menu Content Area -->
                </div>
            </div>
            <div class="tab-content">
                <div id="description" class="tab-pane active show" role="tabpanel">
                    <div class="product-description">
                        <span>{!! $product->content ?? 'Thông tin chi tiết sản phẩm đang được cập nhật ...' !!}</span>
                    </div>
                </div>
                <div id="information-product" class="tab-pane" role="tabpanel">
                    <div class="product-description">
                        <span>{!! $product->information ?? 'Thông số sản phẩm đang được cập nhật ...' !!}</span>
                    </div>
                </div>
                {{-- <div id="product-details" class="tab-pane" role="tabpanel">
                <div class="product-details-manufacturer">
                    <ul>
                        @foreach ($product->ProductAndAttributeValue as $attribute)
                            <li class="pt-5">{{$attribute->Attribute->at_name}}: {{$attribute->atv_value}}</li>
                        @endforeach
                    </ul>
                </div>
            </div> --}}
                <div id="reviews" class="tab-pane" role="tabpanel">
                    {{-- start my custom rating --}}
                    <div class="component_rating" style="margin-bottom:20px;margin-top: 25px;">
                        <div class="component_rating_content" style="display:flex; align-items: center; border-radius:5px; border:1px solid #dedede">
                            {{-- /// --}}
                            <div class="rating_item" style="width:25%;position:relative">
                                <span class="fa fa-star" style="font-size:100px;color:#ff9705;margin:0 auto; text-align:center;display:block"></span>
                                @if ($product->number_of_reviewers > 0)
                                    <b style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%); color:white;font-size:25px">{{ round($product->total_star / $product->number_of_reviewers, 1) }}</b>
                                @else
                                    <b style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%); color:white;font-size:25px">0</b>
                                @endif
                            </div>
                            <div class="list_rating" style="width:50%;padding:20px">
                                @for ($i = 5; $i >= 1; $i--)
                                    <div class="item_rating" style="display:flex; align-items: center;">
                                        <div style="width:10%;font-size:14px">
                                            <span>
                                                {{ $i }} <div class="fa fa-star" style="width: 30%"></div>
                                            </span>
                                        </div>
                                        <div style="width:60%;margin:0 20px">
                                            @foreach ($eachstar as $key => $value)
                                                @if ($key == $i)
                                                    @if ($product->total_star > 0)
                                                        <span style="width:100%;height:8px;display:block;border: 1px solid #dedede;border-radius:5px;background-color:#dedede"><b style="width:{{ ($value / $product->number_of_reviewers) * 100 }}%;background-color:#f25800;display:block;height:100%;border-radius:5px;"></b></span>
                                                    @else
                                                        <span style="width:100%;height:8px;display:block;border: 1px solid #dedede;border-radius:5px;background-color:#dedede"><b style="width:0%;background-color:#f25800;display:block;height:100%;border-radius:5px;"></b></span>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                        <div style="width:30%">
                                            @foreach ($eachstar as $key => $value)
                                                @if ($key == $i)
                                                    @if ($product->total_star > 0)
                                                        <span>{{ $value }} @lang('Evaluate')({{ round(($value / $product->number_of_reviewers) * 100, 2) }}%)</span>
                                                    @else
                                                        <a href="#">{{ $value }} @lang('Evaluate')</a>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endfor
                            </div>
                            <div style="width:25%">
                                @if (Auth::check())
                                    <a href="#" class="btn btn-primary js_rating_action"> @lang('Submit your review') </a>
                                @else
                                    {{-- <a href="#" class="btn btn-primary js_rating_action"> Gửi đánh giá của bạn </a> --}}
                                    <div style="text-align: center"><b>@lang('To rate the product you need')</b>
                                        <a href="{{ route('get.login') }}" class="btn btn-primary"> @lang('Login') </a>
                                    </div>
                                @endif
                            </div>
                            {{-- /// --}}
                        </div>
                        {{-- form rating --}}
                        <div class="form_rating d-none">
                            <div style="display: flex; margin-top:20px">
                                <p style="margin-bottom:0px">@lang('Choose your rating'):</p>
                                <span style="margin: 0 15px;" class="list_start">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star" data-key="{{ $i }}"></i>
                                    @endfor
                                </span>
                                <span class="list_text">@lang('Good')</span>
                                <input type="hidden" value="" class="number_rating"/>
                            </div>
                            <div style="margin-top:15px">
                                <textarea class="form-control" cols="30" rows="3" id="content_rating"></textarea>
                            </div>
                            <div style="margin-top:15px">
                                <a href="{{ route('post.rating.product', $product->id) }}" class="btn btn-primary js_rating_product_button">
                                    @lang('Submit your review')
                                </a>
                            </div>
                        </div>
                        {{-- end form rating --}}
                    </div>
                    {{-- end my custom rating --}}
                    {{-- show đánh giá --}}
                    <div style="margin-top:30px; padding: 0px 25px ">
                        @if (isset($ratings))
                            @foreach ($ratings as $rating)
                                <div style="margintop:10px;">
                                    <div class="pro-rating">
                                        <span><b>{{ optional($rating->user)->name }}</b></span> - @lang('Evaluate')
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fa fa-star {{ $i <= $rating->number ? 'active' : '' }}"></i>
                                        @endfor
                                        <a href="#" style="color:#1cc88a">
                                            <i class="fa fa-check-circle-o"></i>
                                            @lang('Purchased') -
                                        </a>
                                        <span class="list-rating">
                                            <i class="fa fa-clock-o"></i>
                                            <input type="hidden" class="convert-time" value="{{ date('Y-m-d h:i:s A', strtotime($rating->created_at ?? '')) }}">
                                            {{ $rating->created_at }}
                                        </span>
                                        @if (Auth::check())
                                            @if (Auth::user()->id == $rating->user_id)
                                                <span style="float: right">
                                                    <a href="{{ route('get.delete.rating.product', $rating->id) }}" class="btn btn-danger btn_delete_rating">
                                                        @lang('Delete')
                                                    </a>
                                                </span>
                                                <span style="clear: both"></span>
                                            @endif
                                        @endif
                                    </div>
                                    <div style="margin-top:5px;padding-left: 15px">
                                        <span>{{ $rating->content }}</span>
                                    </div>
                                </div>
                                <hr style="margin: 15px 0;"/>
                            @endforeach
                        @endif
                    </div>
                    {{-- end show đánh giá --}}
                </div>
            </div>
        </div>
    </div>
    <div class="product-area pt-40">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="li-product-tab">
                        <ul class="nav li-product-menu">
                            <li><a class="active" data-toggle="tab"
                                   href="#list-product-in-category"><span>@lang('Similar products')</span></a></li>
                            {{-- <li><a data-toggle="tab" href="#product-details"><span>Chi tiết sản phẩm</span></a></li> --}}
{{--                            <li><a data-toggle="tab" href="#list-product-in-viewer"><span>@lang('list-product-in-viewer')</span></a></li>--}}
                        </ul>
                    </div>
                    <!-- Begin Li's Tab Menu Content Area -->
                </div>
            </div>
            <div class="tab-content">
                <div id="list-product-in-category" class="tab-pane active show" role="tabpanel">
                    <div class="product-description">
                        <div class="row">
                            <div class="special-product-active owl-carousel">
                                @foreach($product_in_category_ids as $product_in_category_id)
                                    <div class="col-lg-12">
                                        <!-- single-product-wrap start -->
                                        <div class="single-product-wrap">
                                            <div class="product-image">
                                                <a href="{{ route('product.index', [$product_in_category_id->slug, $product_in_category_id->id]) }}">
                                                    @if (isset($product_in_category_id->image))
                                                        <img src="{{ asset($product_in_category_id->image) }}" alt="Li's Product Image">
                                                    @else
                                                        <img src="{{ asset('noimg.png') }}" alt="Li's Product Image">
                                                    @endif
                                                </a>
                                                <span>
                                                        @if ($product_in_category_id->quantity > 10)
                                                        <b style="color: #3d3de3;">@lang('Stocking')</b>
                                                    @elseif($product_in_category_id->quantity <= 10 && $product_in_category_id->quantity > 0)
                                                        <b style="color: #bfbf50;">@lang('Almost out of stock')</b>
                                                    @elseif($product_in_category_id->quantity <= 0)
                                                        <b style="color: red;">@lang('Out of stock')</b>
{{--                                                    @else--}}
{{--                                                        <b>@lang('Unknown')</b>--}}
                                                    @endif
                                                    </span>
                                                @if ($product_in_category_id->hot == 'yes')
                                                    <span class="sticker">Hot</span>
                                                @endif
                                            </div>
                                            <div class="product_desc">
                                                <div class="product_desc_info">
                                                    <div class="product-review">
                                                        <h5 class="manufacturer">
                                                            {{-- <a href="shop-left-sidebar.html">Số lượng: {{$prn->pro_number}}</a> --}}
                                                            <div class="rating-box">
                                                                    <?php
                                                                    $point = 0;
                                                                    if ($product_in_category_id->number_of_reviewers > 0) {
                                                                        $point_product_new = round($product_in_category_id->total_star / $product_in_category_id->number_of_reviewers);
                                                                    } else {
                                                                        $point_product_new = -1;
                                                                    }
                                                                    ?>
                                                                <ul class="rating">
                                                                    @if ($point_product_new == -1)
                                                                        <li style="color: #a4a4a4;
                                                                    font-size: 13px;
                                                                    text-transform: capitalize;
                                                                    transition: all 0.3s ease-in-out;">
                                                                            @lang('Not Yet Rated')
                                                                        </li>
                                                                    @else
                                                                        @lang('Evaluate'):
                                                                        @for ($i = 1; $i <= 5; $i++)
                                                                            <li
                                                                                class="{{ $i <= $point_product_new ? '' : 'no-star' }}">
                                                                                <i class="fa fa-star"></i>
                                                                            </li>
                                                                        @endfor
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </h5>

                                                    </div>
                                                    <h4><a class="product_name"
                                                           href="{{ route('product.index', [$product_in_category_id->slug, $product_in_category_id->id]) }}">{{ $product_in_category_id->name }}</a>
                                                    </h4>

                                                    <div class="price-box">
                                                        @if ($product_in_category_id->sale > 0)
                                                            <span class="new-price new-price-2">{{ number_format(($product_in_category_id->price * (100 - $product_in_category_id->sale)) / 100, 0, ',', '.') }}
                                                                @lang('VND')
                                                                </span>
                                                            <span class="discount-percentage">-{{ $product_in_category_id->sale }}%</span>
                                                            <br />
                                                            <div class="old-price" style="padding-top: 6px">
                                                                {{ number_format($product_in_category_id->price, 0, ',', '.') }}
                                                                @lang('VND')
                                                            </div>
                                                        @else
                                                            <span class="new-price">{{ number_format($product_in_category_id->price, 0, ',', '.') }}
                                                                @lang('VND')
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="add-actions">
                                                    <ul class="add-actions-link">
                                                        <li class="add-cart active">
                                                            <a class="button_add_cart" data-product-name="{{ $product_in_category_id->name }}" href="{{ route('shopping.add.product', $product_in_category_id->id) }}">
                                                                @lang('Buy product')
                                                            </a>
                                                        </li>
                                                        <li><a class="links-details button_add_favorite_product"
                                                               data-product-name="{{ $product_in_category_id->name }}"
                                                               href="{{ route('favorite-product.get.add', $product_in_category_id->id) }}"><i
                                                                    class="fa fa-heart-o"></i></a></li>
                                                        <li>
                                                            <a href="{{ route('product.index', [$product_in_category_id->slug, $product_in_category_id->id]) }}"
                                                               title="quick view" class="quick-view-btn"><i
                                                                    class="fa fa-eye"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- single-product-wrap end -->
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Product Area End Here -->
    <!-- Begin Li's Laptop Product Area -->

    <!-- Li's Laptop Product Area End Here -->
@endsection
@section('javascript')
    <script>
        $(document).ready(function () {
            $(".button_add_favorite_product").click(function (event) {
                event.preventDefault();
                name_product = $(this).attr("data-product-name");
                url = $(this).attr("href");
                $.ajax({
                    method: "GET",
                    url: url
                }).done(function (result) {
                    if (result.status == 1) {
                        swal("Thành công !", "Đã thêm sản phẩm " + name_product +
                            " vào sản phẩm yêu thích của bạn!", "success");
                        $(".wishlist-item-count-custom").text(result.number_favorite_product);
                    }
                    if (result.status == 0) {
                        swal("Có thể bạn chưa biết !", "Sản phẩm " + name_product +
                            " đã tồn tại trong danh sách sản phẩm ưa thích của bạn !", "info");
                    }
                    if (result.error) {
                        swal("Cảnh báo !", "Bạn cần đăng nhập cho chức năng này!", "warning");
                    }
                })
            })

            $(".button_add_cart").click(function (event) {
                event.preventDefault();
                url = $(".button_add_cart").attr("href");
                name_product = $(".button_add_cart").attr("data-product-name");
                $.ajax({
                    method: "GET",
                    url: url
                }).done(function (result) {
                    switch (result.status) {
                        case 1:
                            swal("Thành công !", "Đã thêm sản phẩm " + name_product + " vào giỏ hàng !",
                                "success");
                            $(".cart-item-count-number").text(result.number_product_in_cart);
                            $(".price_total_cart").text(result.price_total_cart);
                            break;
                        case 2:
                            swal("Cảnh báo !", "Trong kho chỉ còn " + result.product_less +
                                " sản phẩm " + name_product, "warning");
                            break;
                        case 3:
                            swal("Cảnh báo !", "Sản phẩm " + name_product + " không tồn tại !",
                                "warning");
                            break;
                        case 4:
                            swal("Cảnh báo !", "Sản phẩm " + name_product + " đã hết hàng !",
                                "warning");
                            break;
                        default:
                            swal("Cảnh báo !", "Bạn cần đăng nhập cho chức năng này!", "warning");
                    }
                })
            })

            $('.list-rating').find('.convert-time').each(function () {
                var a = moment.tz($('.convert-time').val(), Intl.DateTimeFormat().resolvedOptions().timeZone)
                console.log(a)
                $('.list-rating').html('<i class="fa fa-clock-o"></i>' + a.format('YYYY-MM-DD HH:mm:ss'))
            });

            //list number equal text rate
            listRatingText = {
                1: "Không thích",
                2: "Tạm được",
                3: "Bình thường",
                4: "Tốt",
                5: "Tuyệt vời",
            }
            // event mouse over rate
            $(".list_start .fa").mouseover(function () {
                // get object now
                let $this = $(this);
                // get number rate
                let number = $this.attr("data-key");
                // reset star active
                $(".list_start .fa").removeClass('rating_active');
                //Enter number rate in textbox
                $(".number_rating").val(number);
                //active star
                $.each(
                    $(".list_start .fa"),
                    function (key, value) {
                        if (key + 1 <= number) {
                            $(this).addClass('rating_active')
                        }
                    }
                );
                // show text rate
                $(".list_text").text('').text(listRatingText[$this.attr("data-key")]).show();
                console.log($this.attr("data-key"));
            });
            //hide and show form rating
            $(".js_rating_action").click(function (event) {
                event.preventDefault();
                if ($(".form_rating").hasClass('d-none')) {
                    $(".form_rating").removeClass('d-none');
                    $(".js_rating_action").text("").text("Hủy đánh giá");
                } else {
                    $(".form_rating").addClass('d-none');
                    $(".js_rating_action").text("").text("Gửi đánh giá của bạn");
                }
            });
            $(".js_rating_product_button").click(function (event) {
                event.preventDefault()
                var number = $(".number_rating").val()
                if (!number) {
                    swal("Lỗi xảy ra", "Bạn cần chọn sao đánh giá sản phẩm !", "error")
                }
                var content = $("#content_rating").val()
                var url = $(this).attr("href")
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        number: number,
                        content_rating: content
                    }
                }).done(function (result) {
                    switch (result.code) {
                        case 1:
                            swal("Thành công!", "Bạn đã gửi đánh giá sản phẩm thành công", "success").then(
                                function () {
                                    location.reload();
                            });
                            break;
                        case 2:
                            swal("Thất bại!", "Bạn đã gửi đánh giá sản phẩm này rồi", "error");
                            break;
                        case 3:
                            swal("Có thể bạn chưa biết !", "Bạn chưa được phép đánh giá sản phẩm này", "info");
                            break;
                        default:
                            swal("Thất bại!", "Có lỗi xảy ra", "error");
                    }
                });
            })

            $(".btn_delete_rating").click(function (e) {
                e.preventDefault();
                url = $(this).attr('href');
                swal({
                    title: "Bạn có chắc chắn ?",
                    text: "Bạn có chắc chắn xóa đánh giá của bạn khỏi sản phẩm này !",
                    icon: "info",
                    buttons: ["Không", "Có"],
                    dangerMode: true
                }).then((willdelete) => {
                    if (willdelete) {
                        swal("Thành công", "Hệ thống chuẩn bị xóa đánh giá của bạn khỏi sản phẩm này !",
                            'success').then(function () {
                            window.location.href = url;
                        });
                    }
                });
            });
        })
    </script>
@endsection
