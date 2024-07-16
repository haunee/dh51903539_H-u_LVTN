{{-- @extends('admin_layout')
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
                                <th>Thao tác</th>
                               
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            @foreach ($list_customer as $key => $customer)
                            <tr>
                                @if ($customer->Avatar)
                                <td class="text-center"><img class="rounded img-fluid avatar-40"
                                        src="/storage/page/images/customer/{{$customer->Avatar}}" alt="profile"></td>
                                @else
                                <td class="text-center"><img class="rounded img-fluid avatar-40"
                                        src="/page/images/customer/1.png" alt="profile"></td>
                                @endif
                                <td>{{$customer->username}}</td>
                                <td>{{$customer->email}}</td>
                                <td>{{$customer->PhoneNumber}}</td>
                                <td>
                                    <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#resetPasswordModal{{$customer->$idCustomer}}">Reset Password</a>
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








@endsection --}}




@extends('admin_layout')
@section('content_dash')


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
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            @foreach($list_customer as $customer)
                            <tr>
                                @if($customer->Avatar)
                                <td class="text-center">
                                    <img class="rounded img-fluid avatar-40"
                                         src="/storage/page/images/customer/{{$customer->Avatar}}" alt="profile">
                                </td>
                                @else
                                <td class="text-center">
                                    <img class="rounded img-fluid avatar-40"
                                         src="/page/images/customer/1.png" alt="profile">
                                </td>
                                @endif
                                <td>{{$customer->username}}</td>
                                <td>{{$customer->email}}</td>
                                <td>{{$customer->PhoneNumber}}</td>
                                <td>
                                    <!-- Nút để mở modal -->
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#resetPasswordModal{{$customer->idCustomer}}">
                                        Reset Password
                                    </button>
                                </td>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="resetPasswordModal{{$customer->idCustomer}}" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.reset-password', $customer->idCustomer) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="new_password" class="form-label">Mật khẩu mới</label>
                                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="new_password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                                                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                    <button type="submit" class="btn btn-primary">Cập nhật mật khẩu</button>
                                                </div>
                                            </form>
                                            
                                            
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (nếu chưa có) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection

