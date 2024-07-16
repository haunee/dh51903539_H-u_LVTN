@extends('admin_layout')
@section('content_dash')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-3">Danh Sách Tài Khoản Khách Hàng ( Tổng: {{ $count_customer }} khách hàng )</h4>
                            <p class="mb-0">Trang hiển thị danh sách tài khoản khách hàng, cung cấp thông tin về khách
                                hàng, các chức năng và điều khiển. </p>
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
                                @foreach ($list_customer as $customer)
                                    <tr>
                                        @if ($customer->Avatar)
                                            <td class="text-center">
                                                <img class="rounded img-fluid avatar-40"
                                                    src="/storage/page/images/customer/{{ $customer->Avatar }}"
                                                    alt="profile">
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <img class="rounded img-fluid avatar-40" src="/page/images/customer/1.png"
                                                    alt="profile">
                                            </td>
                                        @endif
                                        <td>{{ $customer->username }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->PhoneNumber }}</td>
                                        <td>
                                            <!-- Nút để mở modal -->
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#resetPasswordModal{{ $customer->idCustomer }}">
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
                                                    <div class="modal-body">
                                                        <div class="alert alert-success d-none" id="successMessage{{$customer->idCustomer}}"></div>
                                                        <div class="alert alert-danger d-none" id="errorMessage{{$customer->idCustomer}}"></div>
                                                        <form id="resetPasswordForm{{$customer->idCustomer}}">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="new_password{{$customer->idCustomer}}" class="form-label">Mật khẩu mới</label>
                                                                <input type="password" class="form-control" id="new_password{{$customer->idCustomer}}" name="new_password" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="confirm_password{{$customer->idCustomer}}" class="form-label">Xác nhận mật khẩu mới</label>
                                                                <input type="password" class="form-control" id="confirm_password{{$customer->idCustomer}}" name="new_password_confirmation" required>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Cập nhật mật khẩu</button>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                    </div>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($list_customer as $customer)
                document.getElementById('resetPasswordForm{{ $customer->idCustomer }}').addEventListener('submit',
                    function(event) {
                        event.preventDefault();

                        let form = event.target;
                        let formData = new FormData(form);
                        let customerId = {{ $customer->idCustomer }};
                        let newPassword = document.getElementById('new_password{{ $customer->idCustomer }}')
                            .value;
                        let confirmPassword = document.getElementById(
                            'confirm_password{{ $customer->idCustomer }}').value;
                        let successMessage = document.getElementById('successMessage' + customerId);
                        let errorMessage = document.getElementById('errorMessage' + customerId);

                        if (newPassword !== confirmPassword) {
                            errorMessage.classList.remove('d-none');
                            errorMessage.textContent = 'Mật khẩu mới không khớp nhau.';
                            return;
                        }

                        fetch('/admin/reset-password/' + customerId, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.message) {
                                    successMessage.classList.remove('d-none');
                                    errorMessage.classList.add('d-none');
                                    successMessage.textContent = data.message;
                                    form.reset();
                                } else {
                                    successMessage.classList.add('d-none');
                                    errorMessage.classList.remove('d-none');
                                    errorMessage.textContent = 'Đã xảy ra lỗi. Vui lòng thử lại.';
                                }
                            })
                            .catch(error => {
                                successMessage.classList.add('d-none');
                                errorMessage.classList.remove('d-none');
                                errorMessage.textContent = 'Đã xảy ra lỗi. Vui lòng thử lại.';
                                console.error('Error:', error);
                            });
                    });
                     // Reset form when modal is closed
                    document.getElementById('resetPasswordModal{{$customer->idCustomer}}').addEventListener('hidden.bs.modal', function () {
                    document.getElementById('resetPasswordForm{{$customer->idCustomer}}').reset();
                    document.getElementById('successMessage{{$customer->idCustomer}}').classList.add('d-none');
                    document.getElementById('errorMessage{{$customer->idCustomer}}').classList.add('d-none');
    });
            @endforeach
        });
    </script>
@endsection
