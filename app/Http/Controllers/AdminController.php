<?php

namespace App\Http\Controllers;

use App\Mail\VerifyAdminPass;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Customer;

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
        Session::put('email', $admin->email);
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
            'Address' => 'required',

        ], [
            'AdminName.required' => 'Họ và tên không để trống',
            'NumberPhone.required' => 'Số điện thoại không để trống',
            'NumberPhone.digits' => 'Số điện thoại phải có đúng 10 chữ số',
            'Address.required' => 'Địa chỉ không để trống',
        ]);


        $data = $request->all();

        $admin = Admin::find(Session::get('idAdmin'));

        //giá trị từ form nhập vào gán cho thuộc tính
        $admin->AdminName = $data['AdminName'];
        $admin->NumberPhone = $data['NumberPhone'];
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




    //forgot password
    public function admin_forgotpass()
    {
        return view('admin.account.adforgot-pass');
    }


    //gửi mail
    public function submit_send_mail(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:admin,email',
    ]);

    $admin = Admin::where('email', $request->email)->first();
    $resetCode = rand(100000, 999999);

    // Lưu mã xác nhận vào cơ sở dữ liệu
    $admin->code = $resetCode;
    $admin->save();

    try {
        Mail::to($admin->email)->send(new VerifyAdminPass($admin, $resetCode));
        Session::flash('message', 'Mã xác nhận đã được gửi đến email của bạn.');
        Session::flash('section', 'send-code');
    } catch (\Exception $e) {
        // Ghi lỗi vào file log
        Log::error('Error sending email: ' . $e->getMessage());
        Session::flash('error', 'Có lỗi xảy ra khi gửi email. Vui lòng thử lại sau.');
        Session::flash('section', 'send-code');
    }

    return redirect()->back();
}
    


    //trang nhập mã xác thực
    public function submit_reset_Password(Request $request)
    {
        $request->validate([
            'reset_code' => 'required|exists:admin,code',
            'password' => 'required|confirmed|min:6',
        ]);

        $admin = Admin::where('code', $request->reset_code)->first();
        $admin->AdminPass = Hash::make($request->password);
        $admin->code = null; // Xóa mã xác nhận sau khi đặt lại mật khẩu
        $admin->save();

        Session::flash('message', 'Mật khẩu của bạn đã được đặt lại thành công.');
        Session::flash('section', 'reset-password');

        return redirect()->back();
    }




    
    //chuyển trang quản lí người dùng
    public function manage_customers()
    {

        $list_customer = Customer::get();
        $count_customer = Customer::count();
        return view("admin.manage-user.manage-customers")->with(compact('list_customer', 'count_customer'));
    }
}
