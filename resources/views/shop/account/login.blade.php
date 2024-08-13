@extends('page_layout')
@section('content')

<?php use Illuminate\Support\Facades\Session; ?>

<!--Page Banner Start-->
<div class="page-banner" style="background-image: url(/page/images/banner/banner9.jpg);">
    <div class="container">
        <div class="page-banner-content text-center">
            <h2 class="title">Đăng Nhập</h2>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="{{URL::to('/home')}}">Trang Chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Đăng Nhập</li>
            </ol>
        </div>
    </div>
</div>
<!--Page Banner End-->

<div class="login-page section-padding-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="login-register-content">
                    <h4 class="title">Đăng Nhập Tài Khoản</h4>

                    <div class="login-register-form">
                        <form method="POST" action="{{URL::to('/submit-login')}}" id="form-login">
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
                                <input id="username" type="text" name="username">
                                <span class="text-danger"></span>
                            </div>
                            <div class="form-group mt-15">
                                <label for="passsword">Mật khẩu</label>
                                <input id="password" type="password" name="password">
                                <span class="text-danger"></span>
                            </div>
                            <div>
                                <a href="{{ url('/forgot-password') }}">Quên mật khẩu?</a>
                            </div>
                            <div class="form-group mt-15">
                                <input type="submit" class="btn btn-primary btn-block"  style="border-radius: 20px;"value="Đăng nhập"/>
                            </div>
                         
                            <div class="form-group mt-15">
                                <a>Bạn chưa có tài khoản?</a>
                                <a href="{{ URL::to('/register') }}"style="text-decoration: underline;" class="text-primary">Đăng ký ngay</a>

                                {{-- <a href="{{URL::to('/register')}}" type="submit" class="btn btn-dark btn-block">Đăng ký ngay</a> --}}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection