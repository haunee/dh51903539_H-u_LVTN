@extends('page_layout')
@section('content')
    <?php use Illuminate\Support\Facades\Session; ?>

    <!--Page Banner Start-->
    <div class="page-banner" style="background-image: url(/page/images/banner/banner5.jpg);">
        <div class="container">
            <div class="page-banner-content text-center">
                <h2 class="title">Chi Tiết Đơn Hàng</h2>
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết đơn hàng</li>
                </ol>
            </div>
        </div>
    </div>
    <!--Page Banner End-->

    <!--Cart Start-->
    <div class="cart-page section-padding-5">
        <div class="container">

            <div class="cart-table table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="image">Hình Ảnh</th>
                            <th class="product">Sản Phẩm</th>
                            <th class="price">Giá</th>
                            <th class="quantity" style="width:10%">Số Lượng</th>
                            <th class="total">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $Total = 0;
                        $ship = 0;
                        $total_bill = 0;
                        $discount = 0; ?>
                        @foreach ($list_order_info as $key => $order_info)
                            <?php $Total += $order_info->Price * $order_info->QuantityBuy; ?>
                            <tr class="product-item">
                                <?php $image = json_decode($order_info->ImageName)[0]; ?>
                                <td class="image">
                                    <a href="{{ URL::to('/shop-single/' . $order_info->idProduct) }}"><img
                                            src="{{ asset('/storage/kidadmin/images/product/' . $image) }}"
                                            alt=""></a>
                                </td>
                                <td class="product">
                                    <a
                                        href="{{ URL::to('/shop-single/' . $order_info->idProduct) }}">{{ $order_info->ProductName }}</a>
                                    <span>Mã sản phẩm: {{ $order_info->idProduct }}</span>
                                    <span>{{ $order_info->PropertyPro }}</span>
                                </td>
                                <td class="price">{{ number_format($order_info->Price, 0, ',', '.') }}đ</td>
                                <td class="quantity">{{ $order_info->QuantityBuy }}</td>
                                <td class="total">
                                    {{ number_format($order_info->Price * $order_info->QuantityBuy, 0, ',', '.') }}đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>


                <div class="container__address">
                    <div class="container__address-content">
                        <div class="container__address-content-hd justify-content-between">
                            <div><i class="container__address-content-hd-icon fa fa-map-marker"></i>Địa Chỉ Nhận Hàng</div>
                        </div>
                        <ul class="shipping-list list-address">
                            <li class="cus-radio align-items-center" style="font-size:20px;">
                                <span class="mr-2">{{ $order->CustomerName }}</span>
                                <span class="mr-2">{{ $order->PhoneNumber }}</span>
                                <span>{{ $order->Address }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="cart-totals shop-single-content">
                        <div class="cart-title">
                            <h4 class="title">Tổng giỏ hàng</h4>
                        </div>
                        <div class="cart-total-table mt-25" style="position:relative;">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Tổng tiền hàng</td>
                                        <td class="text-right">{{ number_format($Total, 0, ',', '.') }}đ</td>
                                    </tr>
                                    @if ($Total < 1000000)
                                        @php
                                            $ship = '50000';
                                            $total_bill = $Total + $ship;
                                        @endphp
                                    @else
                                        @php
                                            $ship = 'Miễn phí';
                                            $total_bill = $Total;
                                        @endphp
                                    @endif
                                    <tr class="shipping">
                                        <td>Phí vận chuyển (Miễn phí vận chuyển cho đơn hàng trên 1.000.000đ)</td>
                                        <td class="text-right">
                                            @if ($ship > 0)
                                                {{ number_format(floatval($ship), 0, ',', '.') }}
                                            @endif
                                        </td>
                                    </tr>

                                    @if ($order->Voucher != '')
                                        <tr>
                                            <td width="70%">Mã giảm giá</td>
                                            @php
                                                $Voucher = explode('-', $order->Voucher);
                                                $VoucherCondition = $Voucher[0];
                                                $VoucherNumber = $Voucher[1];
                                                if ($VoucherCondition == 1) {
                                                    $discount = ($Total / 100) * $VoucherNumber;
                                                } else {
                                                    $discount = $VoucherNumber;
                                                    if ($discount > $Total) {
                                                        $discount = $Total;
                                                    }
                                                }

                                                $total_bill = $total_bill - $discount;
                                                if ($total_bill < 0) {
                                                    $total_bill = $ship;
                                                }
                                            @endphp




                                            <td class="text-right totalBill">- {{ number_format($discount, 0, ',', '.') }}đ
                                            </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td width="70%">Thành tiền</td>
                                        <td class="text-right totalBill">{{ number_format($total_bill, 0, ',', '.') }}đ</td>
                                    </tr>

                                    <input type="hidden" class="subtotal" value="{{ $Total }}">
                                    <input type="hidden" name="TotalBill" class="totalBillVal"
                                        value="{{ $total_bill }}">
                                    <input type="hidden" name="idVoucher" class="idVoucher" value="0">
                                </tbody>
                            </table>
                            @if ($order->Payment == 'vnpay' || $order->Status == '2')
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
    <!--Cart End-->
@endsection
