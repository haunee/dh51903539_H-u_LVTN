<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Mail\VerifyAccount;
use Illuminate\Support\Facades\Mail;

//PAGE
Route::get('/', function () {
    return view('shop.home');
});
Route::get('/home', function () {
    return view('shop.home');
});

//ACCOUNT customer
//chuyển trang xác nhận  //xac nhận mã
Route::get('/verify', [CustomerController::class, 'verify']);
Route::post('/verify-token', [CustomerController::class, 'verifyToken']);


//đăng xuất
Route::get('/logout', [CustomerController::class, 'logout']);
//dăng nhap
Route::get('/login', [CustomerController::class, 'login']);
Route::post('/submit-login', [CustomerController::class, 'submit_login']);
//dăng kí
Route::get('/register', [CustomerController::class, 'register']);
Route::post('/submit-register', [CustomerController::class, 'submit_register']);
//xem tk
Route::get('/profile', [CustomerController::class, 'show_account_info']);
Route::post('/profile', [CustomerController::class, 'check_profile']);
//sửa hồ sơ
Route::post('/edit-profile', [CustomerController::class, 'edit_profile']);
//đoi mk
Route::get('/change-password', [CustomerController::class, 'change_password']);
Route::post('/submit-change-password', [CustomerController::class, 'submit_change_password']);



//chuyển trang quên mk
Route::get('/forgot-password', [CustomerController::class, 'forgot_password']);


//submit xác thực
Route::post('/submit-forgot-password', [CustomerController::class, 'submit_forgot_password']);