@extends('page_layout')
@section('content')
    @php
        use Carbon\Carbon;
    @endphp
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
                            <li><a href="{{ URL::to('/profile') }}"><i class="fa fa-user"></i> Hồ Sơ</a></li>
                            <li><a href="{{ URL::to('/change-password') }}"><i class="fa fa-key"></i> Đổi Mật Khẩu</a></li>
                            <li><a class="active"><i class="fa fa-shopping-cart"></i> Đơn Đặt Hàng</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-9 col-md-8">
                    <div class="tab-content my-account-tab mt-30" id="pills-tabContent">
                        <div class="tab-pane fade active show">
                            <div class="my-account-order account-wrapper">
                                <h4 class="account-title mb-15">Đơn Đặt Hàng</h4>

                                <!-- Thông báo xóa đơn hàng -->
                                <div id="delete-bill-notification" class="alert" style="display:none;"></div>

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
                                                <a class="dropdown-item" href="{{ URL::to('/ordered') }}"
                                                    onclick="updateDropdownText('Tất cả'); return false;">
                                                    Tất cả
                                                </a>
                                                <!-- Waiting Orders -->
                                                <a class="dropdown-item" href="{{ URL::to('/order-waiting') }}"
                                                    onclick="updateDropdownText('Chờ xác nhận'); return false;">
                                                    Chờ xác nhận
                                                </a>
                                                <!-- Shipping Orders -->
                                                <a class="dropdown-item" href="{{ URL::to('/order-shipping') }}"
                                                    onclick="updateDropdownText('Đang giao'); return false;">
                                                    Đang giao
                                                </a>
                                                <!-- Shipped Orders -->
                                                <a class="dropdown-item" href="{{ URL::to('/order-shipped') }}"
                                                    onclick="updateDropdownText('Đã giao'); return false;">
                                                    Đã giao
                                                </a>
                                                <!-- Cancelled Orders -->
                                                <a class="dropdown-item" href="{{ URL::to('/order-cancelled') }}"
                                                    onclick="updateDropdownText('Đã hủy'); return false;">
                                                    Đã hủy
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>






                                <table id="example" class="table table-striped table-bordered dt-responsive nowrap"
                                    style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th class="no">Mã ĐH</th>
                                            <th class="name">Tên người nhận</th>
                                            <th class="date">Ngày đặt</th>
                                            <th class="status">Trạng thái</th>
                                            <th class="total">Tổng tiền</th>
                                            <th class="action text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($list_order as $key => $order)
                                            <tr>
                                                <td>{{ $order->idOrder }}</td>
                                                <td>{{ $order->CustomerName }}</td>
                                                <td>{{ Carbon::parse($order->created_at)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') }}
                                                </td>

                                                @if ($order->Status == 0)
                                                    <td>Chờ xác nhận...</td>
                                                @elseif($order->Status == 1)
                                                    <td>Đang giao</td>
                                                @elseif($order->Status == 2)
                                                    <td>Đã giao</td>
                                                @else
                                                    <td>Đã hủy</td>
                                                @endif

                                                <td>{{ number_format($order->TotalBill, 0, ',', '.') }}đ</td>

                                                <td class="d-flex justify-content-center">
                                                    <a class="view-hover h3 mr-2"
                                                        href="{{ URL::to('/ordered-info/' . $order->idOrder) }}"
                                                        data-toggle="tooltip" data-placement="top" title=""
                                                        data-original-title="Xem chi tiết"><i class="fa fa-eye"></i></a>
                                                    @if ($order->Status == 0)
                                                        <meta name="csrf-token" content="{{ csrf_token() }}">
                                                        <a class="view-hover h3 ml-2 delete-order-btn"
                                                            data-id="{{ $order->idOrder }}">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--My Account End-->

    <script>
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

            function updateDropdownText(text) {
                document.getElementById('orderStatusDropdown').innerText = text;
            }



        });
    </script>
@endsection
