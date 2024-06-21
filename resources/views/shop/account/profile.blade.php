@extends('page_layout')
@section('content')

<!-- Page Banner Start -->
<div class="page-banner" style="background-image: url(/page/images/oso.png);">
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
<div class="register-page section-padding-5">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-md-4">
                <div class="my-account-menu mt-30">
                    <ul class="nav account-menu-list flex-column">
                        <li>
                            <a class="active"><i class="fa fa-user"></i> Hồ Sơ</a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/change-password') }}"><i class="fa fa-key"></i> Đổi Mật Khẩu</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-xl-9 col-md-8">
                <div class="tab-content my-account-tab mt-30" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-account">
                        <div class="tab-content my-account-tab" id="pills-tabContent">
                            <div class="tab-pane fade active show">
                                <div class="my-account-address account-wrapper">
                                    <div class="row">
                                        <div class="col-md-12" style="border-bottom: solid 1px #efefef;">
                                            <h4 class="account-title" style="margin-bottom: 0;">Hồ Sơ Của Tôi</h4>
                                            <h5 style="color: #666;">Quản lý thông tin hồ sơ để bảo mật tài khoản</h5>

                                            
                                        </div>

                                    
                                        @if(session('message'))
                                        <div class="  text-success mt-0">{{ session('message') }}</div>
                                        @endif
                                        @if($errors->any())
                                        <div class="text-danger mt-3">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif
                                    
                                        <form id="form-edit-profile" class="d-flex flex-wrap p-0 mt-3"  action="{{ url('/profile') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                           
                                            <div class="col-md-8">
                                                <div class="account-address p-3 bg-light rounded">
                                                    <div>
                                                        <a >Email:</a>
                                                        <a >{{ $customer->email }}</a>
                                                    </div>
                                                    <div class="form-group mb-3 d-flex align-items-center">
                                                        <label for="username" class="profile__info-body-left-item-title me-3">Tên tài khoản</label>
                                                        <input id="username" name="username" class="form-control ms-3" type="text" value="{{ $customer->username }}">
                                                        
                                                    </div>
                                                    <div class="form-group mb-3 d-flex align-items-center">
                                                        <label for="PhoneNumber" class="profile__info-body-left-item-title me-3">Số Điện Thoại</label>
                                                        <input id="PhoneNumber" name="PhoneNumber" class="form-control ms-3" type="text" pattern="\d*" maxlength="10" oninput="validateNumberPhone(this)" value="{{ $customer->PhoneNumber }}">
                                                        
                                                    </div>
                                                    <button class="btn btn-primary edit-profile float-right"><i class="fa fa-edit"></i> Sửa Hồ Sơ</button>
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-flex align-items-center justify-content-center" style="border-left: solid 1px #efefef;">
                                                <div class="profile__info-body-right-avatar text-center">
                                                    <div class="profile-img-edit">
                                                        <div class="crm-profile-img-edit">
                                                            @if($customer->Avatar != null)
                                                            <img class="crm-profile-pic rounded-circle avatar-100 replace-avt" src="/storage/page/images/customer/{{$customer->Avatar}}"> 
                                                            @else <img class="crm-profile-pic rounded-circle avatar-100 replace-avt" src="/storage/page/images/user/01.jpg"> @endif 
                                                            <div class="crm-p-image bg-primary">
                                                                <label for="Avatar" style="cursor:pointer;"><span class="ti-pencil upload-button d-block"></span></label>
                                                                <input type="file" class="file-upload" id="Avatar" name="Avatar" onchange="loadPreview(this)" accept="image/*">
                                                            </div>
                                                        </div>                                          
                                                    </div>
                                                    <div class="text-danger alert-img mt-3"></div>
                                                    <div class="mt-3">
                                                        <span class="profile__info-body-right-avatar-condition-item">Dung lượng file tối đa 2MB</span>
                                                        <span class="profile__info-body-right-avatar-condition-item">Định dạng: .JPEG, .PNG</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- My Account End -->

<script src="{{ asset('/page/js/jquery.validate.min.js') }}"></script>

<script>
    window.scrollBy(0,300);
    function validateNumberPhone(input) {
            input.value = input.value.replace(/[^0-9]/g, ''); // Loại bỏ ký tự không phải là số
            if (input.value.length > 10) {
                input.value = input.value.slice(0, 10); // Giới hạn độ dài tối đa là 10
            }
        }

    function loadPreview(input){
        var data = $(input)[0].files; //this file data
        $.each(data, function(index, file){
            if(/(\.|\/)(gif|jpeg|png|jpg|svg)$/i.test(file.type) && file.size < 2000000 ){
                var fRead = new FileReader();
                fRead.onload = (function(file){
                    return function(e) {
                        $('.replace-avt').attr('src', e.target.result);
                    };
                })(file);
                fRead.readAsDataURL(file);
                $('.alert-img').html($('#Avatar').val().replace(/^.*[\\\/]/, ''));
            }else{
                document.querySelector('#Avatar').value = '';
                $('.alert-img').html("Tệp hình ảnh phải có định dạng .gif, .jpeg, .png, .jpg, .svg dưới 2MB");
            }
        });
    }
</script>

@endsection