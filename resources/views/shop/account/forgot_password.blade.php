@extends('page_layout')
@section('content')

<?php use Illuminate\Support\Facades\Session; ?>

<!--Page Banner Start-->
<div class="page-banner" style="background-image: url(/page/images/oso.png);">
    <div class="container">
        <div class="page-banner-content text-center">
            <h2 class="title">Quên Mật Khẩu</h2>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="{{URL::to('/home')}}">Trang Chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Quên Mật Khẩu</li>
            </ol>
        </div>
    </div>
</div>
<!--Page Banner End-->

<div class="login-page section-padding-6">
    <div class="container">
        <div class="row">
            <!-- Phần xác thực email -->
            <div class="col-lg-6">
                <div class="login-register-content">
                    <h4 class="title">Nhập email nhận mã xác thực!!!</h4>

                    <div class="login-register-form">
                        <form method="POST" action="{{URL::to('/submit-forgot-password')}}" id="form-verify-email">
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
                            <div class="form-group mt-15 text-left col-md-8">
                                <a for="email">Tên tài khoản</a>
                                <input id="email" type="text" name="email" class="form-control">
                                <span class="text-danger"></span>
                            </div>

                            <div class="form-group mt-15 text-left col-md-8">
                                <input type="submit" class="btn btn-primary btn-block" value="Xác thực"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Phần đặt lại mật khẩu -->
            <div class="col-lg-6">
                <div class="login-register-content">
                    <h4 class="title">Đặt Lại Mật Khẩu</h4>

                    <div class="login-register-form">
                        <form method="POST" action="{{URL::to('/submit-reset-password')}}" id="form-reset-password">
                            @csrf
                            <?php
                                $reset_message = Session::get('reset_message');
                                $reset_error = Session::get('reset_error');
                                if($reset_message){
                                    echo '<span class="text-success">'.$reset_message.'</span>';
                                    Session::put('reset_message', null);
                                } else if($reset_error){
                                    echo '<span class="text-danger">'.$reset_error.'</span>';
                                    Session::put('reset_error', null);
                                }
                            ?>
                            <div class="form-group mt-15 text-left">
                                <label for="reset_token">Mã xác thực</label>
                                <input id="reset_token" type="text" name="reset_token" class="form-control">
                                <span class="text-danger"></span>
                            </div>
                            <div class="form-group mt-15 text-left">
                                <label for="new_password">Mật khẩu mới</label>
                                <input id="new_password" type="password" name="new_password" class="form-control">
                                <span class="text-danger"></span>
                            </div>
                            <div class="form-group mt-15 text-left">
                                <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                                <input id="new_password_confirmation" type="password" name="new_password_confirmation" class="form-control">
                                <span class="text-danger"></span>
                            </div>

                            <div class="form-group mt-15 text-left">
                                <input type="submit" class="btn btn-primary btn-block" value="Đặt lại mật khẩu"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
