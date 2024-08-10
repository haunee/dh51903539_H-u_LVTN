@extends('admin_layout')
@section('content_dash')

<?php use Illuminate\Support\Facades\Session; ?>

<div class="content-page">
    <div class="container-fluid">
        <div class="row">
           
            <div class="col-lg-12">
                <div class="iq-edit-list-data">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
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

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Đổi Mật Khẩu</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{URL::to('/submit-change-adpassword')}}" id="form-password-change">
                                        @csrf
                                        <div class="form-group">
                                            <label for="password">Mật Khẩu Cũ:</label>
                                            <input type="password" name="password" class="form-control" id="password" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="newpassword">Mật Khẩu Mới:</label>
                                            <input type="password" name="newpassword" class="form-control" id="newpassword" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="newpassword_confirmation">Xác Nhận Mật Khẩu Mới:</label>
                                            <input type="password" name="newpassword_confirmation" class="form-control" id="newpassword_confirmation" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Đổi Mật Khẩu</button>
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

@endsection
