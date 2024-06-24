<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{


    //login
    public function show_login()
    {
        return view('admin.account.admin_login');
    }



    public function admin_logout()
    {
        Session::put('idAdmin', null);
        return Redirect::to('/admin');
    }



    public function submit_admin_login(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'AdminUser' => 'required',
            'AdminPass' => 'required',
        ]);

        // Lấy dữ liệu từ request
        $adminUser = $request->input('AdminUser');
        $adminPass = $request->input('AdminPass');

        // Tìm kiếm người dùng trong cơ sở dữ liệu
        $admin = Admin::where('AdminUser', $adminUser)->first();
        if (!$admin || !Hash::check($adminPass, $admin->AdminPass)) {
            // Nếu tên tài khoản không tồn tại hoặc mật khẩu không chính xác
            if (!$admin) {
                return back()->withErrors(['error' => 'Tên tài khoản không tồn tại.'])->withInput();
            } else {
                return back()->withErrors(['error' => 'Mật khẩu không chính xác.'])->withInput();
            }
        }


        // Đăng nhập thành công
        // Lưu thông tin vào session key aduser giá trị  lấy csdl
        Session::put('idAdmin', $admin->idAdmin);
        Session::put('AdminUser', $admin->AdminUser);
        Session::put('AdminName', $admin->AdminName);
        Session::put('Address', $admin->Address);
        Session::put('NumberPhone', $admin->NumberPhone);
        Session::put('Email', $admin->Email);
        Session::put('Avatar', $admin->Avatar);




        return redirect()->route('admin.profile'); // Chuyển hướng đến trang profile của admin
    }



   




    public function my_profile()
    {
        return view('admin.account.my-adprofile');
    }


    //chuyển trang profile
    public function profile()
    {
        return view('admin.account.my-adprofile');
    }

    //edit profile

    public function edit_profile()
    {
        return view("admin.account.edit-profile");
    }

    // Chỉnh sửa hồ sơ cá nhân
    public function submit_edit_adprofile(Request $request)
    {
        $request->validate([
            'AdminName' => 'required',
            'NumberPhone' => 'required|digits:10',
            'Email' => 'required|ends_with:@gmail.com',
            'Address' => 'required',

        ], [
            'AdminName.required' => 'Họ và tên không để trống',
            'NumberPhone.required' => 'Số điện thoại không để trống',
            'NumberPhone.digits' => 'Số điện thoại phải có đúng 10 chữ số',
            'Email.ends_with' => 'Email không đúng cú pháp',
            'Email.required' => 'Email không để trống',
            'Address.required' => 'Địa chỉ không để trống',
        ]);


        $data = $request->all();

        $admin = Admin::find(Session::get('idAdmin'));

        //giá trị từ form nhập vào gán cho thuộc tính
        $admin->AdminName = $data['AdminName'];
        $admin->NumberPhone = $data['NumberPhone'];
        $admin->Email = $data['Email'];
        $admin->Address = $data['Address'];

        if ($request->file('Avatar')) {
            $get_image = $request->file('Avatar');
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . rand(0, 99) . '.' . $get_image->getClientOriginalExtension();
            $get_image->storeAs('public/kidadmin/images/user', $new_image);
            $admin->Avatar = $new_image;
            Session::put('Avatar', $new_image);

            $get_old_img = Admin::where('idAdmin', Session::get('idAdmin'))->first();
            Storage::delete('/kidadmin/images/user/' . $get_old_img->Avatar);
        }

        $admin->save();

        //lưu thông tin từ yêu cầu vào session với key là adminname
        Session::put('AdminName', $data['AdminName']);
        Session::put('Address', $data['Address']);
        Session::put('NumberPhone', $data['NumberPhone']);
        Session::put('Email', $data['Email']);
        return redirect()->back()->with('message', 'Sửa hồ sơ thành công');
    }








    //chuyển trang change password
    public function change_adpassword()
    {
        return view('admin.account.change-adpassword');
    }


    public function submit_change_adpassword(Request $request)
    {
        $data = $request->validate([
            'password' => 'required',
            'newpassword' => 'required|min:8|confirmed|regex:/[A-Z]/',
        ], [
            'newpassword.min' => 'mật khẩu tối thiểu 8 kí tự',
            'newpassword.regex' => 'mật khẩu tối thiểu 1 kí tự hoa',
            'newpassword.confirmed' => 'mật khẩu không trùng khớp',

        ]);

        $admin = Admin::find(Session::get('idAdmin'));

        if (!Hash::check($data['password'], $admin->AdminPass)) {
            return redirect()->back()->with('error', 'Nhập mật khẩu cũ không đúng');
        }

        // Mã hóa mật khẩu mới và lưu vào cơ sở dữ liệu
        $admin->AdminPass = Hash::make($data['newpassword']);
        $admin->save();

        return redirect()->back()->with('message', 'Đổi mật khẩu thành công');
    }
}
