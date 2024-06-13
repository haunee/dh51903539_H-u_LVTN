<!doctype html>
<html class="no-js" lang="en">

<head>
    
    <base href="\">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Kid Angel Shop</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('/page/images/favivon.png')}}">

    <!-- CSS
	============================================ -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('/page/css/vendor/bootstrap.min.css')}}">

    <!-- Icon Font CSS -->
    <link rel="stylesheet" href="{{asset('/page/css/vendor/plazaicon.css')}}">
    <link rel="stylesheet" href="{{asset('/page/css/vendor/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('/page/css/vendor/font-awesome.min.css')}}">


    <!-- Main Style CSS -->
    <link rel="stylesheet" href="{{asset('/page/css/style.css')}}">


    <!-- jQuery JS -->
    <script src="{{asset('/page/js/vendor/jquery-3.3.1.min.js')}}" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" 
        crossorigin="anonymous">
    </script>
    <script src="{{asset('/page/js/jquery.preloadinator.min.js')}}"></script>


   

</head>

<body data-aos-easing="ease" data-aos-duration="400" data-aos-delay="0" class="preloader-deactive">
    <div class="main-wrapper">
    
        <div class="preloader js-preloader flex-center">
            <div class="dots">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>

        <!--Header Section Start-->
        <?php 
         
            use Illuminate\Support\Facades\Session;
        ?>
        <input id="quick-view-token" name="_token" type="hidden" value="{{csrf_token()}}">
        <div class="header-section d-none d-lg-block">
            <div class="main-header">
                <div class="container position-relative">
                    <div class="row align-items-center">
                        <div class="col-lg-2">
                            <div class="header-logo">
                                <a href="{{URL::to('/home')}}"><img src="{{asset('/page/images/logo2.png')}}" alt=""></a>
                            </div>
                        </div>
                        <div class="col-lg-7 position-static">
                            <div class="site-main-nav">
                                <nav class="site-nav">
                                    <ul>
                                        <li><a href="{{URL::to('/home')}}">Trang chủ</a></li>
                                        <li>
                                            <a >Cửa hàng </a>

                                            <ul class="mega-sub-menu">
                                                <li class="mega-dropdown">
                                                    <a class="mega-title" >Danh mục</a>
                                                    <ul class="mega-item">
                                                        <li><a >Sản phẩm mới</a></li>
                                                        <li><a >Sản phẩm bán chạy</a></li>
                                                        <li><a >Sản phẩm nổi bật</a></li>
                                                        <li><a >Sản phẩm đang SALE</a></li>
                                                    </ul>   
                                                  
                                                <li class="mega-dropdown">
                                                    <a class="mega-title" >Thương hiệu</a>
                                                    <ul class="mega-item">
                                                        <li><a >Sản phẩm mới</a></li>
                                                        <li><a >Sản phẩm bán chạy</a></li>
                                                        <li><a >Sản phẩm nổi bật</a></li>
                                                        <li><a >Sản phẩm đang SALE</a></li>
                                                    </ul>
                                                    
                                                </li>
                                                <li class="mega-dropdown">
                                                    <a class="mega-title">Danh Mục Khác</a>

                                                    <ul class="mega-item">
                                                        <li><a >Sản phẩm mới</a></li>
                                                        <li><a >Sản phẩm bán chạy</a></li>
                                                        <li><a >Sản phẩm nổi bật</a></li>
                                                        <li><a >Sản phẩm đang SALE</a></li>
                                                    </ul>
                                                </li>
                                                
                                            </ul>
                                        </li>
                                       
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="header-meta-info" style="position:relative;">
                                <div class="header-search">
                                    
                                        <input type="text" name="keyword" id="search-input" placeholder="Tìm kiếm sản phẩm " autocomplete="off">
                                        <button class="search-btn"><i class="icon-search"></i></button>
                                   
                                </div>
                              
                                <div class="header-account">
                                    <div class="header-account-list dropdown top-link">
                                        @if(Session::get('idCustomer'))
                                            @if(Session::get('AvatarCus') != '')
                                            <a href="#" role="button" data-toggle="dropdown"><img style="border-radius:50%;" width="70px" height="24px" src="{{asset('/storage/page/images/customer/'.Session::get('AvatarCus'))}}" alt=""></a>
                                            @else <a href="#" role="button" data-toggle="dropdown"><i class="icon-users"></i></a> @endif
                                        <ul class="dropdown-menu">
                                            <li><a href="{{URL::to('/profile')}}">Tài khoản của tôi</a></li>   
                                            <li><a href="{{URL::to('/logout')}}">Đăng xuất</a></li>
                                        </ul>
                                        <input type="hidden" id="idCustomer" value="{{Session::get('idCustomer')}}">
                                        @else
                                        <a href="#" role="button" data-toggle="dropdown"><i class="icon-users"></i></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{URL::to('/register')}}">Đăng ký</a></li>
                                            <li><a href="{{URL::to('/login')}}">Đăng nhập</a></li>
                                        </ul>
                                        <input type="hidden" id="idCustomer" value="">
                                        @endif
                                    </div>
                                    <div class="header-account-list dropdown mini-cart">
                                            <a href="#" role="button" data-toggle="dropdown">
                                                <i class="icon-shopping-bag"></i>
                                               
                                            </a> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      
        <!--Header Section End-->
        <div class="overlay"></div>
        <!--Overlay-->
        @yield('content') 
             
        <!--Footer Section Start-->
        <div class="footer-area">
            <div class="container">
                <div class="footer-widget-area section-padding-6">
                    <div class="row justify-content-between">

                        <!--Footer Widget Start-->
                        <div class="col-lg-4 col-md-6">
                            <div class="footer-widget">
                              
                           
                                <div class="widget-social">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                        <li><a href="#"><i class="fa fa-youtube"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <!--Footer Widget End-->
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="footer-widget">
                                <h4 class="footer-widget-title">Information</h4>

                                <div class="footer-widget-menu">
                                    <ul>
                                        <li><a href="#">Search Terms</a></li>
                                        <li><a href="#">Advanced Search</a></li>
                                        <li><a href="#">Helps & Faqs</a></li>
                                        <li><a href="#">Store Location</a></li>
                                        <li><a href="#">Orders & Returns</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        

                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="footer-widget">
                                <h4 class="footer-widget-title">Help</h4>

                                <div class="footer-widget-menu">
                                    <ul>
                                        <li><a href="#">FAQ’s</a></li>
                                        <li><a href="#">Pricing Plans</a></li>
                                        <li><a href="#">Track</a></li>
                                        <li><a href="#">Your Order</a></li>
                                        <li><a href="#">Returns</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="footer-widget">
                                <h4 class="footer-widget-title">Customer Service</h4>

                                <div class="footer-widget-menu">
                                    <ul>
                                        <li><a href="{{URL::to('/account')}}">My Account</a></li>
                                        <li><a href="#">Terms of Use</a></li>
                                        <li><a href="#">Deliveries & Returns</a></li>
                                        <li><a href="#">Gift card</a></li>
                                        <li><a href="#">Legal Notice</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--Footer Section End-->

  
    </div>

    <!-- JS
    ============================================ -->
    <script src="{{asset('/page/js/vendor/popper.min.js')}}"></script>

    <script src="{{asset('/page/js/vendor/bootstrap.min.js')}}"></script>
    <script src="{{asset('/page/js/custom.js')}}"></script>
    

    
    <script>
        $('.js-preloader').preloadinator();
        $('.js-preloader').preloadinator({
            scroll: false
        });
        $('.js-preloader').preloadinator({
            minTime: 2000
        });
        $('.js-preloader').preloadinator({
            animation: 'fadeOut',
            animationDuration: 400
        });
        
        $(document).ready(function(){  
            APP_URL = '{{url('/')}}' ;

            $('.select-input__item').on('click',function(){
                var sort_by = $(this).data("sort");
                split_url = url.split("&sort_by");
                if(url.indexOf("?show=all") != -1 || url.indexOf("?keyword") != -1) window.location.href = split_url[0] + sort_by;
                else window.location.href = url + '?show=all' + sort_by;
            });

        });
    </script>
</body>
</html>