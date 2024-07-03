@extends('page_layout')
@section('content')
    <?php use App\Http\Controllers\ProductController; ?>

    <div class="slider-area">
        <div class="swiper-container slider-active">
            <div class="swiper-wrapper">
                <!--Single Slider Start-->
                <div class="single-slider swiper-slide animation-style-01"
                    style="background-image: url('/page/images/KIDOLBanner.png');">
                    <div class="container">
                        <div class="slider-content">
                            <h5 class="sub-title">Nhập: <span class="text-primary">SALE100K</span> <br> Giảm 100K cho mọi đơn
                                hàng</h5>
                            <h2 class="main-title">Ngày đặc biệt!</h2>
                            <p>Nhập: <span class="text-primary">SALE10</span> để được giảm 10%, số lượng có hạn!</p>

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
                    style="background-image: url('/page/images/KIDOLBanner2.png');">
                    <div class="container" style="text-align:right;">
                        <div class="slider-content">
                            <h5 class="sub-title sub-title-right">Nhập: <span class="text-info">SALE100K</span> <br> Giảm
                                100K cho mọi đơn hàng</h5>
                            <h2 class="main-title">Ngày đặc biệt!</h2>
                            <p>Nhập: <span class="text-info">SALE10</span> để được giảm 10%, số lượng có hạn!</p>

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

    <div class="shipping-area section-padding-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="single-shipping">
                        <div class="shipping-icon">
                            <img src="/page/images/shipping-icon/Free-Delivery.png" alt="">
                        </div>
                        <div class="shipping-content">
                            <h5 class="title">Miễn Phí Vận Chuyển</h5>
                            <p>Giao hàng miễn phí cho tất cả các đơn đặt hàng trên 1.000.000đ</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single-shipping">
                        <div class="shipping-icon">
                            <img src="/page/images/shipping-icon/Online-Order.png" alt="">
                        </div>
                        <div class="shipping-content">
                            <h5 class="title">Đặt Hàng Online</h5>
                            <p>Đừng lo lắng, bạn có thể đặt hàng Trực tuyến trên Trang web của chúng tôi</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single-shipping">
                        <div class="shipping-icon">
                            <img src="/page/images/shipping-icon/Freshness.png" alt="">
                        </div>
                        <div class="shipping-content">
                            <h5 class="title">Hiện Đại</h5>
                            <p>Cập nhật những sản phẩm mới nhất</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single-shipping">
                        <div class="shipping-icon">
                            <img src="/page/images/shipping-icon/Made-By-Artists.png" alt="">
                        </div>
                        <div class="shipping-content">
                            <h5 class="title">Hỗ Trợ 24/7</h5>
                            <p>Đội ngũ hỗ trợ trưc tuyến chuyên nghiệp</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Recommend Product Start -->
    <div class="new-product-area section-padding-2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-9 col-sm-11">
                    <div class="section-title text-center">
                        <h2 class="title">Gợi Ý Cho Bạn</h2>
                        <p>A perfect blend of creativity, energy, communication, happiness and love. Let us arrange a smile
                            for you.</p>
                    </div>
                </div>
            </div>
            <div class="product-wrapper">
                <div class="swiper-container product-active">
                    <div class="swiper-wrapper">
                        @foreach ($recommend_pds as $product)
                            <div class="swiper-slide">
                                <div class="single-product">
                                    <div class="product-image">
                                        <?php $image = json_decode($product->ImageName)[0]; ?>
                                        <a href="{{ URL::to('/shop-single/' . $product->idProduct) }}">
                                            <img src="{{ asset('/storage/kidadmin/images/product/' . $image) }}"
                                                alt="{{ $product->ProductName }}">
                                        </a>

                                        <?php
                                        $isInWishlist = in_array($product->idProduct, $wishlistProducts);
                                        ?>



                                        <div class="action-links">
                                            <ul>
                                                <li>
                                                    <a class="add-to-wishlist {{ $isInWishlist ? 'in-wishlist' : '' }}" 
                                           data-id="{{ $product->idProduct }}" data-tooltip="tooltip" 
                                           data-placement="left" title="Thêm vào danh sách yêu thích">
                                            <i class="icon-heart"></i>
                                        </a>
                                                </li>

                                                <li><a class="quick-view-pd" data-id="{{ $product->idProduct }}"
                                                        data-tooltip="tooltip" data-placement="left" title="Xem nhanh"><i
                                                            class="icon-eye"></i></a></li>
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
    <!-- Recommend Product End -->



    <!--New Product Start-->
    <div class="new-product-area section-padding-2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-9 col-sm-11">
                    <div class="section-title text-center">
                        <h2 class="title">Sản Phẩm Mới</h2>
                        <p>A perfect blend of creativity, energy, communication, happiness and love. Let us arrange a smile
                            for you.</p>
                    </div>
                </div>
            </div>
            <div class="product-wrapper">
                <div class="swiper-container product-active">
                    <div class="swiper-wrapper">
                        @foreach ($newestProducts as $product)
                            <div class="swiper-slide">
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
                                                <img src="{{ asset('path/to/default-image.jpg') }}"
                                                    alt="No image available">
                                            </a>
                                        @endif


                                        <?php
                                        $isInWishlist = in_array($product->idProduct, $wishlistProducts);
                                        ?>

                                        <div class="action-links">
                                            <ul>

                                                <li>
                                                    <a class="add-to-wishlist {{ $isInWishlist ? 'in-wishlist' : '' }}" 
                                                    data-id="{{ $product->idProduct }}" data-tooltip="tooltip" 
                                                    data-placement="left" title="Thêm vào danh sách yêu thích">
                                                     <i class="icon-heart"></i>
                                                 </a>

                                                </li>
                                                <li><a class="quick-view-pd" data-id="{{ $product->idProduct }}"
                                                        data-tooltip="tooltip" data-placement="left" title="Xem nhanh"><i
                                                            class="icon-eye"></i></a></li>
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
    <!--New Product Start-->
    {{-- <div class="section-title text-center">
        <h2 class="title">Sản Phẩm Mới Nhất</h2>
        <p>A perfect blend of creativity, energy, communication, happiness and love. Let us arrange a smile for you.</p>
    </div>

    <div class="row justify-content-center">
        @foreach ($newestProducts as $product)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="single-product text-center mb-4">
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

                        <div class="action-links">
                            <ul>
                                <li><a class="add-to-compare" data-idcat="{{ $product->idCategory }}"
                                        id="{{ $product->idProduct }}" data-tooltip="tooltip" data-placement="left"
                                        title="So sánh"><i class="icon-sliders"></i></a></li>
                                <li><a class="add-to-wishlist" data-id="{{ $product->idProduct }}"
                                        data-tooltip="tooltip" data-placement="left"
                                        title="Thêm vào danh sách yêu thích"><i class="icon-heart"></i></a></li>
                                <li><a class="quick-view-pd" data-id="{{ $product->idProduct }}" data-tooltip="tooltip"
                                        data-placement="left" title="Xem nhanh"><i class="icon-eye"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-content">
                        <h4 class="product-name"><a
                                href="{{ URL::to('/shop-single/' . $product->idProduct) }}">{{ $product->ProductName }}</a>
                        </h4>
                        <div class="price-box">
                            <span class="current-price">{{ number_format($product->Price, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div> --}}


    <!--About Start-->
    <div class="about-area section-padding-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-image">
                        <img src="/page/images/banner/banner_home.png" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content">
                        <h2 class="title">Cùng bé đón gió mùa về với những ưu đãi hấp dẫn.</h2>
                        <p>Các mã giảm giá hiện có trên cửa hàng:</p>
                        <ul>
                            <li> SALE100K: Giảm 100K trên tổng giá trị đơn hàng. </li>
                            <li> SALE10: Giảm 10% trên tổng giá trị đơn hàng. </li>
                        </ul>
                        <div class="about-btn">
                            <a href="{{ URL::to('/store') }}" class="btn btn-primary btn-round">Mua Ngay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--About End-->
@endsection
