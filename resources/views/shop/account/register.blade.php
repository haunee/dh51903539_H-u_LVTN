@extends('page_layout')
@section('content')

<?php use Illuminate\Support\Facades\Session; ?>

<!--Page Banner Start-->
<div class="page-banner" style="background-image: url(/page/images/oso.png);">
    <div class="container">
        <div class="page-banner-content text-center">
            <h2 class="title">Đăng Ký</h2>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="{{URL::to('/home')}}">Trang Chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Đăng Ký</li>
            </ol>
        </div>
    </div>
</div>
<!--Page Banner End-->


<!--Register Start-->
<div class="register-page section-padding-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="login-register-content">
                    <h4 class="title">Tạo Tài Khoản Mới</h4>

                    <div class="login-register-form">
                        <form method="POST" action="{{URL::to('/submit-register')}}" id="form-register">
                            @csrf
                            <?php
                                $message = Session::get('message');
                                $error = Session::get('error');
                                if($message){
                                    echo '<span class="text-success">'.$message.'</span>';
                                    Session::put('message', null);
                                } else if($error){
                                    echo '<span class="text-danger">'.$error.'</span>';
                                    Session::put('error', null);
                                }
                            ?>  
                               <div class="form-group mt-15">
                                <a for="username">Tên tài khoản</a>
                                <input id="username" type="text" name="username" required>
                                <span class="text-danger"></span>
                            </div>
                            <div class="form-group mt-15">
                                <a for="email">email tài khoản</a>
                                <input id="email" type="text" name="email" required>
                                <span class="text-danger"></span>
                            </div>
                            <div class="form-group mt-15">
                                <a for="password">Mật khẩu</a>
                                <input id="password" type="password" name="password" required>
                                <span class="text-danger"></span>
                            </div>
                            <div class="form-group mt-15">
                                <a for="password_confirmation">Xác nhận mật khẩu</a>
                                <input id="password_confirmation" type="password" name="password_confirmation" required>
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group mt-15">
                                <input type="submit" class="btn btn-primary btn-block" value="Đăng ký"/>
                            </div>
                            <div class="form-group mt-15">
                                <a>Bạn đã có tài khoản?</a>
                                <a href="{{URL::to('/login')}}" class="btn btn-dark btn-block">Đăng nhập</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Register End-->

<!-- Validate form đăng ký -->
<script>
    window.scrollBy(0,300);

    $(document).ready(function(){  
        $('.register').on('click',function(){
            $("#form-register").validate({
                rules: {
                    email: {
                        required: true,
                        minlength: 8
                    },
                    password: {
                        required: true,
                        minlength: 8
                    },
                    password_confirmation: {
                        required: true,
                        minlength: 8,
                        equalTo: "#password"
                    }
                },

                messages: {
                    email: {
                        required: "Vui lòng nhập trường này",
                        minlength: "Nhập mật khẩu cũ tối thiểu 8 ký tự"
                    },
                    password: {
                        required: "Vui lòng nhập trường này",
                        minlength: "Nhập mật khẩu mới tối thiểu 8 ký tự"
                    },
                    password_confirmation: {
                        required: "Vui lòng nhập trường này",
                        minlength: "Nhập lại mật khẩu mới tối thiểu 8 ký tự",
                        equalTo: "Nhập lại mật khẩu không trùng khớp"
                    }
                },

                submitHandler: function(form) {
                    let formData = new FormData($('#form-register')[0]);

                    $.ajax({
                        url: APP_URL + '/submit-register',
                        type: 'POST',   
                        contentType: false,
                        processData: false,   
                        cache: false,        
                        data: formData,
                        success:function(data){
                            $('.alert-password').html(data);
                            $('#email').val("");
                            $('#password').val("");
                            $('#password_confirmation').val("");
                        }
                    });
                }
            });
        });
    });
</script>

@endsection