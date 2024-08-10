@extends('admin_layout')
@section('content_dash')
    <?php use Illuminate\Support\Facades\Session; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
               
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
                                                                    <label for="Avatar" style="cursor:pointer;">
                                                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                                            <path fill-rule="evenodd" d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352l2.914-3.086Z" clip-rule="evenodd"/>
                                                                            <path fill-rule="evenodd" d="M19.846 4.318a2.148 2.148 0 0 0-.437-.692 2.014 2.014 0 0 0-.654-.463 1.92 1.92 0 0 0-1.544 0 2.014 2.014 0 0 0-.654.463l-.546.578 2.852 3.02.546-.579a2.14 2.14 0 0 0 .437-.692 2.244 2.244 0 0 0 0-1.635ZM17.45 8.721 14.597 5.7 9.82 10.76a.54.54 0 0 0-.137.27l-.536 2.84c-.07.37.239.696.588.622l2.682-.567a.492.492 0 0 0 .255-.145l4.778-5.06Z" clip-rule="evenodd"/>
                                                                          </svg>
                                                                          
                                                                    </label>

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
                                                    <label class="required" for="address">Địa Chỉ:</label>
                                                    <input type="text" name="Address" class="form-control" id="address"
                                                        value="{{ old('Address', Session::get('Address')) }}">

                                                    @error('Address')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <input type="submit" class="btn btn-primary mr-2" value="Lưu" />
                                            <a href="{{URL::to('/my-adprofile')}}" class="btn btn-light">Trở về</a>
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
