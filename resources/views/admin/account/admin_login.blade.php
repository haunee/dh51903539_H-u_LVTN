<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>KidAngel Dashboard | Login</title>
      
      <!-- Favicon -->
      <link rel="shortcut icon" href="{{asset('/kidadmin/images/favicon.ico')}}" />
      <link rel="stylesheet" href="{{asset('/kidadmin/css/backend-plugin.min.css')}}">
      <link rel="stylesheet" href="{{asset('/kidadmin/css/backend.css?v=1.0.0')}}">
      
  </head>
  <body class=" ">
    <!-- loader Start -->
    <div id="loading">
          <div id="loading-center">
          </div>
    </div>
    <!-- loader END -->
   <?php use Illuminate\Support\Facades\Session; ?>
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
                                 <h2 class="mb-2">Đăng Nhập</h2>
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
                             
                                 <form action="{{URL::to('/submit-admin-login')}}" method="POST">
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
                                    </div>
                                    <input type="submit"  class="btn btn-primary btn-block" value="Đăng Nhập"/>
                                    <div class="mt-3">
                                        <a href="{{ URL::to('/admin-forgotpass') }}"  class="text-primary">Quên Mật Khẩu</a>
                                    </div>
                                 </form>
                              </div>
                           </div>
                           <div class="col-lg-5 content-right">
                              <img src="/kidadmin/images/8.png" class="img-fluid image-right" alt="">
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      </div>
    
    <!-- Backend Bundle JavaScript -->
    <script src="{{asset('/kidadmin/js/backend-bundle.min.js')}}"></script>
  <!-- app JavaScript -->
    <script src="{{asset('/kidadmin/js/app.js')}}"></script>

  </body>
</html>
