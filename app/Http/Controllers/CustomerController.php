<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Session;
use App\Mail\VerifyAccount;
use App\Mail\VerifyPassword;
use App\Models\Customer;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;



class CustomerController extends Controller
{
    // Kiểm tra đăng nhập
    public function checkLogin()
    {
        $idCustomer = Session::get('idCustomer');
        if ($idCustomer == false) return Redirect::to('/login')->send();
    }

    // Chuyển đến trang hồ sơ khách hàng
    public function show_account_info()
    {
        // Kiểm tra đăng nhập
        $this->checkLogin();

        // Lấy thông tin khách hàng từ cơ sở dữ liệu
        $customer = Customer::find(Session::get('idCustomer'));

        // Truyền dữ liệu khách hàng tới view
        return view('shop.account.profile', compact('customer'));
    }

    public function logout()
    {

        // Xóa phiên đăng nhập của người dùng
        Session::forget('idCustomer');
        Session::forget('AvatarCus');

        // Chuyển hướng về trang chủ hoặc trang đăng nhập
        return redirect('/home');
    }




    //XÁC THỰC EMAIL
    //xác thực 
    public function verify(Request $request)
    {
        // lưu trữ token tạm thời
        $token = $request->input('token');
        session(['verification_token' => $token]);
        return view("shop.mail.verify");
    }
    //mã xác thực
    public function verifyToken(Request $request)
    {
        $token = $request->input('token');
        $customer = Customer::where('verification_token', $token)->first();

        if ($customer) {
            // Xác thực thành công
            $customer->email_verified = true;
            $customer->verification_token = null;
            $customer->save();
            return redirect('/login')->with('message', 'Xác thực thành công! Bạn đã có thể đăng nhập.');
        } else {
            // Mã token không hợp lệ
            return redirect()->back()->with('error', 'Mã xác thực không hợp lệ.');
        }
    }







    //ĐĂNG KÝ
    public function register()
    {

        return view("shop.account.register");
    }
    public function submit_register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8|regex:/[A-Z]/', // Mật khẩu phải có ít nhất 8 kí tự

        ]);

        // Kiểm tra nếu validator thất bại
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'mật khẩu ít nhất 1 kí tự hoa và nhiều hơn 8 kí tự');
        }

        $verificationToken = rand(100000, 999999);
        $data = $request->all();

        // Kiểm tra xác nhận mật khẩu
        if ($data['password'] !== $data['password_confirmation']) {
            return redirect()->back()->with('error', 'Mật khẩu không khớp vui lòng nhập lại!!!');
        }

        // Tạo khách hàng mới
        $customer = new Customer();
        $data = $request->only('username','email', 'password', 'PhoneNumber', 'Avatar', 'verification_token');
        $data['password'] = bcrypt($request->password);

        // Kiểm tra xem email đã tồn tại chưa
        $check_customer = Customer::where('username', $data['username'])->first();
        if ($check_customer) {
            return redirect()->back()->with('error', 'Tên tài khoản đã tồn tại');
        } else {
            $customer->username = $data['username'];
            $customer->email = $data['email'];
            $customer->password = $data['password'];
            $customer->verification_token = $verificationToken; // Tạo mã xác minh ngẫu nhiên
            $customer->save();

            // Gửi email xác nhận
            Mail::to($customer->email)->send(new VerifyAccount($customer, $verificationToken));

            return redirect()->back()->with('message', 'Đăng ký tài khoản thành công, vui lòng kiểm tra email để xác nhận tài khoản.');
        }
    }








    //ĐĂNG NHẬP
    public function login()
    {
        return view("shop.account.login");
    }

    public function submit_login(Request $request)
    {
        $data = $request->all();
        $email = $data['email'];
        $password = $data['password'];

        $customer = Customer::where('email', $email)->first();

        if ($customer && Hash::check($password, $customer->password)) {
            if (!$customer->email_verified) {
                return redirect()->back()->with('error', 'Email của bạn chưa được xác thực. Vui lòng kiểm tra email và xác thực.');
            }

            Session::put('idCustomer', $customer->idCustomer);
            Session::put('AvatarCus', $customer->Avatar);
            return Redirect::to('/home');
        } else {
            return redirect()->back()->with('error', 'Mật khẩu hoặc tài khoản không đúng');
        }
    }




    //SỬA TÀI KHOẢN
    public function edit_profile(Request $request)
    {
        $this->checkLogin();
        $data = $request->all();

        $customer = Customer::find(Session::get('idCustomer'));
        $customer->PhoneNumber = $data['PhoneNumber'];
        $customer->username = $data['username'];

        if ($request->hasFile('Avatar')) {
            $get_image = $request->file('Avatar');

            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . rand(0, 99) . '.' . $get_image->getClientOriginalExtension();
            $get_image->storeAs('public/page/images/customer', $new_image);
            $customer->Avatar = $new_image;
            Session::put('AvatarCus', $new_image);

            $get_old_img = Customer::where('idCustomer', Session::get('idCustomer'))->first();
            Storage::delete('public/page/images/customer/' . $get_old_img->Avatar);
        }

        $customer->save();
    }





    //ĐỔI MẬT KHẨU
    public function change_password()
    {
        return view("shop.account.change_password");
    }


    public function submit_change_password(Request $request)
    {
        $this->checkLogin();

        // Validate input
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới',
            'new_password.min' => 'Mật khẩu mới phải chứa ít nhất 8 ký tự',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $customer = Customer::find(Session::get('idCustomer'));

        if (!Hash::check($request->current_password, $customer->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        $customer->password = Hash::make($request->new_password);
        $customer->save();

        return redirect()->back()->with('success', 'Mật khẩu đã được thay đổi thành công');
    }












    //XÁC HỰC QUÊN MẬT KHẨU
    public function forgot_password()
    {
        return view("shop.account.forgot_password");
    }













    public function submit_forgot_password(Request $request)
    {
        $verificationToken = rand(100000, 999999);
        $request->validate(['email' => 'required|email|exists:email']);
        $customer = Customer :: where('email', $request->email) -> first();

        Mail::to($customer->email)->send(new VerifyPassword($customer, $verificationToken));


        return redirect()->back()->with('message', 'vui lòng kiểm tra email để xác nhận tài khoản.');
    }


    public function submit_reset_password(Request $request)  
    {
        $token = $request->input('token');
        $customer = Customer::where('verification_token', $token)->first();

        if ($customer) {
            // Xác thực thành công
            $customer->email_verified = true;
            $customer->verification_token = null;
            $customer->save();
            return redirect('/login')->with('message', 'Xác thực thành công! Bạn đã có thể đăng nhập.');
        } else {
            // Mã token không hợp lệ
            return redirect()->back()->with('error', 'Mã xác thực không hợp lệ.');
        }

    }



}
