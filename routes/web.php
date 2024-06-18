<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
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

//submit reset password
Route::post('/submit-reset-password', [CustomerController::class, 'submit_reset_password'])->name('submit_reset_password');







//ADMIN
//chuyển trang

Route::get('/admin', [AdminController::class, 'show_login'])->name('admin.login');

Route::get('/admin-register', [AdminController::class, 'admin_register'])->name('admin.register');

Route::post('/submit-admin-register', [AdminController::class, 'submit_admin_register'])->name('admin.submit_admin_registers');

Route::post('/submit-admin-login', [AdminController::class, 'submit_admin_login'])->name('admin.submit_admin_login');

Route::get('/admin-layout', [AdminController::class, 'admin_layout'])->name('admin.layout');

Route::get('/admin-logout', [AdminController::class, 'admin_logout'])->name('admin.logout');

Route::get('/my-adprofile', function () {
    return view('admin.account.my-adprofile');
})->name('admin.profile');




Route::get('/edit-profile', [AdminController::class, 'edit_profile']);




Route::get('/add-brand', [BrandController::class, 'add_brand'])->name('admin.add_brand');


