<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>KidAngel Dashboard | Forgot Password</title>
    <link rel="shortcut icon" href="{{ asset('/kidadmin/images/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('/kidadmin/css/backend-plugin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/kidadmin/css/backend.css?v=1.0.0') }}">
</head>
<body class=" ">
    <div id="loading">
        <div id="loading-center"></div>
    </div>
    <div class="wrapper">
        <section class="login-content">
            <div class="container">
                <div class="row align-items-center justify-content-center height-self-center">
                    <div class="col-lg-12">
                        <div class="card auth-card">
                            <div class="card-body p-0">
                                <div class="d-flex align-items-center auth-content">
                                    <div class="col-lg-6 align-self-center">
                                        <div class="p-3">
                                            <h3 class="mb-2">Quên Mật Khẩu</h2>
                                            <p>Nhập email để nhận mã xác nhận.</p>
                                            @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                            
                                            @if (session('message'))
                                                <div class="alert alert-success">
                                                    {{ session('message') }}
                                                </div>
                                            @endif
                                            
                                            @if (session('error'))
                                                <div class="alert alert-danger">
                                                    {{ session('error') }}
                                                </div>
                                            @endif
                                        

                                            <form action="{{ URL::to('/send-reset-code') }}" method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="floating-label form-group">
                                                            <input class="floating-input form-control" type="email" placeholder="Email " name="email" required>
                                                          
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="submit" class="btn btn-primary btn-block" value="Gửi Mã Xác Nhận"/>
                                            </form>
                                      
                                        </div>
                                    </div>
                                    <div class="col-lg-6 align-self-center">
                                        <div class="p-3">
                                            <h2 class="mb-2">Đặt Lại Mật Khẩu</h2>
                                            <p>Nhập mã xác nhận và mật khẩu mới.</p>
                                            <form action="{{ URL::to('/reset-password') }}" method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="floating-label form-group">
                                                            <input class="floating-input form-control" type="text" placeholder="Mã Xác Nhận " name="reset_code" required>
                                                            
                                                          
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="floating-label form-group">
                                                            <input class="floating-input form-control" type="password" placeholder=" Mật Khẩu Mới" name="password" required>
                                                            
                                                 
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="floating-label form-group">
                                                            <input class="floating-input form-control" type="password" placeholder=" Xác Nhận Mật Khẩu" name="password_confirmation" required>
                                                            
                                                           
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="submit" class="btn btn-primary btn-block" value="Đặt Lại Mật Khẩu"/>
                                                <br>
                                                        <button type="button" class="btn btn-light btn-block" onclick="window.location.href='{{ URL::to('/admin') }}'">Đăng nhập</button>
                                              </form>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 content-right">
                                        <!-- Thêm nội dung bổ sung nếu cần thiết -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="{{ asset('/kidadmin/js/backend-bundle.min.js') }}"></script>
    <script src="{{ asset('/kidadmin/js/app.js') }}"></script>
</body>
</html>
