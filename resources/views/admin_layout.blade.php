<?php

use Illuminate\Support\Facades\Session;
?>

@php
    use Carbon\Carbon;
@endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
    <link rel="shortcut icon" href="{{ asset('/kidadmin/images/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('/kidadmin/css/backend-plugin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/kidadmin/css/backend.css?v=1.0.0') }}">
    <link rel="stylesheet" href="{{ asset('/kidadmin/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{asset('/kidadmin/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css')}}">
    <link rel="stylesheet"
        href="{{ asset('/kidadmin/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/kidadmin/vendor/remixicon/fonts/remixicon.css') }}">






    {{-- <!-- Morris Chart CSS -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('/kidadmin/images/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('/kidadmin/css/backend-plugin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/kidadmin/css/backend.css?v=1.0.0') }}">
    <link rel="stylesheet" href="{{ asset('/kidadmin/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('/kidadmin/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/kidadmin/vendor/remixicon/fonts/remixicon.css') }}"> --}}



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
                <a>
                    <img src="{{ asset('/kidadmin/images/logo.png') }}">
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

                        <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                            <a href="{{ URL::to('/dashboard') }}" class="svg-icon">
                                <svg class="svg-icon" id="p-dash1" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                    </path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg>
                                <span class="ml-4">Thống Kê Doanh Thu</span>
                            </a>
                        </li>



                        <li class=" ">
                            <a href="#myaccount" class="collapsed" data-toggle="collapse" aria-expanded="false">
                                <svg class="svg-icon" id="p-dash8" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span class="ml-4">Quản Lý Tài Khoản</span>
                                <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="10 15 15 20 20 15"></polyline>
                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                </svg>

                            </a>
                            <ul id="myaccount" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                <li class="{{ Request::is('my-adprofile') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/my-adprofile') }}">
                                        <i class="las la-minus"></i><span>Hồ Sơ Của Tôi</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('edit-profile') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/edit-profile') }}">
                                        <i class="las la-minus"></i><span>Sửa Hồ Sơ</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('change-adpassword') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/change-adpassword') }}">
                                        <i class="las la-minus"></i><span>Đổi Mật Khẩu</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class=" ">
                            <a href="#purchase" class="collapsed" data-toggle="collapse" aria-expanded="false">
                                <svg class="svg-icon" id="p-dash2" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                <span class="ml-4">Quản Lý Đơn Hàng</span>
                                <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="10 15 15 20 20 15"></polyline>
                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                </svg>
                            </a>
                            <ul id="purchase" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                <li class="{{ Request::is('list-bill') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/list-bill') }}">
                                        <i class="las la-minus"></i><span>Danh Sách Đơn Hàng</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('waiting-bill') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/waiting-bill') }}">
                                        <i class="las la-minus"></i><span>Đơn Chờ Xác Nhận</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('shipping-bill') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/shipping-bill') }}">
                                        <i class="las la-minus"></i><span>Đơn Đang Giao</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('shipped-bill') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/shipped-bill') }}">
                                        <i class="las la-minus"></i><span>Đơn Đã Giao</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('cancelled-bill') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/cancelled-bill') }}">
                                        <i class="las la-minus"></i><span>Đơn Đã Hủy</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('confirmed-bill') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/confirmed-bill') }}">
                                        <i class="las la-minus"></i><span>Đơn Đã Xác Nhận</span>
                                    </a>
                                </li>
                            </ul>
                        </li>



                        <li class=" ">
                            <a href="#product" class="collapsed" data-toggle="collapse" aria-expanded="false">
                                <svg class="svg-icon" id="p-dash5" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                                    </rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                <span class="ml-4">Quản Lý Sản Phẩm</span>
                                <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="10 15 15 20 20 15"></polyline>
                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                </svg>

                            </a>
                            <ul id="product" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                <li class="{{ Request::is('manage-product') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/manage-product') }}">
                                        <i class="las la-minus"></i><span>Danh Sách Sản Phẩm</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('add-product') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/add-product') }}">
                                        <i class="las la-minus"></i><span>Thêm Sản Phẩm</span>
                                    </a>
                                </li>



                            </ul>
                        </li>



                        <li class=" ">
                            <a href="#brand" class="collapsed" data-toggle="collapse" aria-expanded="false">
                                <svg class="svg-icon" id="p-dash3" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2">
                                    </rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg>
                                <span class="ml-4">Quản Lý Thương Hiệu</span>
                                <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="10 15 15 20 20 15"></polyline>
                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                </svg>

                            </a>
                            <ul id="brand" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                <li class="{{ Request::is('manage-brand') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/manage-brand') }}">
                                        <i class="las la-minus"></i><span>Danh Sách Thương Hiệu</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('add-brand') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/add-brand') }}">
                                        <i class="las la-minus"></i><span>Thêm Thương Hiệu</span>
                                    </a>
                                </li>
                            </ul>
                        </li>


                        <li class=" ">
                            <a href="#category" class="collapsed" data-toggle="collapse" aria-expanded="false">
                                <svg class="svg-icon" id="p-dash3" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2">
                                    </rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg>
                                <span class="ml-4">Quản Lý Danh Mục</span>
                                <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="10 15 15 20 20 15"></polyline>
                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                </svg>
                            </a>
                            <ul id="category" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                <li class="{{ Request::is('manage-category') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/manage-category') }}">
                                        <i class="las la-minus"></i><span>Danh Sách Danh Mục</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('add-category') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/add-category') }}">
                                        <i class="las la-minus"></i><span>Thêm Danh Mục</span>
                                    </a>
                                </li>
                            </ul>
                        </li>





                        <li class=" ">
                            <a href="#manage-attribute" class="collapsed" data-toggle="collapse"
                                aria-expanded="false">
                                <svg class="svg-icon" id="p-dash3" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2">
                                    </rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg>
                                <span class="ml-4">Quản Lý Phân Loại</span>
                                <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="10 15 15 20 20 15"></polyline>
                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                </svg>
                            </a>
                            <ul id="manage-attribute" class="iq-submenu collapse" data-parent="#product">
                                <li class="{{ Request::is('manage-attribute') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/manage-attribute') }}">
                                        <i class="las la-minus"></i><span>Danh sách Nhóm Phân Loại</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('manage-attri-value') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/manage-attri-value') }}">
                                        <i class="las la-minus"></i><span>Danh sách Phân Loại</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class=" ">
                            <a href="#manage-people" class="collapsed" data-toggle="collapse" aria-expanded="false">
                                <svg class="svg-icon" id="p-dash8" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span class="ml-4">Danh Sách Tài Khoản</span>
                                <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="10 15 15 20 20 15"></polyline>
                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                </svg>
                            </a>
                            <ul id="manage-people" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                <li class="{{ Request::is('manage-customers') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/manage-customers') }}">
                                        <i class="las la-minus"></i><span>Tài Khoản User</span>
                                    </a>
                                </li>

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
                                        @if ($avatar != '')
                                            <img src="{{ asset('/storage/kidadmin/images/user/' . $avatar) }}"
                                                class="img-fluid rounded" alt="user">
                                        @else
                                            <img src="{{ asset('/kidadmin/images/user/12.jpg') }}"
                                                class="img-fluid rounded" alt="user">
                                        @endif
                                    </a>
                                    <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <div class="card shadow-none m-0">
                                            <div class="card-body p-0 text-center">
                                                <div class="media-body profile-detail text-center">

                                                    @if ($avatar != null)
                                                        <img src="{{ asset('/storage/kidadmin/images/user/' . $avatar) }}"
                                                            alt="profile-img"
                                                            class="rounded profile-img img-fluid avatar-70">
                                                    @else
                                                        <img src="{{ asset('/kidadmin/images/user/12.jpg') }}"
                                                            alt="profile-img"
                                                            class="rounded profile-img img-fluid avatar-70">
                                                    @endif
                                                </div>
                                                <div class="p-3">
                                                    <h5 class="mb-1"><?php echo Session::get('AdminName'); ?></h5>
                                                    <p class="mb-0"><?php echo Session::get('AdminUser'); ?></p>
                                                    <div class="d-flex align-items-center justify-content-center mt-3">
                                                        <a href="{{ URL::to('/my-adprofile') }}"
                                                            class="btn border mr-2">Profile</a>
                                                        <a href="{{ URL::to('/admin-logout') }}"
                                                            class="btn border">Đăng Xuất</a>
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




    <!-- Table Treeview JavaScript -->
    <script src="{{ asset('/kidadmin/js/table-treeview.js') }}"></script>


    <!-- Chart Custom JavaScript -->
    <script async src="{{ asset('/kidadmin/js/chart-custom.js') }}"></script>

    <!-- Backend Bundle JavaScript -->
    <script src="{{ asset('/kidadmin/js/backend-bundle.min.js') }}"></script>


    <!-- app JavaScript -->
    <script src="{{ asset('/kidadmin/js/app.js') }}"></script>

    <script src="{{ asset('/kidadmin/js/ckeditor/ckeditor.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{asset('/kidadmin/datetimepicker-master/jquery.datetimepicker.css')}}">
    <script src="{{asset('/kidadmin/datetimepicker-master/jquery.js')}}"></script>
    <script src="{{asset('/kidadmin/datetimepicker-master/build/jquery.datetimepicker.full.min.js')}}"></script>


<!-- Chart Custom JavaScript -->
<script src="{{asset('/kidadmin/js/customizer.js')}}"></script>


<script src="{{asset('/kidadmin/js/form-validate.js')}}"></script>
</body>

</html>
