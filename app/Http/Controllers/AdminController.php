<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    //login
    public function show_login()
    {
        return view('admin.account.admin_login');
    }

    //register
    public function admin_register()
    {
        return view('admin.account.admin_register');
    }


    public function edit_profile()
    {
        return view("admin.account.edit-profile");
    }

    public function admin_logout()
    {     
        Session::put('idAdmin', null);
        return Redirect::to('/admin');
    }

    public  function submit_admin_register(Request $request)
    {
        // Validate dữ liệu
        // Validate dữ liệu
        $request->validate([
            'AdminUser' => 'required|unique:admin,AdminUser', // Đảm bảo tên bảng và cột chính xác
            'AdminPass' => 'required|confirmed|min:6',
        ]);

        // Tạo tài khoản mới
        $user = new Admin();
        $user->AdminUser = $request->AdminUser;
        $user->AdminPass = Hash::make($request->AdminPass); // Mã hóa mật khẩu
        $user->save();

        // Chuyển hướng sau khi đăng ký thành công
        return redirect()->back()->with('message', 'Đăng ký thành công!');
    }


    public function admin_layout()
    {
        return view('admin_layout');
    }
    public function profile()
    {
        return view('admin.account.my-adprofile');
    }

    public function submit_admin_login(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $validatedData = $request->validate([
            'AdminUser' => 'required',
            'AdminPass' => 'required',
        ]);

        // Lấy dữ liệu từ request
        $adminUser = $request->input('AdminUser');
        $adminPass = $request->input('AdminPass');

        // Tìm kiếm người dùng trong cơ sở dữ liệu
        $admin = Admin::where('AdminUser', $adminUser)->first();

        // Kiểm tra xem người dùng có tồn tại và mật khẩu có khớp không
        if ($admin && Hash::check($adminPass, $admin->AdminPass)) {
            // Đăng nhập thành công
            // Lưu thông tin vào session nếu cần thiết
            $request->session()->put('idAdmin', $admin->idAdmin);
            $request->session()->put('AdminUser', $admin->AdminUser);
            $request->session()->put('NumberPhone', $admin->NumberPhone);
            $request->session()->put('Avatar', $admin->Avatar);

            return redirect()->route('admin.profile'); // Chuyển hướng đến trang profile của admin
        } else {
            // Đăng nhập thất bại, đặt thông báo lỗi và chuyển hướng lại trang đăng nhập
            return back()->withErrors([
                'login_error' => 'Tên tài khoản hoặc mật khẩu không chính xác.',
            ])->withInput();
        }
    }


}
