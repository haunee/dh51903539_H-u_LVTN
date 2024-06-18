<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>KidAngel Dashboard | Register</title>
    <link rel="shortcut icon" href="{{asset('/kidadmin/images/favicon.ico')}}" />
    <link rel="stylesheet" href="{{asset('/kidadmin/css/backend-plugin.min.css')}}">
</head>
<body class=" ">
    <div id="loading">
        <div id="loading-center">
        </div>
    </div>
    <div class="wrapper">
        <section class="login-content">
            <div class="container">
                <div class="row align-items-center justify-content-center height-self-center">
                    <div class="col-lg-8">
                        <div class="card auth-card">
                            <div class="card-body p-0">
                                <div class="d-flex align-items-center auth-content">
                                    <div class="col-lg-7 align-self-center">
                                        <div class="p-3">
                                            <h2 class="mb-2">Đăng Ký</h2>
                                            <p>Đăng ký tài khoản mới.</p>
                                            
                                            <!-- Hiển thị thông báo thành công -->
                                            @if(session('message'))
                                                <div class="alert alert-success">
                                                    {{ session('message') }}
                                                </div>
                                            @endif
                                            
                                            <!-- Hiển thị lỗi validation -->
                                            @if($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            
                                            <form action="{{ url('submit-admin-register') }}" method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="floating-label form-group">
                                                            <input class="floating-input form-control" type="text" placeholder=" " name="AdminUser" required>
                                                            <label>Tên Đăng Nhập</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="floating-label form-group">
                                                            <input class="floating-input form-control" type="password" placeholder=" " name="AdminPass" required>
                                                            <label>Mật Khẩu</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="floating-label form-group">
                                                            <input class="floating-input form-control" type="password" placeholder=" " name="AdminPass_confirmation" required>
                                                            <label>Nhập Lại Mật Khẩu</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group mt-15">
                                                   <input type="submit"  class="btn btn-primary btn-block" value="Đăng Ký"/>
                                                </div>

                                                <a href="{{ url('/admin') }}" class="btn btn-outline-secondary btn-block">Đăng Nhập</a>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 content-right">
                                        <img src="/kidadmin/images/01.png" class="img-fluid image-right" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="{{asset('/kidadmin/js/backend-bundle.min.js')}}"></script>
    <script src="{{asset('/kidadmin/js/table-treeview.js')}}"></script>
    <script src="{{asset('/kidadmin/js/app.js')}}"></script>
</body>
</html>
