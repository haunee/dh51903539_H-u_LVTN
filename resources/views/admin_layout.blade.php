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
    
   
    
    <!-- Dashboard -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>


    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('/kidadmin/images/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('/kidadmin/css/backend-plugin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/kidadmin/css/backend.css?v=1.0.0') }}">
    <link rel="stylesheet" href="{{ asset('/kidadmin/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{asset('/kidadmin/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css')}}">
    <link rel="stylesheet" href="{{ asset('/kidadmin/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/kidadmin/vendor/remixicon/fonts/remixicon.css') }}">






</head>

<body class="  ">
    <!-- loader Start -->
    <div id="loading">
        <div id="loading-center"> </div>
    </div>
    <!-- loader END -->
    <!-- Wrapper Start -->
    <div class="wrapper">

        <div class="iq-sidebar  sidebar-default ">
            <div class="iq-sidebar-logo d-flex align-items-center justify-content-between">
                <a>
                    
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
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v15a1 1 0 0 0 1 1h15M8 16l2.5-5.5 3 3L17.273 7 20 9.667"/>
                                  </svg>
                                  
                                <span class="ml-4">Thống Kê Doanh Thu</span>
                            </a>
                        </li>



                        
                        <li class=" ">
                            <a href="#purchase" class="collapsed" data-toggle="collapse" aria-expanded="false">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M14 7h-4v3a1 1 0 0 1-2 0V7H6a1 1 0 0 0-.997.923l-.917 11.924A2 2 0 0 0 6.08 22h11.84a2 2 0 0 0 1.994-2.153l-.917-11.924A1 1 0 0 0 18 7h-2v3a1 1 0 1 1-2 0V7Zm-2-3a2 2 0 0 0-2 2v1H8V6a4 4 0 0 1 8 0v1h-2V6a2 2 0 0 0-2-2Z" clip-rule="evenodd"/>
                                  </svg>
                                  
                                  
                                  
                                <span class="ml-4">Quản Lý Đơn Hàng</span>
                               
                            </a>
                            <ul id="purchase" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                <li class="{{ Request::is('list-order') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/list-order') }}">
                                        <i class="las la-minus"></i><span>Danh Sách Đơn Hàng</span>
                                    </a>
                                </li>
                               
                             
                            </ul>
                        </li>



                        <li class=" ">
                            <a href="#product" class="collapsed" data-toggle="collapse" aria-expanded="false">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M5.005 10.19a1 1 0 0 1 1 1v.233l5.998 3.464L18 11.423v-.232a1 1 0 1 1 2 0V12a1 1 0 0 1-.5.866l-6.997 4.042a1 1 0 0 1-1 0l-6.998-4.042a1 1 0 0 1-.5-.866v-.81a1 1 0 0 1 1-1ZM5 15.15a1 1 0 0 1 1 1v.232l5.997 3.464 5.998-3.464v-.232a1 1 0 1 1 2 0v.81a1 1 0 0 1-.5.865l-6.998 4.042a1 1 0 0 1-1 0L4.5 17.824a1 1 0 0 1-.5-.866v-.81a1 1 0 0 1 1-1Z" clip-rule="evenodd"/>
                                    <path d="M12.503 2.134a1 1 0 0 0-1 0L4.501 6.17A1 1 0 0 0 4.5 7.902l7.002 4.047a1 1 0 0 0 1 0l6.998-4.04a1 1 0 0 0 0-1.732l-6.997-4.042Z"/>
                                  </svg>
                                  
                                  
                                <span class="ml-4">Quản Lý Sản Phẩm</span>
                               

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
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M6 5V4a1 1 0 1 1 2 0v1h3V4a1 1 0 1 1 2 0v1h3V4a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v2H3V7a2 2 0 0 1 2-2h1ZM3 19v-8h18v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm5-6a1 1 0 1 0 0 2h8a1 1 0 1 0 0-2H8Z" clip-rule="evenodd"/>
                                  </svg>
                                  
                                <span class="ml-4">Quản Lý Thương Hiệu</span>
                              
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
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M2 6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6Zm2 8v-2h7v2H4Zm0 2v2h7v-2H4Zm9 2h7v-2h-7v2Zm7-4v-2h-7v2h7Z" clip-rule="evenodd"/>
                                  </svg>
                                  
                                <span class="ml-4">Quản Lý Danh Mục</span>
                               
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
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M2 6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6Zm4.996 2a1 1 0 0 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 8a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Zm-4.004 3a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 11a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Zm-4.004 3a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 14a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Z" clip-rule="evenodd"/>
                                  </svg>
                                  
                                <span class="ml-4">Quản Lý Phân Loại</span>
                              
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
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z" clip-rule="evenodd"/>
                                  </svg>
                                  
                                <span class="ml-4">Danh Sách Tài Khoản</span>
                                
                            </a>
                            <ul id="manage-people" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                <li class="{{ Request::is('manage-customers') ? 'active' : '' }}">
                                    <a href="{{ URL::to('/manage-customers') }}">
                                        <i class="las la-minus"></i><span>Tài Khoản User</span>
                                    </a>
                                </li>

                            </ul>
                        </li>





                        <li class="">
                            <a href="#voucher" class="collapsed" data-toggle="collapse" aria-expanded="false">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 7h-.7c.229-.467.349-.98.351-1.5a3.5 3.5 0 0 0-3.5-3.5c-1.717 0-3.215 1.2-4.331 2.481C10.4 2.842 8.949 2 7.5 2A3.5 3.5 0 0 0 4 5.5c.003.52.123 1.033.351 1.5H4a2 2 0 0 0-2 2v2a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V9a2 2 0 0 0-2-2Zm-9.942 0H7.5a1.5 1.5 0 0 1 0-3c.9 0 2 .754 3.092 2.122-.219.337-.392.635-.534.878Zm6.1 0h-3.742c.933-1.368 2.371-3 3.739-3a1.5 1.5 0 0 1 0 3h.003ZM13 14h-2v8h2v-8Zm-4 0H4v6a2 2 0 0 0 2 2h3v-8Zm6 0v8h3a2 2 0 0 0 2-2v-6h-5Z"/>
                                  </svg>
                                  
                                  
                                <span class="ml-4">Mã Giảm Giá</span>
                                
                            </a>
                            <ul id="voucher" class="iq-submenu collapse" data-parent="#product">
                                <li class="{{ Request::is('manage-voucher') ? 'active' : '' }}">
                                    <a href="{{URL::to('/manage-voucher')}}">
                                        <i class="las la-minus"></i><span>Danh Sách Mã</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('add-voucher') ? 'active' : '' }}">
                                    <a href="{{URL::to('/add-voucher')}}">
                                        <i class="las la-minus"></i><span>Thêm Mã</span>
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
