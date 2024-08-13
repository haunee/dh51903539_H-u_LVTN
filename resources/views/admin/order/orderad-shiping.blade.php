@extends('admin_layout')
@section('content_dash')
    @php
        use Carbon\Carbon;
    @endphp
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-3"> Tổng: {{ $list_order->count() }} đơn hàng </h4>

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
                                            <a class="dropdown-item" href="{{ URL::to('/orderad-list') }}"
                                                onclick="updateDropdownText('Tất cả'); return false;">
                                                Tất cả
                                            </a>
                                            <a class="dropdown-item" href="{{ URL::to('/orderad-wait') }}"
                                                onclick="updateDropdownText('Chờ xác nhận'); return false;">
                                                Chờ xác nhận
                                            </a>
                                            <a class="dropdown-item" href="{{ URL::to('/orderad-confirm') }}"
                                                onclick="updateDropdownText('Chờ xác nhận'); return false;">
                                                Đã xác nhận
                                            </a>
                                            <a class="dropdown-item" href="{{ URL::to('/orderad-shiping') }}"
                                                onclick="updateDropdownText('Đang giao'); return false;">
                                                Đang vận chuyển
                                            </a>
                                            <a class="dropdown-item" href="{{ URL::to('/orderad-shiped') }}"
                                                onclick="updateDropdownText('Đã giao'); return false;">
                                                Đã nhận hàng
                                            </a>
                                            <a class="dropdown-item" href="{{ URL::to('/oderad-cancelled') }}"
                                                onclick="updateDropdownText('Đã hủy'); return false;">
                                                Đơn đã hủy
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="table-responsive rounded mb-3">
                        <table class="data-tables table mb-0 tbl-server-info">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data">
                                    <th>Mã </th>
                                    <th>Tên Tài Khoản</th>
                                    <th>SĐT</th>
                                    <th>Thanh Toán</th>
                                    <th>Ngày Đặt </th>
                                    <th>Ngày Xác Nhận</th>
                                    <th>NV Xác Nhận</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="ligth-body" id="load-bill">
                                @foreach ($list_order as $key => $order)
                                    <tr>
                                        <td>{{ $order->idOrder }}</td>
                                        <td>{{ $order->username }}</td>
                                        <td>{{ $order->CusPhone }}</td>
                                        <td>
                                            @if ($order->Payment == 'vnpay')
                                                VNPay
                                            @else
                                                Khi nhận hàng
                                            @endif
                                        </td>
                                        <td>{{ Carbon::parse($order->created_at)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') }}
                                        </td>
                                       
                                        <td>{{ Carbon::parse($order->TimeConfirm)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') }}
                                        </td>
                                        <td>
                                            <div class=" align-items-center badge badge-warning">{{ $order->AdminName }}
                                            </div>
                                        </td>
                                        <td>
                                            <form action="{{ URL::to('/confirm-bill/' . $order->idOrder) }}" method="POST">
                                                @csrf
                                                <div class="d-flex align-items-center list-action">
                                                    <a class="badge badge-success mr-2" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="Xem chi tiết"
                                                        href="{{ URL::to('/order-info/' . $order->idOrder) }}"><i
                                                            class="fa fa-eye mr-0"></i>
                                                    </a>
                                                    <button class="badge badge-info mr-2 momo" style="border:none;"
                                                        data-toggle="tooltip" data-placement="top" title=""
                                                        data-original-title="Xác nhận hoàn thành"><i
                                                            class="fa fa-check"></i>
                                                    </button>
                                                    <input type="hidden" name="Status" value="2">
                                                </div>
                                            </form>
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
    <!-- Page end  -->
@endsection
