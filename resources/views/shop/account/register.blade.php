@extends('page_layout')
@section('content')

<?php use Illuminate\Support\Facades\Session; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<!--Page Banner Start-->
<div class="page-banner" style="background-image: url(/page/images/banner/banner9.jpg);">
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
                                <label for="username">Tên tài khoản</label>
                                <input id="username" type="text" name="username" value="{{ old('username') }}" required>
                                @if ($errors->has('username'))
                                    <span class="text-danger">{{ $errors->first('username') }}</span>
                                @endif
                            </div>

                            <div class="form-group mt-15">
                                <label for="email">Email tài khoản</label>
                                <input id="email" type="text" name="email" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <div class="form-group mt-15 ">
                                <label for="password">Mật khẩu</label>
                                <input id="password" type="password" name="password" required >
                              
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>

                            <div class="form-group mt-15 ">
                                <label for="password_confirmation">Xác nhận mật khẩu</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required>
                               
                            </div>

                            <div class="form-group mt-15">
                                <input type="submit" class="btn btn-primary btn-block" style="border-radius: 20px;" value="Đăng ký"/>
                            </div>
                            
                            <div class="form-group mt-15">
                                <a>Bạn đã có tài khoản?</a>
                                <a href="{{URL::to('/login')}}" class="text-priamry">Đăng nhập ngay </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Register End-->

@endsection
