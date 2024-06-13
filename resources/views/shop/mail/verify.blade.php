

@extends('page_layout')
@section('content')

<?php use Illuminate\Support\Facades\Session; ?>

<!--Page Banner Start-->
<div class="page-banner" style="background-image: url(/page/images/oso.png);">
    <div class="container">
        <div class="page-banner-content text-center">
            <h2 class="title">Nhập Mã Xác Thực</h2>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="{{URL::to('/home')}}">Trang Chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Nhập Mã Xác Thực</li>
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
                    <h4 class="title">Nhập Mã Xác Thực</h4>

                   
                        <form method="POST" action="{{ url('verify-token') }}">
                            @csrf
                            <input type="text" name="token" placeholder="Enter verification code" required>
                            <div class="form-group mt-15">
                                <input type="submit" class="btn btn-primary btn-block" value="xác nhận"/>
                            </div>
                        </form>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                
                </div>
            </div>
        </div>
    </div>
</div>
<!--Register End-->


@endsection