<!doctype html>
<html class="no-js" lang="en">

<head>

    <base href="\">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>KidAngel Shop</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/page/images/favivon.png') }}">

    <!-- CSS
 ============================================ -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('/page/css/vendor/bootstrap.min.css') }}">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('/page/css/plugins/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('/page/css/plugins/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/page/css/plugins/select2.min.css') }}">
    <!-- Icon Font CSS -->
    <link rel="stylesheet" href="{{ asset('/page/css/vendor/plazaicon.css') }}">
    <link rel="stylesheet" href="{{ asset('/page/css/vendor/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('/page/css/vendor/font-awesome.min.css') }}">

    <!-- Helper CSS -->
    <link rel="stylesheet" href="{{ asset('/page/css/helper.css') }}">
    <link rel="stylesheet" href="{{ asset('/page/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/page/css/responsive.bootstrap.min.css') }}">
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="{{ asset('/page/css/style.css') }}">


    <!-- jQuery JS -->
    <script src="{{ asset('/page/js/vendor/jquery-3.3.1.min.js') }}"
        integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous">
    </script>
    <script src="{{ asset('/page/js/jquery.preloadinator.min.js') }}"></script>




</head>

<body data-aos-easing="ease" data-aos-duration="400" data-aos-delay="0" class="preloader-deactive">
    <div class="main-wrapper">

        <div class="preloader js-preloader flex-center">
            {{-- <div class="dots">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div> --}}
        </div>

        <!--Header Section Start-->
        <?php
        use App\Http\Controllers\CartController;
        use App\Http\Controllers\ProductController;
        use Illuminate\Support\Facades\Session;
        ?>
        <input id="quick-view-token" name="_token" type="hidden" value="{{ csrf_token() }}">
        <div class="header-section d-none d-lg-block">
            <div class="main-header">
                <div class="container position-relative">
                    <div class="row align-items-center">
                        <div class="col-lg-2">
                            <div class="header-logo">
                                <a href="{{ URL::to('/home') }}"><img src="{{ asset('/page/images/logo2.png') }}"
                                        alt=""></a>
                            </div>
                        </div>
                        <div class="col-lg-7 position-static">
                            <div class="site-main-nav">
                                <div class="header-container">
                                    <div class="header-search">
                                        <form type="GET" action="{{ route('search') }}">
                                            <input type="text" name="keyword" id="search-input" placeholder="Tìm kiếm sản phẩm" autocomplete="off">
                                            {{-- <i class="icon-search"></i> --}}
                                        </form>  
                                        
                                    </div>
                                   
                                    <ul class="search-product"></ul>
                                </div>
                                

                             
                                <nav class="site-nav">
                                    
                                
                                    <ul>
                                        <li><a href="{{ URL::to('/home') }}">Trang chủ</a>
                                        
                                        </li>
                                        <li>
                                            <a href="{{ URL::to('/store') }}">Cửa hàng </a>

                                            <ul class="mega-sub-menu">
                                                <li class="mega-dropdown">
                                                    <a class="mega-title">Danh mục</a>
                                                    <ul class="mega-item">
                                                        @foreach ($list_category as $key => $category)
                                                            <li><a
                                                                    href="{{ URL::to('/store?show=all&category=' . $category->idCategory . '&sort_by=new') }}">{{ $category->CategoryName }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>

                                                <li class="mega-dropdown">
                                                    <a class="mega-title">Thương hiệu</a>
                                                    <ul class="mega-item">
                                                        @foreach ($list_brand as $key => $brand)
                                                            <li><a
                                                                    href="{{ URL::to('/store?show=all&brand=' . $brand->idBrand . '&sort_by=new') }}">{{ $brand->BrandName }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>

                                                </li>
                                                <li class="mega-dropdown">
                                                    <a class="mega-title">Danh Mục Khác</a>

                                                    <ul class="mega-item">
                                                        <li><a href="{{ URL::to('/store?show=all&sort_by=new') }}">Sản
                                                                phẩm mới</a></li>
                                                        <li><a
                                                                href="{{ URL::to('/store?show=all&sort_by=bestsellers') }}">Sản
                                                                phẩm bán chạy</a></li>

                                                    </ul>
                                                </li>

                                                <li class="mega-dropdown">
                                                    <a class="menu-banner" href="#">
                                                        <img src="{{ asset('/page/images/logo9.jpg') }}"
                                                            alt="">
                                                    </a>
                                                </li>

                                            </ul>
                                        </li>

                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="header-meta-info" style="position:relative;">
                                <div class="header-search"></div>
                                <div class="header-account">
                                    
                                    <div class="header-account-list dropdown mini-cart">
                                        <?php
                                        $get_cart_header = CartController::get_cart_header();
                                        $sum_cart = $get_cart_header['sum_cart'];
                                        $carts = $get_cart_header['get_cart_header'];
                                        $Total = 0;
                                        ?>
                                        @if ($sum_cart > 0)
                                            <a href="#" role="button" data-toggle="dropdown">
                                                <svg class="w-[30px] h-[30px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10V6a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v4m3-2 .917 11.923A1 1 0 0 1 17.92 21H6.08a1 1 0 0 1-.997-1.077L6 8h12Z"/>
                                                </svg>
                                                <span class="item-count">{{ $sum_cart }}</span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li class="product-cart">
                                                    @foreach ($carts->groupBy('idProduct') as $key => $cartGroup)
                                                        @php
                                                            $cart = $cartGroup->first(); // Lấy sản phẩm đầu tiên trong nhóm
                                                            $Total += $cart->Price * $cart->QuantityBuy;
                                                        @endphp
                                                        <div class="single-cart-box">
                                                            <div class="cart-img">
                                                                <?php $image = json_decode($cart->ImageName)[0]; ?>
                                                                <a
                                                                    href="{{ URL::to('shop-single/' . $cart->idProduct) }}"><img
                                                                        src="{{ asset('/storage/kidadmin/images/product/' . $image) }}"
                                                                        alt=""></a>
                                                                <span
                                                                    class="pro-quantity">{{ $cart->QuantityBuy }}</span>
                                                            </div>
                                                            <div class="cart-content">
                                                                <h6 class="title"><a
                                                                        href="{{ URL::to('shop-single/' . $cart->idProduct) }}">{{ $cart->ProductName }}</a>
                                                                </h6>
                                                                <span class="title"
                                                                    style="font-size:13px;">{{ $cart->AttributeProduct }}</span>
                                                                <div class="cart-price d-flex">
                                                                    <span
                                                                        class="amount">{{ number_format($cart->Price, 0, ',', '.') }}đ</span>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    @endforeach
                                                </li>

                                                <li class="product-btn">
                                                    <a href="{{ URL::to('/cart') }}"
                                                        class="btn btn-dark btn-block">Xem giỏ hàng</a>
                                                </li>
                                            </ul>
                                        @else
                                            <a href="#" role="button" data-toggle="dropdown">
                                                <svg class="w-[30px] h-[30px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10V6a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v4m3-2 .917 11.923A1 1 0 0 1 17.92 21H6.08a1 1 0 0 1-.997-1.077L6 8h12Z"/>
                                                </svg>
                                                  
                                            </a>
                                            <ul class="dropdown-menu" style="height:250px; width:250px;">
                                                <li
                                                    style="height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                                    <img src="{{ asset('/page/images/no_cart.png') }}" alt=""
                                                        style="width: 80%;" class="product-image">
                                                    <span class="mt-10 d-block text-align-center">Giỏ hàng trống</span>
                                                </li>
                                            </ul>
                                        @endif
                                    </div>




                                    <div class="header-account-list dropdown top-link">
                                        @if (Session::get('idCustomer'))
                                            <a href="#" role="button" data-toggle="dropdown" class="dropdown-toggle">
                                                @if (Session::get('AvatarCus') != '')
                                                    <img
                                                        style="border-radius:20%;"
                                                        width="30px"
                                                        height="30px"
                                                        src="{{ asset('/storage/page/images/customer/' . Session::get('AvatarCus')) }}"
                                                        alt=""
                                                    >
                                                @else
                                                    <svg class="w-[30px] h-[30px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                    </svg>
                                                @endif
                                                <span class="username">{{ Session::get('username') }}</span>

                                            </a>

                                            <ul class="dropdown-menu">
                                                <li><a href="{{ URL::to('/profile') }}">Tài khoản của tôi</a></li>
                                                <li><a href="{{ URL::to('/wishlist') }}">Sản phẩm yêu thích</a></li>
                                                <li><a href="{{ URL::to('/ordered') }}">Đơn hàng</a></li>
                                                <li><a href="{{ URL::to('/logout') }}">Đăng xuất</a></li>
                                            </ul>
                                            <input type="hidden" id="idCustomer" value="{{ Session::get('idCustomer') }}">
                                        @else
                                            <a href="#" role="button" data-toggle="dropdown" class="dropdown-toggle">
                                                <svg class="w-[30px] h-[30px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                </svg>
                                              
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{ URL::to('/login') }}">Đăng nhập</a></li>
                                                <li><a href="{{ URL::to('/register') }}">Đăng ký</a></li>
                                            </ul>
                                            <input type="hidden" id="idCustomer" value="">
                                        @endif
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
                                {{-- <a class="footer-logo" href="#"><img
                                        src="{{ asset('/page/images/logo/logo2.png') }}" alt=""></a> --}}
                                <div class="footer-widget-text">
                                    <p>Địa chỉ: 123 Đường ABC, Phường XYZ, Quận 1, TP. Hồ Chí Minh </p>
                                    <p>Số điện thoại:(012) 345-6789</p>
                                    <p>Email: info@example.com</p>
                                </div>
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
                                <h4 class="footer-widget-title">Giới Thiệu</h4>

                                <div class="footer-widget-menu">
                                    <ul>
                                        <li><a href="#">Giới thiệu</a></li>
                                        <li><a href="#">Liên hệ</a></li>
                                        <li><a href="#">Chính sách bảo mật</a></li>
                                        <li><a href="#">Điều khoản dịch vụ</a></li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>

                       

                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="footer-widget">
                                <h4 class="footer-widget-title">Chính Sách</h4>

                                <div class="footer-widget-menu">
                                    <ul>
                                        <li><a href="#">Chính Sách Đổi Trả</a></li>
                                        <li><a href="#">Chính Sách Vận Chuyển</a></li>
                                        <li><a href="#">Chính Sách Bảo Hành</a></li>
                                        <li><a href="#">Điều Kiện & Điều Khoản</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="footer-widget">
                                <h4 class="footer-widget-title">Hỗ Trợ Khách Hàng</h4>

                                <div class="footer-widget-menu">
                                    <ul>
                                        <li><a href="#">Hướng Dẫn Đặt Hàng</a></li>
                                        <li><a href="#">Phương Thức Thanh Toán</a></li>
                                        <li><a href="#">Hệ Thống Cửa Hàng</a></li>
                                        <li><a href="#">Hướng Dẫn Chọn Size</a></li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--Footer Section End-->


        <!-- Modal Add To WishList -->
        <div class="modal fade bd-example-modal-sm modal-AddToWishList" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Thông báo</h5>
                    </div>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                        aria-hidden="true"></button>
                    <div class="modal-body text-center p-3 h4">
                        <div class="mb-3">
                            <i class="fa fa-check-circle text-primary" style="font-size:50px;"></i>
                        </div>Đã thêm sản phẩm vào danh sách yêu thích
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tiếp tục mua
                            sắm</button>
                        <a href="{{ URL::to('/wishlist') }}" type="button" class="btn btn-primary">Xem danh
                            sách</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Add To WishList -->

    </div>



    <!-- JS
    ============================================ -->
    <!-- Bootstrap JS -->
    <script src="{{ asset('/page/js/vendor/popper.min.js') }}"></script>
    <script src="{{ asset('/page/js/vendor/bootstrap.min.js') }}"></script>

    <!-- Plugins JS -->
    <script src="{{ asset('/page/js/plugins/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('/page/js/plugins/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('/page/js/plugins/jquery.elevateZoom.min.js') }}"></script>
    <script src="{{ asset('/page/js/plugins/select2.min.js') }}"></script>
    <script src="{{ asset('/page/js/plugins/ajax-contact.js') }}"></script>



    <script src="{{ asset('/page/js/main.js') }}"></script>
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

        $(document).ready(function() {
            APP_URL = '{{ url('/') }}';

            $('.select-input__item').on('click', function() {
                var sort_by = $(this).data("sort");
                split_url = url.split("&sort_by");
                if (url.indexOf("?show=all") != -1 || url.indexOf("?keyword") != -1) window.location.href = split_url[0] + sort_by;
                else window.location.href = url + '?show=all' + sort_by;
            });



            // Gợi ý tìm kiếm sản phẩm
            $('#search-input').on('keyup', function() {
                var value = $(this).val();//lấy giá trị nhập vào
                var _token = $('input[name="_token"]').val();
                if (value != '') {
                    $.ajax({
                        url: '{{ url('/search-suggestions') }}',
                        method: 'POST',
                        data: {
                            value: value,
                            _token: _token
                        },
                        //yêu cầu thành công
                        success: function(data) {
                            $('.search-product').fadeIn();//hiển thị từ từ
                            $('.search-product').html(data);//hiện html

                            //sự kiện click cho từng gợi ý
                            $('.search-product-item').on('click', function() {
                                //lấy text của mục gợi ý làm giá trị tìm kiếm
                                $('#search-input').val($(this).text());
                                $('.search-product').fadeOut();
                            });
                        }
                    });
                } else $('.search-product').fadeOut();
            });

            //khi rời khỏi tìm kiếm sẽ mất gợi ý
            $('#search-input').on('blur', function() {
                $('.search-product').fadeOut();
            });






            // Bộ lọc tìm kiếm
            var category = [],
                tempArrayCat = [],
                brand = [],
                tempArrayBrand = [];
            url = window.location.href;

            $(".filter-product").on("click", function() {
                var sort_by = $('.select-input__sort').data("sort");
                var min_price = $('.input-filter-price.min').val();
                var max_price = $('.input-filter-price.max').val();
                var min_price_filter = '';
                var max_price_filter = '';

                //nếu ở trang tìm kiếm 
                if (url.indexOf("search?keyword=") != -1) {
                    var keyword = $('#keyword-link').val();//lấy gtri ptu có id -link
                    page = 'search?keyword=' + keyword;//tạo url tìm kiếm khóa cụ thể 
                } else page = 'store?show=all';


                //lặp qua các ptu và trạng thái checked 
                $.each($("[data-filter='brand']:checked"), function() {
                    tempArrayBrand.push($(this).val()); //thêm gtri pt hiện tại vào mảng 
                });
                 tempArrayBrand.reverse();

                $.each($("[data-filter='category']:checked"), function() {
                    tempArrayCat.push($(this).val());
                });
                tempArrayCat.reverse();

                if (min_price != '' && max_price != '' && parseInt(min_price) > parseInt(max_price)) $(
                    '.alert-filter-price').removeClass("d-none");
                else {
                    if (min_price != '') min_price_filter = '&priceMin=' + min_price;
                    else min_price_filter = '';

                    if (max_price != '') max_price_filter = '&priceMax=' + max_price;
                    else max_price_filter = '';

                    if (tempArrayBrand.length !== 0 && tempArrayCat.length !== 0) {
                        brand += '&brand=' + tempArrayBrand.toString();
                        category += '&category=' + tempArrayCat.toString();
                        window.location.href = page + brand + category + min_price_filter +max_price_filter + sort_by;
                    } else if (tempArrayCat.length !== 0) {
                        category += '&category=' + tempArrayCat.toString();
                        window.location.href = page + category + min_price_filter + max_price_filter + sort_by;
                    } else if (tempArrayBrand.length !== 0) {
                        brand += '&brand=' + tempArrayBrand.toString();
                        window.location.href = page + brand + min_price_filter + max_price_filter + sort_by;
                    } else window.location.href = page + min_price_filter + max_price_filter + sort_by;
                }
            });

            //sự kiện click của sắp xếp
            $('.select-input__item').on('click', function() {
                var sort_by = $(this).data("sort");
                split_url = url.split("&sort_by");
                if (url.indexOf("?show=all") != -1 || url.indexOf("?keyword") != -1) window.location.href =
                    split_url[0] + sort_by;
                else window.location.href = url + '?show=all' + sort_by;
            });

            $('.btn-filter-price').on('click', function() {
                var min_price = $('.input-filter-price.min').val();
                var max_price = $('.input-filter-price.max').val();
                var min_price_filter = '';
                var max_price_filter = '';

                if (min_price != '' && max_price != '' && parseInt(min_price) > parseInt(max_price)) $(
                    '.alert-filter-price').removeClass("d-none");
                else {
                    if (min_price != '') min_price_filter = '&priceMin=' + min_price;
                    else min_price_filter = '';

                    if (max_price != '') max_price_filter = '&priceMax=' + max_price;
                    else max_price_filter = '';

                    if (url.indexOf("&sort_by") != -1) {
                        split_url = url.split("&sort_by");
                        if (url.indexOf("&price") != -1) {
                            split_url_price = url.split("&price");
                            window.location.href = split_url_price[0] + min_price_filter +
                                max_price_filter + "&sort_by" + split_url[1];
                        } else window.location.href = split_url[0] + min_price_filter + max_price_filter +
                            "&sort_by" + split_url[1];
                    } else {
                        split_url = url.split("&price");
                        if (url.indexOf("?show=all") != -1 || url.indexOf("?keyword") != -1)
                            window.location.href = split_url[0] + min_price_filter + max_price_filter;
                        else window.location.href = url + '?show=all' + min_price_filter + max_price_filter;
                    }
                }
            });





            $('.add-to-wishlist').on('click', function(e) {
                e.preventDefault();
                var productId = $(this).data('id');
                var $this = $(this);

                $.ajax({
                    url: '{{ url('/add-to-wishlist') }}',
                    method: 'POST',
                    data: {
                        idProduct: productId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Cập nhật trạng thái nút
                            $this.addClass('in-wishlist').removeClass('not-in-wishlist');
                        } else if (response.removed) {
                            // Cập nhật trạng thái nút nếu sản phẩm bị xóa khỏi danh sách yêu thích
                            $this.addClass('not-in-wishlist').removeClass('in-wishlist');
                        } else if (response.already_in_wishlist) {
                            // Thông báo nếu sản phẩm đã có trong danh sách yêu thích
                            alert('Sản phẩm đã có trong danh sách yêu thích.');
                            $this.addClass('in-wishlist').removeClass('not-in-wishlist');
                        }
                    },
                    error: function() {
                        alert('Đã có lỗi xảy ra.');
                    }
                });
            });


        });
        
    </script>
</body>

</html>