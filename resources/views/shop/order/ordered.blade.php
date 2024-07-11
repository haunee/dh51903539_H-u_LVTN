@extends('page_layout')
@section('content')
@php
    use Carbon\Carbon;
@endphp
    <!--Page Banner Start-->
    <div class="page-banner" style="background-image: url(/page/images/oso.png);">
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
                                    style="border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5; justify-content: space-evenly;">
                                    <a class="col-xl-2 col-md-2 text-center view-hover text-primary"
                                        style="position:relative;">
                                        <i class="fa fa-envelope" style="font-size:24px;"></i>
                                        <div>Tất cả</div>
                                        @if (App\Models\Order::where('idCustomer', Session::get('idCustomer'))->count() > 0)
                                            <span
                                                class="qty-ordered">{{ App\Models\Order::where('idCustomer', Session::get('idCustomer'))->count() }}</span>
                                        @endif
                                    </a>
                                    <a href="{{ URL::to('/order-waiting') }}"
                                        class="col-xl-2 col-md-2 text-center view-hover" style="position:relative;">
                                        <i class="fa fa-inbox" style="font-size:24px;"></i>
                                        <div>Chờ xác nhận</div>
                                        @if (App\Models\Order::where('idCustomer', Session::get('idCustomer'))->where('Status', '0')->count() > 0)
                                            <span
                                                class="qty-ordered">{{ App\Models\Order::where('idCustomer', Session::get('idCustomer'))->where('Status', '0')->count() }}</span>
                                        @endif
                                    </a>
                                    <a href="{{ URL::to('/order-shipping') }}"
                                        class="col-xl-2 col-md-2 text-center view-hover" style="position:relative;">
                                        <i class="fa fa-plane" style="font-size:24px;"></i>
                                        <div>Đang giao</div>
                                        @if (App\Models\Order::where('idCustomer', Session::get('idCustomer'))->where('Status', '1')->count() > 0)
                                            <span
                                                class="qty-ordered">{{ App\Models\Order::where('idCustomer', Session::get('idCustomer'))->where('Status', '1')->count() }}</span>
                                        @endif
                                    </a>
                                    <a href="{{ URL::to('/order-shipped') }}"
                                        class="col-xl-2 col-md-2 text-center view-hover" style="position:relative;">
                                        <i class="fa fa-check-circle" style="font-size:24px;"></i>
                                        <div>Đã giao</div>
                                        @if (App\Models\Order::where('idCustomer', Session::get('idCustomer'))->where('Status', '2')->count() > 0)
                                            <span
                                                class="qty-ordered">{{ App\Models\Order::where('idCustomer', Session::get('idCustomer'))->where('Status', '2')->count() }}</span>
                                        @endif
                                    </a>
                                    <a href="{{ URL::to('/order-cancelled') }}"
                                        class="col-xl-2 col-md-2 text-center view-hover" style="position:relative;">
                                        <i class="fa fa-times" style="font-size:24px;"></i>
                                        <div>Đã hủy</div>
                                        @if (App\Models\Order::where('idCustomer', Session::get('idCustomer'))->where('Status', '99')->count() > 0)
                                            <span
                                                class="qty-ordered">{{ App\Models\Order::where('idCustomer', Session::get('idCustomer'))->where('Status', '99')->count() }}</span>
                                        @endif
                                    </a>
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
                                                <td>{{ Carbon::parse($order->created_at)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') }}</td>

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

                if (confirm('Bạn có chắc chắn muốn hủy đơn hàng #' + idOrder+ ' không?')) {
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
