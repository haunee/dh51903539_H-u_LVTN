@extends('admin_layout')
@section('content_dash')

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('path/to/your/custom/styles.css') }}"> <!-- Thay thế bằng đường dẫn đến file CSS của bạn -->

<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-transparent">
                    <div class="card-body p-0">
                        <div class="profile-image position-relative">
                            {{-- <img src="/kidadmin/images/profile/pro1.jpg" class="img-fluid rounded w-100" alt="profile-image"> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-4">
                <div class="card card-profile">
                    <div class="card-body text-center">
                        <div class="profile-img mb-3">
                            @if (Session::get('Avatar'))
                                <img src="/storage/kidadmin/images/user/{{ Session::get('Avatar') }}" class="img-fluid rounded-circle avatar-150" alt="profile-image">
                            @else
                                <img src="/kidadmin/images/user/12.jpg" class="img-fluid rounded-circle avatar-150" alt="profile-image">
                            @endif
                        </div>
                        <h4 class="mb-1">{{ Session::get('AdminUser') }}</h4>
                        <p class="text-muted">{{ Session::get('AdminName') }}</p>
                        <a href="{{ URL::to('/edit-profile') }}" class="btn btn-primary mt-2">Sửa hồ sơ</a>
                        <a href="{{ URL::to('/change-adpassword') }}" class="btn btn-warning mt-2">Đổi mật khẩu</a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin liên hệ</h5>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user mr-3"></i>
                                    <p class="mb-0">{{ Session::get('AdminName') }}</p>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-phone mr-3"></i>
                                    <p class="mb-0">{{ Session::get('NumberPhone') }}</p>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-envelope mr-3"></i>
                                    <p class="mb-0">{{ Session::get('email') }}</p>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt mr-3"></i>
                                    <p class="mb-0">{{ Session::get('Address') }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
