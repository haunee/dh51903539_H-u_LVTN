<?php

namespace App\Http\Controllers;

use App\Mail\VerifyAdminPass;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Voucher;




class AdminController extends Controller
{

    // Kiểm tra đăng nhập
    public function checkLogin()
    {
        $idAdmin = Session::get('idAdmin');
        if ($idAdmin == false) return Redirect::to('admin')->send();
    }


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




        return redirect()->route('admin.dashboard');
    }




    public function my_profile()
    {
        return view('admin.account.my-adprofile');
    }


   
    public function profile()
    {
        return view('admin.account.my-adprofile');
    }

    
    public function edit_profile()
    {
        return view("admin.account.edit-profile");
    }

    // edit pf
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
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.exists' => 'Email không tồn tại trong hệ thống.',
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

        return redirect()->back()->with(['section' => 'send-code'])->withInput();

    }



    //trang nhập mã xác thực
    public function submit_reset_Password(Request $request)
    {

        $request->validate([
            'reset_code' => 'required|exists:admin,code', 
            'password' => 'required|confirmed|min:8|regex:/[A-Z]/',
        ],[
            'reset_code.required' => 'Vui lòng nhập mã xác nhận.',
            'reset_code.exists' => 'Mã xác nhận không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.regex' => 'mật khẩu tối thiểu 1 kí tự hoa',
        ]);

        $admin = Admin::where('code', $request->reset_code)->first();
        $admin->AdminPass = Hash::make($request->password);
        $admin->code = null; // Xóa mã xác nhận sau khi đặt lại mật khẩu
        $admin->save();

        Session::flash('message', 'Mật khẩu của bạn đã được đặt lại thành công.');
        Session::flash('section', 'reset-password');

        return redirect()->back();
    }










    //DASHBOARD
   
    public function show_dashboard()
    {
        $this->checkLogin();

        //doanh thu
        $total_revenue = Order::whereNotIn('Status', [99])->sum('TotalBill'); 

        //sản phẩm đã bán
        $total_sell = OrderDetail::join('order', 'order.idOrder', '=', 'orderdetail.idOrder')->whereNotIn('Status', [99])->sum('QuantityBuy');
       

        //danh sách sp bán chạy
        
        $list_topProduct = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->join('orderdetail', 'orderdetail.idProduct', '=', 'product.idProduct')
            ->join('order', 'order.idOrder', '=', 'orderdetail.idOrder')
            ->whereNotIn('Status', [99])
            ->select('ProductName', 'ImageName') 
            ->selectRaw('sum(QuantityBuy) as Sold')//tính tổng sl sp đặt tên sold
            ->groupBy('ProductName', 'ImageName')//nhóm kết quả tên và ảnh lại 
            ->orderBy('Sold', 'DESC')
            ->take(6)->get();
       

        
        $list_topProduct_AllTime = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->join('orderdetail', 'orderdetail.idProduct', '=', 'product.idProduct')
            ->join('order', 'order.idOrder', '=', 'orderdetail.idOrder')
            ->whereNotIn('order.Status', [99])
            ->select('product.ProductName', 'productimage.ImageName', 'product.Price')//lấy tên hình giá sản phẩm 
            ->selectRaw('sum(orderdetail.QuantityBuy) as Sold')//lấy tổng sl sp đã bán 
            ->groupBy('product.ProductName', 'productimage.ImageName', 'product.Price')//nhóm kq theo tên hình giá
            ->orderBy('Sold', 'DESC')//sắp xếp theo sold giảm dần
            ->take(5)
            ->get();

        return view("admin.dashboard")->with(compact('total_revenue', 'total_sell', 'list_topProduct', 'list_topProduct_AllTime'));
    }





    // Thống kê doanh thu 7 ngày qua
    public function chart_7days()
    {

        //lấy ngày giờ hiện tại trừ 7 ngày: lọc dữ liệu 7 ngày qua
        $sub7days = Carbon::now()->subDays(7)->toDateString();

        //số lượng đơn hàng và tổng giá trị đơn hàng
        $get_statistic = Order::whereNotIn('order.Status', [99])
           
            ->whereBetween('created_at', [$sub7days, now()])
            
            ->selectRaw('sum(TotalBill) as Sale, count(idOrder) as QtyBill, date(created_at) as Date')
            ->groupBy('Date')
            ->get();


        //tổng sl sp đã bán
        $total_sold = OrderDetail::join('order', 'order.idOrder', '=', 'orderdetail.idOrder')->whereNotIn('order.Status', [99])
            ->whereBetween('order.created_at', [$sub7days, now()])
            ->selectRaw('sum(QuantityBuy) as TotalSold, date(order.created_at) as Date')
            ->groupBy('Date')->get();


        if ($get_statistic->count() > 0) {
            foreach ($get_statistic as $key => $statistic) {
                $chart_data[] = array(
                    'Date' => $statistic->Date,
                    'Sale' => $statistic->Sale,
                    'TotalSold' => $total_sold[$key]->TotalSold, 
                    'QtyBill' => $statistic->QtyBill
                );
            }
        } else $chart_data[] = array();

   
        echo $data = json_encode($chart_data);
    }



    // Thống kê doanh thu theo ngày, tháng, năm
    public function statistic_by_date_order(Request $request)
    {
        $data = $request->all();

        // lấy dữ liệu từ 7 30 365 đến hiện tại
        $sub7days = Carbon::now()->subDays(7)->toDateString();
        $sub30days = Carbon::now()->subDays(30)->toDateString();
        $sub365days = Carbon::now()->subDays(365)->toDateString();

        
        if ($data['Days'] == 'lastweek') {
            //số lượng đơn hàng ,tổng tiền 
            $get_statistic = Order::whereNotIn('order.Status', [99])
                //lọc các đơn hàng được tạo từ 7 ngày đến nay
                ->whereBetween('created_at', [$sub7days, now()])
                //tính tổng tiền đơn hàng , đếm đơn , ngày tạo đơn 
                ->selectRaw('sum(TotalBill) as Sale, count(idOrder) as QtyBill, date(created_at) as Date')
                ->groupBy('Date')->get();// nhóm lại theo ngày

            //tổng sp đã bán
            $total_sold = OrderDetail::join('order', 'order.idOrder', '=', 'orderdetail.idOrder')
                ->whereNotIn('order.Status', [99])
                ->whereBetween('order.created_at', [$sub7days, now()])
                ->selectRaw('sum(QuantityBuy) as TotalSold, date(order.created_at) as Date')
                ->groupBy('Date')->get();

        } else if ($data['Days'] == 'lastmonth') {
            $get_statistic = Order::whereNotIn('order.Status', [99])
                ->whereBetween('order.created_at', [$sub30days, now()])
                ->selectRaw('sum(TotalBill) as Sale, count(idOrder) as QtyBill, date(order.created_at) as Date')
                ->groupBy('Date')->get();
            $total_sold = OrderDetail::join('order', 'order.idOrder', '=', 'orderdetail.idOrder')
                ->whereNotIn('order.Status', [99])
                ->whereBetween('order.created_at', [$sub30days, now()])
                ->selectRaw('sum(QuantityBuy) as TotalSold, date(order.created_at) as Date')
                ->groupBy('Date')->get();

        } else if ($data['Days'] == 'lastyear') {
            $get_statistic = Order::whereNotIn('order.Status', [99])
                ->whereBetween('created_at', [$sub365days, now()])
                ->selectRaw('sum(TotalBill) as Sale, count(idOrder) as QtyBill, date(created_at) as Date')
                ->groupBy('Date')->get();

            $total_sold = OrderDetail::join('order', 'order.idOrder', '=', 'orderdetail.idOrder')
                ->whereNotIn('order.Status', [99])
                ->whereBetween('order.created_at', [$sub365days, now()])
                ->selectRaw('sum(QuantityBuy) as TotalSold, date(order.created_at) as Date')
                ->groupBy('Date')->get();
        }

        if ($get_statistic->count() > 0) {
            foreach ($get_statistic as $key => $statistic) {
                $chart_data[] = array(
                    'Date' => $statistic->Date,//ngày 
                    'Sale' => $statistic->Sale,//doanh thu
                    'TotalSold' => $total_sold[$key]->TotalSold,//số lượng bán
                    'QtyBill' => $statistic->QtyBill// tổng đơn 
                );
            }
        } else $chart_data[] = array();

        echo $data = json_encode($chart_data);
    }

   
 

    

    //chuyển trang quản lí người dùng
    public function manage_customers()
    {

        $list_customer = Customer::get();
        $count_customer = Customer::count();
        return view("admin.manage-user.manage-customers")->with(compact('list_customer', 'count_customer'));
    }

    
    //reset pw user
    public function resetPassword(Request $request, $idCustomer)
    {
     
        $validatedData = $request->validate([
            'new_password' => 'required|confirmed|min:8',
        ]);    
        $customer = Customer::find($idCustomer);
        if (!$customer) {
            return response()->json(['message' => 'Khách hàng không tồn tại'], 404);
        }

   
        $customer->password = Hash::make($validatedData['new_password']);
        $customer->save();

    
        return response()->json(['message' => 'Đổi mật khẩu thành công']);
    }















  


    // Chuyển đến trang thêm mã giảm giá
    public function add_voucher(){
        return view("admin.voucher.add-voucher");
    }



    // Chuyển đến trang sửa mã giảm giá
    public function edit_voucher($idVoucher){
        $voucher = Voucher::find($idVoucher);
        return view("admin.voucher.edit-voucher")->with(compact('voucher'));
    }



   // Chuyển đến trang quản lý mã giảm giá
    public function manage_voucher(){
        $list_voucher = Voucher::whereNotIn('idVoucher',[0])->get();
        $count_voucher = Voucher::whereNotIn('idVoucher',[0])->count();
        return view("admin.voucher.manage-voucher")->with(compact('list_voucher','count_voucher'));
    }


    // Thêm mã giảm giá
    public function submit_add_voucher(Request $request){
        $data = $request->all();

        $select_voucher = Voucher::where('VoucherCode', $data['VoucherCode'])->first();

        if($select_voucher){
            return redirect()->back()->with('error', 'Mã giảm giá này đã tồn tại');
        }else{
            $voucher = new Voucher();
            $voucher->VoucherName = $data['VoucherName'];
            $voucher->VoucherQuantity = $data['VoucherQuantity'];
            $voucher->VoucherCondition = $data['VoucherCondition'];
            $voucher->VoucherNumber = $data['VoucherNumber'];
            $voucher->VoucherCode = $data['VoucherCode'];
            $voucher->VoucherStart = $data['VoucherStart'];
            $voucher->VoucherEnd = $data['VoucherEnd'];
            $voucher->save();

            return redirect()->back()->with('message', 'Thêm mã giảm giá thành công');
        }
    }



    // Sửa mã giảm giá
    public function submit_edit_voucher(Request $request, $idVoucher){
        $data = $request->all();

        $select_voucher = Voucher::where('VoucherCode', $data['VoucherCode'])->whereNotIn('idVoucher',[$idVoucher])->first();

        if($select_voucher){
            return redirect()->back()->with('error', 'Mã giảm giá này đã tồn tại');
        }else{
            $voucher = Voucher::find($idVoucher);
            $voucher->VoucherName = $data['VoucherName'];
            $voucher->VoucherQuantity = $data['VoucherQuantity'];
            $voucher->VoucherCondition = $data['VoucherCondition'];
            $voucher->VoucherNumber = $data['VoucherNumber'];
            $voucher->VoucherCode = $data['VoucherCode'];
            $voucher->VoucherStart = $data['VoucherStart'];
            $voucher->VoucherEnd = $data['VoucherEnd'];
            $voucher->save();

            return redirect()->back()->with('message', 'Sửa mã giảm giá thành công');
        }
    }



    public function delete_voucher($idVoucher){
        Voucher::destroy($idVoucher);
        return redirect()->back();
    }

}
