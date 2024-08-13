@extends('page_layout')
@section('content')
    <style>
        .centered-card {
            margin: 0 auto;
            /* Tự động căn giữa theo chiều ngang */
            max-width: 700px;
            /* Đặt chiều rộng tối đa để đảm bảo card không quá rộng */
        }
    </style>

    <!-- Page Banner Start -->
    <div class="page-banner" style="background-image: url(/page/images/banner/banner9.jpg);">
        <div class="container">
            <div class="page-banner-content text-center">
                <h2 class="title">Tài khoản của tôi</h2>
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tài khoản của tôi</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- Page Banner End -->

    <!-- My Account Start -->
      
        <div class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <form id="form-edit-profile" class="d-flex flex-wrap p-0 mt-3" action="{{ url('/profile') }}" method="POST"  enctype="multipart/form-data">
                        @csrf
                    <!-- Sidebar Start -->
                    <div class="col-lg-3 col-md-4">
                        <div class="card card-profile mt-4">
                            <div class="card-body text-center">

                               
                                <div class="profile-img mb-3">


                                    <div class="profile-img-edit mt-3">
                                        <div class="crm-profile-img-edit">
                                            @if ($customer->Avatar != null)
                                                <img class="crm-profile-pic rounded-circle avatar-100 replace-avt"
                                                    alt="profile-image"
                                                    src="/storage/page/images/customer/{{ $customer->Avatar }}">
                                            @else
                                                <img class="crm-profile-pic rounded-circle avatar-100 replace-avt"alt="profile-image"
                                                    src="/storage/page/images/user/01.jpg">
                                            @endif
                                            <div class="crm-p-image bg-primary position-absolute">
                                                <label for="Avatar">
                                                    <span class="ti-pencil upload-button d-block"></span>
                                                </label>
                                                <input type="file" class="file-upload" id="Avatar" name="Avatar"
                                                    onchange="loadPreview(this)" accept="image/*">
                                            </div>
                                        </div>

                                    </div>

                                </div>
                               
                              

                                <a href="{{ URL::to('/change-password') }}" class="btn btn-primary mt-2">Đổi mật khẩu</a>

                            </div>
                        </div>
                    </div>
                    <!-- Sidebar End -->

                    <!-- Profile Content Start -->
                    <div class="col-lg-9 col-md-8 mx-auto">
                        <div class="card mt-4 centered-card">
                            <div class="card-body">
                                <h5 class="card-title">Thông tin hồ sơ</h5>
                                @if (session('message'))
                                    <div class="text-success mb-3">{{ session('message') }}</div>
                                @endif
                                @if ($errors->any())
                                    <div class="text-danger mb-3">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @csrf
                                <div class="form-group mb-3">
                                    <label for="username" class="form-label">Tên tài khoản</label>
                                    <input id="username" name="username" class="form-control" type="text"
                                        value="{{ $customer->username }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="PhoneNumber" class="form-label">Số Điện Thoại</label>
                                    <input id="PhoneNumber" name="PhoneNumber" class="form-control" type="text"
                                        pattern="\d*" maxlength="10" oninput="validateNumberPhone(this)"
                                        value="{{ $customer->PhoneNumber }}">
                                </div>
                                <button class="btn btn-primary" type="submit"><i class="fa fa-edit"></i> Sửa Hồ Sơ</button>
                            </div>
                        </div>
                    </div>
                    <!-- Profile Content End -->
                </form>

                </div>

            </div>
        </div>

    <!-- My Account End -->

    <script src="{{ asset('/page/js/jquery.validate.min.js') }}"></script>
    <script>
        function validateNumberPhone(input) {
            input.value = input.value.replace(/[^0-9]/g, ''); // Loại bỏ ký tự không phải là số
            if (input.value.length > 10) {
                input.value = input.value.slice(0, 10); // Giới hạn độ dài tối đa là 10
            }
        }

        function loadPreview(input) {
            var data = $(input)[0].files; //this file data
            $.each(data, function(index, file) {
                if (/(\.|\/)(gif|jpeg|png|jpg|svg)$/i.test(file.type) && file.size < 2000000) {
                    var fRead = new FileReader();
                    fRead.onload = (function(file) {
                        return function(e) {
                            $('.replace-avt').attr('src', e.target.result);
                        };
                    })(file);
                    fRead.readAsDataURL(file);
                    $('.alert-img').html($('#Avatar').val().replace(/^.*[\\\/]/, ''));
                } else {
                    document.querySelector('#Avatar').value = '';
                    $('.alert-img').html("Tệp hình ảnh phải có định dạng .gif, .jpeg, .png, .jpg, .svg dưới 2MB");
                }
            });
        }
    </script>
@endsection
