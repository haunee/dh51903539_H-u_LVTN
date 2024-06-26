@extends('page_layout')
@section('content')
    <?php use App\Http\Controllers\ProductController; ?>
    <div class="single-slider swiper-slide animation-style-01" style="background-image: url('/page/images/KIDOLBanner.png');">
    </div>

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






<!--New Product Start-->
<div class="section-title text-center">
    <h2 class="title">Sản Phẩm Mới Nhất</h2>
    <p>A perfect blend of creativity, energy, communication, happiness and love. Let us arrange a smile for you.</p>
</div>

<div class="row justify-content-center">
    @foreach($newestProducts as $product)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="single-product text-center mb-4">
                <div class="product-image">
                    <?php $images = json_decode($product->ImageName); ?>
                    @if($images && isset($images[0]))
                        <a href="{{ URL::to('/shop-single/'.$product->idProduct) }}">
                            <img src="{{ asset('storage/kidadmin/images/product/'.$images[0]) }}" alt="{{ $product->ProductName }}">
                        </a>
                    @else
                        <a href="{{ URL::to('/shop-single/'.$product->idProduct) }}">
                            <img src="{{ asset('path/to/default-image.jpg') }}" alt="No image available">
                        </a>
                    @endif

                    @if($product->QuantityTotal == '0')
                        <span class="sticker-new soldout-title">Hết hàng</span>
                    @endif
                </div>
                <div class="product-content">
                    <h4 class="product-name"><a href="{{ URL::to('/shop-single/'.$product->idProduct) }}">{{ $product->ProductName }}</a></h4>
                    <div class="price-box">
                        <span class="current-price">{{ number_format($product->Price, 0, ',', '.') }}đ</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>


   
@endsection
