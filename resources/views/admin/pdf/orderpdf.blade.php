<!DOCTYPE html>
<html lang="en">
    @php
        use Carbon\Carbon;
    @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn Hàng #{{ $order->idOrder }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        .page-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .order-info {
            margin-bottom: 20px;
        }
        .order-info ul {
            list-style: none;
            padding: 0;
        }
        .order-info ul li {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-title">
            <h1>Chi Tiết Đơn Hàng</h1>
            <p>Đơn Hàng #{{ $order->idOrder }}</p>
        </div>

        <div class="order-info">
            <h2>Thông Tin Đơn Hàng</h2>
            <ul>
                <li><strong>Tên Khách Hàng:</strong> {{ $order->CustomerName }}</li>
                <li><strong>Số Điện Thoại:</strong> {{ $order->PhoneNumber }}</li>
                <li><strong>Địa Chỉ:</strong> {{ $order->Address }}</li>
                <li><strong>Ngày Đặt Hàng:</strong> {{ Carbon::parse($order->created_at)->format('d/m/Y') }}</li>
                <li><strong>Trạng Thái:</strong> {{$order->Payment=='vnpay' || $order->Status == '2' ? 'Đã thanh toán' : 'Chưa thanh toán' }}</li>
            </ul>
        </div>

        <div class="order-items">
            <h2>Danh Sách Sản Phẩm</h2>
            <table>
                <thead>
                    <tr>
                        <th>Hình Ảnh</th>
                        <th>Sản Phẩm</th>
                        <th>Giá</th>
                        <th>Số Lượng</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list_order_info as $item)
                        <tr>
                            <td>
                                <img src="{{ storage_path('app/public/kidadmin/images/product/' . json_decode($item->ImageName)[0]) }}" alt="" style="width: 80px; height: auto;">
                            </td>
                            <td>{{ $item->ProductName }}</td>
                            <td>{{ number_format($item->Price, 0, ',', '.') }}đ</td>
                            <td>{{ $item->QuantityBuy }}</td>
                            <td>{{ number_format($item->Price * $item->QuantityBuy, 0, ',', '.') }}đ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="order-summary">
            <h2>Tổng Kết Đơn Hàng</h2>
            <table>
                <tbody>
                    <tr>
                        <td>Tổng Tiền Hàng</td>
                        <td class="total">{{ number_format($Total, 0, ',', '.') }}đ</td>
                    </tr>
                    <tr>
                        <td>Phí Vận Chuyển</td>
                        <td class="total">{{ $ship == 'Miễn phí' ? 'Miễn phí' : number_format(floatval($ship), 0, ',', '.') . 'đ' }}</td>
                    </tr>
                    @if($discount > 0)
                        <tr>
                            <td>Mã Giảm Giá</td>
                            <td class="total">- {{ number_format($discount, 0, ',', '.') }}đ</td>
                        </tr>
                    @endif
                    <tr>
                        <td>Thành Tiền</td>
                        <td class="total">{{ number_format($total_bill, 0, ',', '.') }}đ</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
