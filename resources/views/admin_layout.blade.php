<?php

use Illuminate\Support\Facades\Session;
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>KidAngel dashboard</title>

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('/kidadmin/images/favicon.ico')}}" />
    <link rel="stylesheet" href="{{asset('/kidadmin/css/backend-plugin.min.css')}}">
   
</head>

<body class="  ">
    <!-- loader Start -->
    <div id="loading">
        <div id="loading-center">
        </div>
    </div>
    <!-- loader END -->
    <!-- Wrapper Start -->
    <div class="wrapper">

        <div class="iq-sidebar  sidebar-default ">
            <div class="iq-sidebar-logo d-flex align-items-center justify-content-between">
                <a >
                    <img src="{{asset('/kidadmin/images/logo.png')}}" >
                    <h5 class="logo-title light-logo ml-2">KidAngel</h5>
                </a>
                
            </div>

            <?php
            $position = Session::get('Position');
            $avatar = Session::get('Avatar');

          
            ?>
                <div class="data-scrollbar" data-scroll="1">
                    <nav class="iq-sidebar-menu">
                        <ul id="iq-sidebar-toggle" class="iq-menu">                   
                            <li class=" ">
                                <a href="#myaccount" class="collapsed" data-toggle="collapse" aria-expanded="false">
                                   
                                    <span class="ml-4">Quản Lý Tài Khoản</span>
                                    
                                </a>
                                <ul id="myaccount" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                    <li class="{{ Request::is('my-adprofile') ? 'active' : '' }}">
                                        <a href="{{URL::to('/my-adprofile')}}">
                                            <i class="las la-minus"></i><span>Hồ Sơ Của Tôi</span>
                                        </a>
                                    </li>
                                    <li class="{{ Request::is('edit-profile') ? 'active' : '' }}">
                                        <a href="{{URL::to('/edit-profile')}}">
                                            <i class="las la-minus"></i><span>Sửa Hồ Sơ</span>
                                        </a>
                                    </li>
                                    <li class="{{ Request::is('change-adpassword') ? 'active' : '' }}">
                                        <a href="{{URL::to('/change-adpassword')}}">
                                            <i class="las la-minus"></i><span>Đổi Mật Khẩu</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class=" ">
                                <a href="#brand" class="collapsed" data-toggle="collapse" aria-expanded="false">
                                    
                                    <span class="ml-4">Quản Lý Thương Hiệu</span>
                                    
                                </a>
                                <ul id="brand" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                    <li class="{{ Request::is('manage-brand') ? 'active' : '' }}">
                                        <a href="{{URL::to('/manage-brand')}}">
                                            <i class="las la-minus"></i><span>Danh Sách Thương Hiệu</span>
                                        </a>
                                    </li>
                                    <li class="{{ Request::is('add-brand') ? 'active' : '' }}">
                                        <a href="{{URL::to('/add-brand')}}">
                                            <i class="las la-minus"></i><span>Thêm Thương Hiệu</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>


                            <li class=" ">
                                <a href="#category" class="collapsed" data-toggle="collapse" aria-expanded="false">
                                    
                                    <span class="ml-4">Quản Lý Danh Mục</span>
                                    
                                </a>
                                <ul id="category" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                    <li class="{{ Request::is('manage-category') ? 'active' : '' }}">
                                        <a href="{{URL::to('/manage-category')}}">
                                            <i class="las la-minus"></i><span>Danh Sách Danh Mục</span>
                                        </a>
                                    </li>
                                    <li class="{{ Request::is('add-category') ? 'active' : '' }}">
                                        <a href="{{URL::to('/add-category')}}">
                                            <i class="las la-minus"></i><span>Thêm Danh Mục</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>



                            <li class=" ">
                                <a href="#product" class="collapsed" data-toggle="collapse" aria-expanded="false">
                                  
                                    <span class="ml-4">Quản Lý Sản Phẩm</span>
                                   
                                </a>
                                <ul id="product" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                    <li class="{{ Request::is('manage-product') ? 'active' : '' }}">
                                        <a href="{{URL::to('/manage-product')}}">
                                            <i class="las la-minus"></i><span>Danh Sách Sản Phẩm</span>
                                        </a>
                                    </li>
                                    <li class="{{ Request::is('add-product') ? 'active' : '' }}">
                                        <a href="{{URL::to('/add-product')}}">
                                            <i class="las la-minus"></i><span>Thêm Sản Phẩm</span>
                                        </a>
                                    </li>
                                    
                                   
                                    
                                </ul>
                            </li>

                            <li class=" ">
                                <a href="#attribute" class="collapsed" data-toggle="collapse" aria-expanded="false">
                                  
                                    <span class="ml-4">Quản Lý Phân Loại</span>
                                   
                                </a>
                               
                                    <ul id="attribute" class="iq-submenu collapse" data-parent="#product">
                                        <li class="{{ Request::is('manage-attribute') ? 'active' : '' }}">
                                            <a href="{{URL::to('/manage-attribute')}}">
                                                <i class="las la-minus"></i><span>Nhóm Phân Loại</span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::is('manage-attri-value') ? 'active' : '' }}">
                                            <a href="{{URL::to('/manage-attri-value')}}">
                                                <i class="las la-minus"></i><span>Phân Loại</span>
                                            </a>
                                        </li>
                                    </ul>
     
                                </ul>
                            </li>




                            
                        </ul>
                    </nav>
                    
                    <div class="p-3"></div>
                </div>
            <?php
           
            ?>
        </div>
        <div class="iq-top-navbar">
    <div class="iq-navbar-custom">
        <nav class="navbar navbar-expand-lg navbar-light p-0">        
            <div class="d-flex align-items-center w-100 justify-content-end">                      
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-list align-items-center">
                        <li class="nav-item nav-icon dropdown caption-content">
                            <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton4"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 @if($avatar != '')
                                  <img src="{{asset('/storage/kidadmin/images/user/'.$avatar)}}" class="img-fluid rounded" alt="user">
                                 @else
                                  <img src="{{asset('/kidadmin/images/user/12.jpg')}}" class="img-fluid rounded" alt="user">
                                 @endif
                            </a>
                            <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <div class="card shadow-none m-0">
                                    <div class="card-body p-0 text-center">
                                        <div class="media-body profile-detail text-center">
                                      
                                            @if($avatar != null)
                                            <img src="{{asset('/storage/kidadmin/images/user/'.$avatar)}}" alt="profile-img"
                                                class="rounded profile-img img-fluid avatar-70">
                                            @else
                                            <img src="{{asset('/kidadmin/images/user/12.jpg')}}" alt="profile-img"
                                                class="rounded profile-img img-fluid avatar-70">
                                            @endif
                                        </div>
                                        <div class="p-3">
                                            <h5 class="mb-1"><?php echo Session::get('AdminName'); ?></h5>
                                            <p class="mb-0"><?php echo Session::get('AdminUser'); ?></p>
                                            <div class="d-flex align-items-center justify-content-center mt-3">
                                                  <a href="{{URL::to('/my-adprofile')}}" class="btn border mr-2">Profile</a>
                                                  <a href="{{URL::to('/admin-logout')}}" class="btn border">Đăng Xuất</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>


        @yield('content_dash')

    </div>



    <!-- Backend Bundle JavaScript -->
    <script src="{{asset('/kidadmin/js/backend-bundle.min.js')}}"></script>
    <!-- app JavaScript -->
    <script src="{{asset('/kidadmin/js/app.js')}}"></script>
</body>

</html>