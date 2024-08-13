@extends('page_layout')
@section('content')
    <!--Page Banner Start-->
    <div class="page-banner" style="background-image: url(/page/images/banner/banner5.jpg);">
        <div class="container">
            <div class="page-banner-content text-center">
                <h2 class="title">Đơn đặt hàng</h2>
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Đơn đặt hàng</li>
                </ol>
            </div>
        </div>
    </div>
    <!--Page Banner End-->


    <!--My Account Start-->
    <div class="register-page section-padding-5">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-md-4">
                    <div class="my-account-menu mt-30">
                        <ul class="nav account-menu-list flex-column">
                            <li>
                                <a href="{{ URL::to('/account') }}"><i class="fa fa-user"></i> Hồ Sơ</a>
                            </li>
                            <li>
                                <a href="{{ URL::to('/change-password') }}"><i class="fa fa-key"></i> Đổi Mật Khẩu</a>
                            </li>
                            <li>
                                <a class="active"><i class="fa fa-shopping-cart"></i> Đơn Đặt Hàng</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-9 col-md-8">
                    <div class="tab-content my-account-tab mt-30" id="pills-tabContent">
                        <div class="tab-pane fade active show">
                            <div class="my-account-order account-wrapper">
                                <h4 class="account-title mb-15">Đơn Đặt Hàng</h4>
                                <div class="row pt-30 pb-30 mb-25"
                                    style="border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5; justify-content: flex-start;">

                                    <div class="col-xl-2 col-md-2 text-left" style="margin-left: 0;">
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                id="orderStatusDropdown" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Trạng thái đơn hàng
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="orderStatusDropdown">
                                                <!-- All Orders -->
                                                <a class="dropdown-item" href="{{ URL::to('/ordered') }}">
                                                    <i style="font-size:24px;"></i> Tất cả

                                                </a>
                                                <!-- Waiting Orders -->
                                                <a class="dropdown-item" href="{{ URL::to('/order-waiting') }}">
                                                    <i style="font-size:24px;"></i> Chờ xác nhận

                                                </a>
                                                <!-- Shipping Orders -->
                                                <a class="dropdown-item" href="{{ URL::to('/order-shipping') }}">
                                                    <i style="font-size:24px;"></i> Đang vận chuyển

                                                </a>
                                                <!-- Shipped Orders -->
                                                <a class="dropdown-item" href="{{ URL::to('/order-shipped') }}">
                                                    <i style="font-size:24px;"></i> Đã nhận hàng

                                                </a>
                                                <!-- Cancelled Orders -->
                                                <a class="dropdown-item" href="{{ URL::to('/order-cancelled') }}">
                                                    <i style="font-size:24px;"></i> Đơn hủy

                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="account-table text-center mt-25 table-responsive"> -->
                                <table id="example" class="table table-striped table-bordered dt-responsive nowrap"
                                    style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th class="no">Mã ĐH</th>
                                            <th class="name">Tên người nhận</th>
                                            <th class="date">Ngày đặt</th>
                                            <th class="total">Tổng tiền</th>
                                            <th class="action text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($list_order as $key => $order )
                                            <tr>
                                                <td>{{ $order ->idOrder }}</td>
                                                <td>{{ $order ->CustomerName }}</td>
                                                <td>{{ $order ->created_at }}</td>

                                                <td>{{ number_format($order ->TotalBill, 0, ',', '.') }}đ</td>
                                                <td class="d-flex justify-content-center">
                                                    <a class="view-hover  mr-2"
                                                        href="{{ URL::to('/ordered-info/' . $order ->idOrder) }}"
                                                        data-toggle="tooltip" data-placement="top" title=""
                                                        data-original-title="Xem chi tiết">
                                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm9.408-5.5a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM10 10a1 1 0 1 0 0 2h1v3h-1a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2h-1v-4a1 1 0 0 0-1-1h-2Z" clip-rule="evenodd"/>
                                                          </svg>
                                                          
                                                    </a>
                                                        
                                                        <a class="view-hover  ml-2 delete-order-btn" data-id="{{ $order ->idOrder }}" href="#" title="Xóa đơn hàng">
                                                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                                              </svg>
                                                              
                                                              
                                                            <meta name="csrf-token" content="{{ csrf_token() }}">
                                                        </a>
                                                        
                                                        
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--My Account End-->

    

    <script>
        window.scrollBy(0, 300);
        $(document).ready(function() {
            var APP_URL = '{{ url('/') }}';

            $(".delete-order-btn").on("click", function(e) {
                e.preventDefault();

                var idOrder = $(this).data("id");
                var button = $(this);

                if (confirm('Bạn có chắc chắn muốn hủy đơn hàng #' + idOrder + ' không?')) {
                    var _token = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: APP_URL + '/delete-order/' + idOrder,
                        method: 'POST',
                        data: {
                            _token: _token
                        },
                        success: function(data) {
                            if (data.success) {
                                button.closest('tr').remove();
                                $('#delete-bill-notification').addClass('alert-success').text(
                                    'Đơn hàng đã được hủy thành công.').show().fadeOut(5000);
                            } else {
                                $('#delete-bill-notification').addClass('alert-danger').text(
                                    data.message).show().fadeOut(5000);
                            }
                        },
                        error: function(xhr) {
                            console.log('Error:', xhr.responseText);
                            $('#delete-bill-notification').addClass('alert-danger').text(
                                'Có lỗi xảy ra. Vui lòng thử lại.').show().fadeOut(5000);
                        }
                    });

                }
            });
        });
    </script>
@endsection
