@extends('admin_layout')
@section('content_dash')

<div class="content-page">
    <div class="container-fluid">
        <div class="row">                  
            <div class="col-lg-12">
                <div class="card card-block card-stretch card-height print rounded">
                    <div class="card-header d-flex justify-content-between bg-primary header-invoice">
                        <div class="iq-header-title">
                            <h4 class="card-title mb-0">Đơn hàng #{{$address->idOrder}}</h4>
                        </div>
                        <!-- <div class="invoice-btn">
                            <button type="button" class="btn btn-primary-dark mr-2"><i class="las la-print"></i> Print
                                Print</button>
                            <button type="button" class="btn btn-primary-dark"><i class="las la-file-download"></i>PDF</button>
                        </div> -->
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive-sm">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Họ và tên người nhận</th>
                                                <th scope="col">Số điện thoại</th>
                                                <th scope="col">Địa chỉ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="padding-left:12px !important;">{{$address->CustomerName}}</td>
                                                <td style="padding-left:12px !important;">{{$address->PhoneNumber}}</td>
                                                <td style="padding-left:12px !important;">{{$address->Address}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="mb-3">Chi tiết đơn hàng</h5>
                                <div class="table-responsive-sm">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="text-center" scope="col">STT</th>
                                                <th scope="col">Sản phẩm</th>
                                                <th class="text-center" scope="col">Giá</th>
                                                <th class="text-center" scope="col">Số lượng</th>
                                                <th class="text-center" scope="col">Tổng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $Total = 0; $ship = 0; $total_bill = 0; $discount = 0; ?>
                                            @foreach($list_order_info as $key => $order_info)
                                                <?php $Total += ($order_info->Price * $order_info->QuantityBuy); ?>
                                            <tr>
                                                <th class="text-center" scope="row">{{$key + 1}}</th>
                                                <td class="row" style="border-bottom:0;">
                                                        <?php $image = json_decode($order_info->ImageName)[0]; ?>
                                                        <img class="avatar-70 rounded" src="{{asset('/storage/kidadmin/images/product/'.$image)}}" alt="">
                                                        <div class="ml-2" style="flex:1;">
                                                            <h6 class="mb-0">{{$order_info->ProductName}}</h6>
                                                            <p class="mb-0">Mã sản phẩm: {{$order_info->idProduct}}</p>
                                                            <span>{{$order_info->AttributeProduct}}</span>
                                                        </div>
                                                </td>
                                                <td class="text-center" style="border-bottom:0;">{{number_format($order_info->Price,0,',','.')}}đ</td>
                                                <td class="text-center" style="border-bottom:0;">{{$order_info->QuantityBuy}}</td>
                                                <td class="text-center" style="border-bottom:0;"><b>{{number_format($order_info->Price * $order_info->QuantityBuy,0,',','.')}}đ</b></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>                              
                        </div>
                        
                        
                        <div class="row mt-4 mb-3">
                            <div class="col-lg-12">
                                <div class="or-detail rounded">
                                    <div class="p-3 row">
                                        <h5 class="mb-3 col-lg-12">Order Details</h5>
                                        <div class="mb-2 col-lg-10">
                                            <h6>Tổng tiền hàng</h6>
                                        </div>
                                        <div class="mb-2 col-lg-2 text-right">
                                            <h6>{{number_format($Total,0,',','.')}}đ</h6>
                                        </div>

                                        @if($Total < 1000000) @php $ship = '30000'; $total_bill = $Total + $ship; @endphp
                                        @else @php $ship = 'Miễn phí'; $total_bill = $Total; @endphp @endif
                                        <div class="mb-2 col-lg-10">
                                            <h6>Phí vận chuyển (Miễn phí vận chuyển cho đơn hàng trên 1.000.000đ)</h6>
                                        </div>
                                        <div class="mb-2 col-lg-2 text-right">
                                            <h6>@if($ship > 0) {{number_format(floatval($ship),0,',','.')}}đ
                                                @else {{$ship}} @endif
                                            </h6>
                                        </div>

                                     
                                    </div>
                                    <div class="ttl-amt py-2 px-3 d-flex justify-content-between align-items-center">
                                        <h6>Thành tiền</h6>
                                        <h3 class="text-primary font-weight-700">{{number_format($total_bill,0,',','.')}}đ</h3>
                                    </div>
                                </div>
                                @if($address->Payment == 'vnpay'|| $address->Status == '2')
                                <div class="col-lg-3 paid_tag">
                                    <div class="h3 p-3 mb-0 text-primary">Đã thanh toán</div>
                                </div>
                                @endif
                            
                            </div>
                        </div>                            
                    </div>
                </div>
            </div>                                    
        </div>
    </div>
</div>

@endsection