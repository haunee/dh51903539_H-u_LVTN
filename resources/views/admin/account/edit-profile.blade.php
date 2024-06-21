@extends('admin_layout')
@section('content_dash')
    <?php use Illuminate\Support\Facades\Session; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="iq-edit-list usr-edit">
                                <ul class="iq-edit-profile d-flex nav nav-pills">
                                    <li class="col-md-12 p-0">
                                        <a class="nav-link active text-white" data-toggle="pill"
                                            href="#personal-information">
                                            Chỉnh Sửa Hồ Sơ
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="iq-edit-list-data">
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
                                <?php
                                $message = Session::get('message');
                                $error = Session::get('error');
                                if ($message) {
                                    echo '<span class="text-success">' . $message . '</span>';
                                    Session::put('message', null);
                                } elseif ($error) {
                                    echo '<span class="text-danger">' . $error . '</span>';
                                    Session::put('error', null);
                                }
                                ?>
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <div class="iq-header-title">
                                            <h4 class="card-title">Chỉnh Sửa Hồ Sơ</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{ URL::to('/submit-edit-adprofile') }}"
                                            id="form-profile-edit" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group row align-items-center">
                                                <div class="col-md-12 ">
                                                    <div class="profile-img-edit ">
                                                        <div class="profile-img-edit">
                                                            <div class="crm-profile-img-edit">
                                                                @if (Session::get('Avatar') != null)
                                                                    <img class="crm-profile-pic rounded-circle avatar-100 replace-avt"
                                                                        src="/storage/kidadmin/images/user/{{ Session::get('Avatar') }}"
                                                                        alt="profile-pic">
                                                                @else
                                                                    <img class="crm-profile-pic rounded-circle avatar-100 replace-avt"
                                                                        src="/kidadmin/images/user/12.jpg"
                                                                        alt="profile-pic">
                                                                @endif
                                                                <div class="crm-p-image bg-primary">
                                                                    <label for="Avatar" style="cursor:pointer;"><span
                                                                            class="fas fa-edit mr-0"></span></label>
                                                                    <input type="file" class="file-upload" id="Avatar"
                                                                        name="Avatar" onchange="loadPreview(this)"
                                                                        accept="image/*" style="display: none;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-danger alert-img ml-3 mt-3"></div>
                                            </div>
                                            <div class="row align-items-center">
                                                <div class="form-group col-sm-6">
                                                    <label class="required" for="fname">Họ Và Tên:</label>
                                                    <input type="text" name="AdminName" class="form-control"
                                                        id="fname"
                                                        value="{{ old('AdminName', Session::get('AdminName')) }}">
                                                    @error('AdminName')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label class="required" for="nmphone">Số Điện Thoại:</label>
                                                    <input type="text" name="NumberPhone" class="form-control"
                                                        pattern="\d*" maxlength="10" oninput="validateNumberPhone(this)"
                                                        id="nmphone"
                                                        value="{{ old('NumberPhone', Session::get('NumberPhone')) }}">
                                                    @error('NumberPhone')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label class="required" for="editmail">Email:</label>
                                                    <input type="email" name="Email" class="form-control" id="editmail"
                                                        value="{{ old('Email', Session::get('Email')) }}">

                                                    @error('Email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror

                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label class="required" for="address">Địa Chỉ:</label>
                                                    <input type="text" name="Address" class="form-control" id="address"
                                                        value="{{ old('Address', Session::get('Address')) }}">

                                                    @error('Address')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <input type="submit" class="btn btn-primary mr-2" value="Lưu" />
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

    <script>
        //không nhập chữ
        function validateNumberPhone(input) {
            input.value = input.value.replace(/[^0-9]/g, ''); // Loại bỏ ký tự không phải là số
            if (input.value.length > 10) {
                input.value = input.value.slice(0, 10); // Giới hạn độ dài tối đa là 10
            }
        }

        function loadPreview(input) {
            var file = input.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.replace-avt').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
