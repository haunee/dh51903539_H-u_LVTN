<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Session;
use App\Mail\VerifyAccount;
use App\Mail\VerifyPassword;
use App\Models\Customer;
use App\Models\ResetPasswordCustomer;
use Dotenv\Validator as DotenvValidator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
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
    //xác thực lưu tạm thời vào csdl 
    public function verify(Request $request)
    {
        // lưu trữ token tạm thời
        $token = $request->input('token');
        session(['verification_token' => $token]);
        return view("shop.mail.verify");
    }

    //trang nhập mã xác thực
    public function verifyToken(Request $request)
    {
        $token = $request->input('token');
        $customer = Customer::where('verification_token', $token)->first();

        if ($customer) {
            // Xác thực thành công
            $customer->email_verified = true;
            $customer->verification_token = null;//nếu khớp thì cột token về null
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
            'username' => 'required|unique:customer',
            'password' => 'required|min:8|regex:/[A-Z]/',
            'email' => 'email',

        ], [
            'username.required' => 'Vui lòng nhập tên người dùng',
            'username.unique' => 'Tên người dùng đã tồn tại',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.regex' => 'Mật khẩu phải chứa ít nhất 1 ký tự hoa',
            'email.email' => 'email không hợp lệ',
        ]);

        // Kiểm tra nếu validator thất bại
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $verificationToken = rand(100000, 999999);
        $data = $request->all();

        // Kiểm tra xác nhận mật khẩu
        if ($data['password'] !== $data['password_confirmation']) {
            return redirect()->back()->with('error', 'Mật khẩu không khớp vui lòng nhập lại!!!');
        }

        // Tạo khách hàng mới
        $customer = new Customer();
        $data = $request->only('username', 'email', 'password', 'PhoneNumber', 'Avatar', 'verification_token');
        $data['password'] = bcrypt($request->password);

        // Kiểm tra xem username đã tồn tại chưa
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
        $username = $data['username'];
        $password = $data['password'];

        $customer = Customer::where('username', $username)->first();

        if ($customer && Hash::check($password, $customer->password)) {
            if (!$customer->email_verified) {
                return redirect()->back()->with('error', 'tài khoản của bạn chưa được xác thực. Vui lòng kiểm tra email và xác thực.');
            }

            Session::put('idCustomer', $customer->idCustomer);
            Session::put('AvatarCus', $customer->Avatar);
            return Redirect::to('/home');
        } else {
            return redirect()->back()->with('error', 'Mật khẩu hoặc tài khoản không đúng');
        }
    }





    // Chỉnh sửa hồ sơ
    public function edit_profile(Request $request)
    {
        $this->checkLogin();

        // Validate dữ liệu từ form
        $request->validate([
            'PhoneNumber' => 'required|numeric|digits:10',
            'username' => 'required|string|max:255',
        ], [
            'PhoneNumber.required' => 'Số điện thoại là bắt buộc',
            'PhoneNumber.numeric' => 'Số điện thoại phải là số',
            'PhoneNumber.digits' => 'Số điện thoại phải có 10 chữ số',
            'username.required' => 'Tên tài khoản là bắt buộc',
            'username.max' => 'Tên tài khoản không được vượt quá 255 ký tự',
            
        ]);

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
        return redirect()->route('profile.show')->with('message', 'Cập nhật hồ sơ thành công');
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
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu cũ không đúng!!']);
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

    //submit xác thực email
    public function submit_forgot_password(Request $request)
    {
        $token = rand(100000, 999999);

        $tokenData = ResetPasswordCustomer::where('email', $request->email)->first();
        if ($tokenData) {
            $tokenData->token = $token;
            $tokenData->save();
        }
        $customer = Customer::where('email', $request->email)->first();
        if (!$customer) {
            return redirect()->back()->with('error', 'email không tồn tại');
        }
        Mail::to($request->email)->send(new VerifyPassword($customer, $token));
        return redirect()->back()->with('message', 'ok check mail');
    }




    //xác nhận đặt lại mật khẩu
    public function submit_reset_password(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:customer,username',
            'token' => 'required|string',
            'new_password' => 'required|min:8|regex:/[A-Z]/',
            'new_password_confirmation' => 'required|same:new_password',
        ], [
            'username.required' => 'Vui lòng nhập tên tài khoản',
            'token.required' => 'Vui lòng nhập mã xác nhận',

            'new_password.required' => 'Vui lòng nhập mật khẩu mới',
            'new_password.min' => 'Mật khẩu mới phải chứa ít nhất 8 ký tự và kí tự hoa',

            'new_password_confirmation.required' => 'Vui lòng nhập xác nhận mật khẩu mới',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        // Lấy thông tin từ request
        $token = $request->input('token');
        $username = $request->input('username');
        $new_password = $request->input('new_password');

        $data = $request->all();

        $token = ResetPasswordCustomer::where('token', $request->token)->first();
        if (!$token) {
            return redirect()->back()->with('reset_error', 'sai mã xác nhận');
        }


        $customer = DB::table('customer')->where('username', $username)->first();
        // Kiểm tra xem username có tồn tại không
        if (!$customer) {
            return redirect()->back()->with(['reset_error' => 'Username không tồn tại'], 400);
        }

        if ($data['new_password'] !== $data['new_password_confirmation']) {
            return redirect()->back()->with('reset_error', 'mat khau khong trung khop');
        }

        // Cập nhật mật khẩu mới
        DB::table('customer')->where('username', $username)->update(['password' => Hash::make($new_password)]);

        $token->token = null;
        $token->save();


        return redirect()->back()->with(['reset_message' => 'Mật khẩu đã được cập nhật thành công']);
    }
}
