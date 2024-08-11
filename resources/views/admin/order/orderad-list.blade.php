@extends('admin_layout')
@section('content_dash')
    @php
        use Carbon\Carbon;
    @endphp
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-3">Tổng: {{ $list_order->count() }} đơn hàng </h4>
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
                                                Đang giao
                                            </a>
                                            <a class="dropdown-item" href="{{ URL::to('/orderad-shiped') }}"
                                                onclick="updateDropdownText('Đã giao'); return false;">
                                                Đã giao
                                            </a>
                                            <a class="dropdown-item" href="{{ URL::to('/oderad-cancelled') }}"
                                                onclick="updateDropdownText('Đã hủy'); return false;">
                                                Đã hủy
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

                                    <th>Ngày Đặt </th>
                                    <th>Ngày Giao </th>
                                    <th>Thanh Toán</th>
                                    <th>Trạng Thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="ligth-body" id="load-bill">
                                @foreach ($list_order as $key => $order)
                                    <tr>
                                        <td>{{ $order->idOrder }}</td>
                                        <td>{{ $order->username }}</td>
                                        <td>{{ $order->CusPhone }}</td>
                                        <td>{{ Carbon::parse($order->created_at)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') }}
                                        </td>



                                        @if ($order->ReceiveDate != null)
                                            <td>{{ Carbon::parse($order->ReceiveDate)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') }}
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <div class="align-items-center badge badge-warning">Chưa giao</div>
                                            </td>
                                        @endif

                                        <td>
                                            @if ($order->Payment == 'vnpay')
                                                VNPay
                                            @else
                                                Khi nhận hàng
                                            @endif
                                        </td>

                                        @if ($order->Status == 0)
                                            <td>
                                                <div class=" align-items-center badge badge-warning">Chờ xác nhận</div>
                                            </td>
                                        @elseif($order->Status == 1)
                                            <td>
                                                <div class=" align-items-center badge badge-info">Đang giao</div>
                                            </td>
                                        @elseif($order->Status == 2)
                                            <td>
                                                <div class=" align-items-center badge badge-success">Đã giao</div>
                                            </td>
                                        @else
                                            <td>
                                                <div class=" align-items-center badge badge-success">Đã hủy</div>
                                            </td>
                                        @endif

                                        <td>
                                            <form action="{{ URL::to('/confirm-bill/' . $order->idOrder) }}" method="POST">
                                                @csrf
                                                <div class="d-flex align-items-center list-action">
                                                    <a class="badge badge-success mr-2" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="Xem chi tiết"
                                                        href="{{ URL::to('/order-info/' . $order->idOrder) }}"><i
                                                            class="fa fa-eye"></i>
                                                    </a>
                                                    @if ($order->Status == 0)
                                                        <button class="badge badge-info mr-2 momo" style="border:none;"
                                                            data-toggle="tooltip" data-placement="top" title=""
                                                            data-original-title="Xác nhận đơn hàng"><i
                                                                class="fa fa-check"></i>
                                                        </button>
                                                        <input type="hidden" name="Status" value="1">
                                                        <a class="badge bg-warning mr-2 delete-bill-btn" data-toggle="modal"
                                                            data-target="#modal-delete-bill" data-placement="top"
                                                            data-original-title="Hủy đơn hàng"
                                                            data-id="{{ $order->idOrder }}" style="cursor:pointer;"><i
                                                                class="fa fa-trash"></i>
                                                        </a>
                                                    @elseif($order->Status == 1)
                                                        <button class="badge badge-info mr-2 momo" style="border:none;"
                                                            data-toggle="tooltip" data-placement="top" title=""
                                                            data-original-title="Xác nhận hoàn thành"><i
                                                                class="fa fa-check"></i>
                                                        </button>
                                                        <input type="hidden" name="Status" value="2">
                                                    @endif
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

    <!-- Modal hủy đơn hàng -->
    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" id="modal-delete-bill" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thông báo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="content-delete"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Trở về</button>
                    <button id="delete-bill-confirm" type="button" class="btn btn-primary">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            APP_URL = '{{ url('/') }}';
            $(".delete-bill-btn").on("click", function() {
                var idOrder = $(this).data("id");
                $(".content-delete").html("Bạn có muốn hủy đơn hàng #" + idOrder + " không?");

                $("#delete-bill-confirm").on("click", function() {
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: APP_URL + '/delete-bill/' + idOrder,
                        method: 'POST',
                        data: {
                            _token: _token
                        },
                        success: function(data) {
                            location.reload();
                        }
                    });
                });
            });
        });
    </script>
@endsection
