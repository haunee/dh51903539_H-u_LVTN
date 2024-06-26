@extends('page_layout')
@section('content')
    <!--Page Banner Start-->
    <div class="page-banner" style="background-image: url(/page/images/banner/banner-shop.png);">
        <div class="container">
            <div class="page-banner-content text-center">
                <h2 class="title">Cửa Hàng</h2>
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cửa Hàng</li>
                </ol>
            </div>
        </div>
    </div>
    <!--Page Banner End-->

    <!-- Shop Start -->
    <div class="shop-page section-padding-6">
        <div class="container">
            <div class="row">
                @foreach ($list_pd as $product)
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="single-product">
                            <div class="product-image">
                                <a href="{{ URL::to('/shop-single/' . $product->idProduct) }}">
                                    <img src="{{ asset('/storage/kidadmin/images/product/' . json_decode($product->ImageName)[0]) }}"
                                        alt="{{ $product->ProductName }}">
                                </a>
                                <div class="product-content text-center">
                                    <h4 class="product-name"><a
                                            href="{{ URL::to('/shop-single/' . $product->idProduct) }}">{{ $product->ProductName }}</a></h4>
                                    <div class="price-box">
                                        <span class="current-price">{{ number_format($product->Price, 0, ',', '.') }}đ</span>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Shop End -->
@endsection
