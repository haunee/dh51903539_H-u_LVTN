@extends('page_layout')
@section('content')

<!--Page Banner Start-->
<div class="page-banner" style="background-image: url(/page/images/banner/banner5.jpg);">
    <div class="container">
        <div class="page-banner-content text-center">
            <h2 class="title">Thanh toán</h2>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="{{URL::to('/home')}}">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
            </ol>
        </div>
    </div>
</div>
<!--Page Banner End-->

<!--Empty Cart Start-->
<div class="empty-cart-page section-padding-5">
    <div class="container">
        <div class="empty-cart-content text-center d-flex flex-column align-items-center">
            <h1>Đặt hàng thành công</h1>
            <div class="empty-cart-img">
                <i class="fa fa-check-circle text-primary" style="font-size:100px;"></i>
            </div>
           
          
            <a href="{{URL::to('/ordered')}}" class="btn btn-primary"></i> Xem đơn đã đặt</a>
        </div>
    </div>
</div>
<!--Empty Cart End-->

@endsection