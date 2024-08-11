@extends('page_layout')
@section('content')
    <?php use App\Http\Controllers\ProductController; ?>

    <div class="slider-area">
        <div class="swiper-container slider-active">
            <div class="swiper-wrapper">
                <!--Single Slider Start-->
                <div class="single-slider swiper-slide animation-style-01" {{-- style="background-image: url('/page/images/KIDOLBanner.png');"> --}}
                    style="background-image: url('/page/images/banner/banner-4.jpg');">
                    <div class="container">
                        <div class="slider-content">

                            <h2 class="main-title">Ngày Ngày Siêu Sale Sập Sàn</h2>
                            <p>Nhập: <span class="text-primary">KM50</span> để được giảm 50%, thời gian có hạn!</p>

                            <ul class="slider-btn">
                                <li><a href="{{ URL::to('/store') }}" class="btn btn-round btn-primary">Bắt đầu mua sắm</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--Single Slider End-->

                <!--Single Slider Start-->
                <div class="single-slider swiper-slide animation-style-01"
                    style="background-image: url('/page/images/KIDOLBanner.png');">
                    <div class="container" style="text-align:right;">
                        <div class="slider-content">

                            <h2 class="main-title">Ngày Siêu Sale Sập Sàn</h2>
                            <p>Nhập: <span class="text-info">KM50</span> để được giảm 50%, thời gian có hạn!</p>

                            <ul class="slider-btn">
                                <li><a href="{{ URL::to('/store') }}" class="btn btn-round btn-primary">Bắt đầu mua sắm</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--Single Slider End-->
            </div>
            <!--Swiper Wrapper End-->

            <!-- Add Arrows -->
            <div class="swiper-next"><i class="fa fa-angle-right"></i></div>
            <div class="swiper-prev"><i class="fa fa-angle-left"></i></div>

            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>

        </div>
        <!--Swiper Container End-->
    </div>
    <!--Slider End-->







    <!--New Product Start-->
    <div class="new-product-area section-padding-2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-9 col-sm-11">
                    <div class="section-title text-center">
                        <h2 class="title">Sản Phẩm Mới</h2>

                    </div>
                </div>
            </div>
            <div class="product-wrapper">
                <div class="row">
                    @foreach ($newestProducts as $product)
                       
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="single-product">
                                <div class="product-image">
                                    <?php $images = json_decode($product->ImageName); ?>
                                    @if ($images && isset($images[0]))
                                        <a href="{{ URL::to('/shop-single/' . $product->idProduct) }}">
                                            <img src="{{ asset('storage/kidadmin/images/product/' . $images[0]) }}"
                                                alt="{{ $product->ProductName }}">
                                        </a>
                                    @else
                                        <a href="{{ URL::to('/shop-single/' . $product->idProduct) }}">
                                            <img src="{{ asset('path/to/default-image.jpg') }}" alt="No image available">
                                        </a>
                                    @endif


                                    <?php
                                    $isInWishlist = in_array($product->idProduct, $wishlistProducts);
                                    ?>

                                    <div class="action-links">
                                        <ul>

                                            <li>
                                                <a class="add-to-wishlist {{ $isInWishlist ? 'in-wishlist' : 'not-in-wishlist' }}"
                                                    data-id="{{ $product->idProduct }}" data-tooltip="tooltip"
                                                    data-placement="left" title="Thêm vào danh sách yêu thích">
                                                    <i class="fa fa-heart"></i>
                                                </a>

                                            </li>

                                        </ul>
                                    </div>
                                </div>
                                <div class="product-content text-center">

                                    <h4 class="product-name"><a
                                            href="{{ URL::to('/shop-single/' . $product->idProduct) }}">{{ $product->ProductName }}</a>
                                    </h4>
                                    <div class="price-box">
                                        <span
                                            class="current-price">{{ number_format($product->Price, 0, ',', '.') }}đ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Add Arrows -->
                <div class="swiper-next"><i class="fa fa-angle-right"></i></div>
                <div class="swiper-prev"><i class="fa fa-angle-left"></i></div>
            </div>
        </div>
    </div>
    </div>
    <!--New Product End-->




    <div class="new-product-area section-padding-2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-9 col-sm-11">
                    <div class="section-title text-center">
                        <h2 class="title">Sản Phẩm đã xem</h2>
                    </div>
                </div>
            </div>
            <div class="product-wrapper">
                <div class="swiper-container product-active">
                    <div class="swiper-wrapper">
                        @php
                            $uniqueProducts = $recentlyViewedProducts->unique('idProduct'); // Đảm bảo loại bỏ sản phẩm trùng lặp
                        @endphp
                        @foreach ($uniqueProducts as $product)
                            <div class="swiper-slide">
                                <div class="single-product">
                                    <div class="product-image">
                                        @php
                                            $images = json_decode($product->ImageName);
                                        @endphp
                                        @if ($images && isset($images[0]))
                                            <a href="{{ URL::to('/shop-single/' . $product->idProduct) }}">
                                                <img src="{{ asset('storage/kidadmin/images/product/' . $images[0]) }}"
                                                    alt="{{ $product->ProductName }}">
                                            </a>
                                        @else
                                            <a href="{{ URL::to('/shop-single/' . $product->idProduct) }}">
                                                <img src="{{ asset('path/to/default-image.jpg') }}"
                                                    alt="No image available">
                                            </a>
                                        @endif

                                        @php
                                            $isInWishlist = in_array($product->idProduct, $wishlistProducts);
                                        @endphp

                                        <div class="action-links">
                                            <ul>
                                                <li>
                                                    <a class="add-to-wishlist {{ $isInWishlist ? 'in-wishlist' : 'not-in-wishlist' }}"
                                                        data-id="{{ $product->idProduct }}" data-tooltip="tooltip"
                                                        data-placement="left" title="Thêm vào danh sách yêu thích">
                                                        <i class="fa fa-heart"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-content text-center">
                                        <h4 class="product-name"><a
                                                href="{{ URL::to('/shop-single/' . $product->idProduct) }}">{{ $product->ProductName }}</a>
                                        </h4>
                                        <div class="price-box">
                                            <span
                                                class="current-price">{{ number_format($product->Price, 0, ',', '.') }}đ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
