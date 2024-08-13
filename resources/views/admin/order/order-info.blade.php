@extends('admin_layout')
@section('content_dash')

<div class="content-page">
    <div class="container-fluid">
        <div class="row">                  
            <div class="col-lg-12">
                <div class="card card-block card-stretch card-height print rounded">
                    <div class="card-header d-flex justify-content-between bg-primary header-invoice">
                        <div class="iq-header-title">
                            <h4 class="card-title mb-0">Đơn hàng #{{$order->idOrder}}</h4>
                        </div>
                        <div class="invoice-btn">
                            <a href="{{ route('order.pdf', ['idOrder' => $order->idOrder]) }}" class="btn btn-primary-dark">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 17v-5h1.5a1.5 1.5 0 1 1 0 3H5m12 2v-5h2m-2 3h2M5 10V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1v6M5 19v1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-1M10 3v4a1 1 0 0 1-1 1H5m6 4v5h1.375A1.627 1.627 0 0 0 14 15.375v-1.75A1.627 1.627 0 0 0 12.375 12H11Z"/>
                                  </svg>
                                   PDF
                            </a>
                        </div>
                        
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
                                                <td style="padding-left:12px !important;">{{$order->CustomerName}}</td>
                                                <td style="padding-left:12px !important;">{{$order->PhoneNumber}}</td>
                                                <td style="padding-left:12px !important;">{{$order->Address}}</td>
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
                                                            <span>{{$order_info->PropertyPro}}</span>
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

                                        @if($Total < 1000000) @php $ship = '50000'; $total_bill = $Total + $ship; @endphp
                                        @else @php $ship = 'Miễn phí'; $total_bill = $Total; @endphp @endif
                                        <div class="mb-2 col-lg-10">
                                            <h6>Phí vận chuyển (Miễn phí vận chuyển cho đơn hàng trên 1.000.000đ)</h6>
                                        </div>
                                        <div class="mb-2 col-lg-2 text-right">
                                            <h6>@if($ship > 0) {{number_format(floatval($ship),0,',','.')}}đ
                                                @else {{$ship}} @endif
                                            </h6>
                                        </div>

                                        @if($order->Voucher != '') 
                                            <div class="mb-2 col-lg-10">
                                                <h6>Mã giảm giá</h6>
                                            </div>
                                            @php
                                                $Voucher = explode("-",$order->Voucher);
                                                $VoucherCondition = $Voucher[0];
                                                $VoucherNumber = $Voucher[1];
                                                if($VoucherCondition == 1) $discount = ($Total/100) * $VoucherNumber;
                                                else{
                                                    $discount = $VoucherNumber;
                                                    if($discount > $Total) $discount = $Total;
                                                } 

                                                $total_bill =  $total_bill - $discount;
                                                if($total_bill < 0) $total_bill = $ship;
                                            @endphp
                                            <div class="mb-2 col-lg-2 text-right">
                                                <h6>- {{number_format($discount,0,',','.')}}đ</h6>
                                            </div>
                                        @endif

                                    </div>
                                    <div class="ttl-amt py-2 px-3 d-flex justify-content-between align-items-center">
                                        <h6>Thành tiền</h6>
                                        <h3 class="text-primary font-weight-700">{{number_format($total_bill,0,',','.')}}đ</h3>
                                    </div>
                                </div>
                                @if($order->Payment == 'vnpay'|| $order->Status == '2')
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