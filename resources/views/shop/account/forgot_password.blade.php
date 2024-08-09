@extends('page_layout')

@section('content')

<!--Page Banner Start-->
<div class="page-banner" style="background-image: url(/page/images/banner/banner9.jpg);">
    <div class="container">
        <div class="page-banner-content text-center">
            <h2 class="title">Quên Mật Khẩu</h2>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="{{ url('/home') }}">Trang Chủ</a></li>
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
                    @if(Session::has('message'))
                    <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif
                @if(Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif  
                    <div class="login-register-form">
                        <form method="POST" action="{{ url('/submit-forgot-password') }}" id="form-verify-email">
                            @csrf
                           

                            <div class="form-group mt-15 text-left col-md-8">
                                <label for="email">Email</label>
                                <input id="email" type="text" name="email" class="form-control">
                                @error('email')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
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
                        <form method="POST" action="{{ url('/submit-reset-password') }}" id="form-reset-password">
                            @csrf
                            <?php
                            $message = Session::get('reset_message');
                            $error = Session::get('reset_error');
                            if($message){
                                echo '<span class="text-success">'.$message.'</span>';
                                Session::put('reset_message', null);
                            } else if($error){
                                echo '<span class="text-danger">'.$error.'</span>';
                                Session::put('reset_error', null);
                            }
                        ?>  

                            <div class="form-group mt-15 text-left">
                                <label for="token">Mã xác thực</label>
                                <input id="token" type="text" name="token" class="form-control">
                                @error('token')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mt-15 text-left">
                                <label for="username">Tên tài khoản</label>
                                <input id="username" type="text" name="username" class="form-control">
                                @error('username')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mt-15 text-left">
                                <label for="new_password">Mật khẩu mới</label>
                                <input id="new_password" type="password" name="new_password" class="form-control" >
                                @error('new_password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mt-15 text-left">
                                <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                                <input id="new_password_confirmation" type="password" name="new_password_confirmation" class="form-control">
                                @error('new_password_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
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
