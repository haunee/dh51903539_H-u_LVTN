@extends('admin_layout')
@section('content_dash')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">Danh Sách Tài Khoản Khách Hàng ( Tổng: {{$count_customer}} khách hàng )</h4>
                        <p class="mb-0">Trang hiển thị danh sách tài khoản khách hàng, cung cấp thông tin về khách hàng, các chức năng và điều khiển. </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table class="data table mb-0 tbl-server-info">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>Avatar</th>
                                <th>Tên Tài Khoản</th>
                                <th>Email</th>
                                <th>Số Điện Thoại</th>
                               
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            @foreach($list_customer as $key => $customer)
                            <tr>
                                @if($customer->Avatar)
                                <td class="text-center"><img class="rounded img-fluid avatar-40"
                                        src="/storage/page/images/customer/{{$customer->Avatar}}" alt="profile"></td>
                                @else
                                <td class="text-center"><img class="rounded img-fluid avatar-40"
                                        src="/page/images/customer/1.png" alt="profile"></td>
                                @endif
                                <td>{{$customer->username}}</td>
                                <td>{{$customer->email}}</td>
                                <td>{{$customer->PhoneNumber}}</td>
                                
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