<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\AddressCustomer;
use App\Models\Bill;   //ORDER  ODERDETAIL
use App\Models\BillInfo;
use App\Models\BillHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{

    // Kiểm tra đăng nhập
    public function checkLogin()
    {
        $idCustomer = Session::get('idCustomer');
        if ($idCustomer == false) return Redirect::to('login')->send();
    }

    // Kiểm tra giỏ hàng
    public function checkCart()
    {
        $check_cart = Cart::where('idCustomer', Session::get('idCustomer'))->count();
        if ($check_cart <= 0) Redirect::to('empty-cart')->send();
    }






    public function add_to_cart(Request $request)
    {
        try {
            Log::info('Phương thức add_to_cart được gọi', ['data' => $request->all()]);

            $this->checkLogin();

            $data = $request->all();
            Log::info('Dữ liệu nhận được', ['data' => $data]);
            if (!isset($data['Price'])) {
                throw new \Exception("Không có giá trị 'Price'");
            }

            $cart = new Cart();

            $cart->idProduct = $data['idProduct'];
            $cart->QuantityBuy = $data['QuantityBuy'];
            $cart->Price = $data['Price'];
            $cart->Total = $data['Price'] * $data['QuantityBuy'];
            $cart->idCustomer = Session::get('idCustomer');
            $cart->AttributeProduct = $data['AttributeProduct'];
            $cart->idProAttr = $data['idProAttr'];
            $qty_of_attr = $data['qty_of_attr'];

            Log::info('Dữ liệu giỏ hàng', ['cart' => $cart]);

            $output = '<div class="modal fade modal-AddToCart" id="successAddToCart" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"><div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="exampleModalCenterTitle">Thông báo</h5></div><button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" aria-hidden="true"></span></button><div class="modal-body text-center p-3 h4"><div class="mb-3"><i class="fa fa-check-circle text-primary" style="font-size:50px;"></i></div>Đã thêm sản phẩm vào giỏ hàng</div><div class="modal-footer justify-content-center"><button type="button" class="btn btn-secondary" data-dismiss="modal">Tiếp tục mua sắm</button><a href="../cart" type="button" class="btn btn-primary">Đi đến giỏ hàng</a></div></div></div></div>';

            $find_pd = Cart::where('idProduct', $data['idProduct'])->where('idCustomer', Session::get('idCustomer'))
                ->where('AttributeProduct', $data['AttributeProduct'])->first();

            if ($find_pd) {
                $QuantityBuy = $data['QuantityBuy'] + $find_pd->QuantityBuy;
                if ($QuantityBuy > $qty_of_attr) {
                    $output = '<div id="errorAddToCart" class="modal fade bd-example-modal-sm modal-AddToCart" tabindex="-1" role="dialog"  aria-hidden="true"><div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Thông báo</h5></div><button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button><div class="modal-body"><p>Vượt quá số lượng sản phẩm hiện có!</p></div><div class="modal-footer justify-content-center"><button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button></div></div></div></div>';
                    Log::info('Số lượng sản phẩm vượt quá số lượng hiện có');
                    echo $output;
                } else {
                    $Total = $QuantityBuy * $data['Price'];

                    Cart::where('idProduct', $data['idProduct'])->where('idCustomer', Session::get('idCustomer'))
                        ->where('AttributeProduct', $data['AttributeProduct'])->update(['QuantityBuy' => $QuantityBuy, 'Total' => $Total]);

                    Log::info('Cập nhật giỏ hàng thành công');
                    echo $output;
                }
            } else {
                $cart->save();
                Log::info('Lưu giỏ hàng mới thành công');
                echo $output;
            }
        } catch (\Exception $e) {
            Log::error('Lỗi trong phương thức add_to_cart', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Lỗi xảy ra trong quá trình thêm vào giỏ hàng'], 500);
        }
    }


    // public function add_to_cart(Request $request){
    //     $this->checkLogin();

    //     $data = $request->all();
    //     $cart = new Cart();

    //     $cart->idProduct = $data['idProduct'];
    //     $cart->QuantityBuy = $data['QuantityBuy'];
    //     $cart->Price = $data['Price'];
    //     $cart->Total = $data['Price']*$data['QuantityBuy'];
    //     $cart->idCustomer = Session::get('idCustomer');
    //     $cart->AttributeProduct = $data['AttributeProduct'];
    //     $cart->idProAttr = $data['idProAttr'];
    //     $qty_of_attr = $data['qty_of_attr'];

    //     $output = '<div class="modal fade modal-AddToCart" id="successAddToCart" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"><div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="exampleModalCenterTitle">Thông báo</h5></div><button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" aria-hidden="true"></span></button><div class="modal-body text-center p-3 h4"><div class="mb-3"><i class="fa fa-check-circle text-primary" style="font-size:50px;"></i></div>Đã thêm sản phẩm vào giỏ hàng</div><div class="modal-footer justify-content-center"><button type="button" class="btn btn-secondary" data-dismiss="modal">Tiếp tục mua sắm</button><a href="../cart" type="button" class="btn btn-primary">Đi đến giỏ hàng</a></div></div></div></div>';

    //     $find_pd = Cart::where('idProduct',$data['idProduct'])->where('idCustomer',Session::get('idCustomer'))
    //         ->where('AttributeProduct',$data['AttributeProduct'])->first();

    //     if($find_pd){
    //         $QuantityBuy = $data['QuantityBuy'] + $find_pd->QuantityBuy;
    //         if($QuantityBuy > $qty_of_attr){
    //             $output = '<div id="errorAddToCart" class="modal fade bd-example-modal-sm modal-AddToCart" tabindex="-1" role="dialog"  aria-hidden="true"><div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Thông báo</h5></div><button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button><div class="modal-body"><p>Vượt quá số lượng sản phẩm hiện có!</p></div><div class="modal-footer justify-content-center"><button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button></div></div></div></div>';
    //             echo $output;
    //         }else{
    //             $Total = $QuantityBuy * $data['Price'];

    //             Cart::where('idProduct',$data['idProduct'])->where('idCustomer',Session::get('idCustomer'))
    //                 ->where('AttributeProduct',$data['AttributeProduct'])->update(['QuantityBuy' => $QuantityBuy, 'Total' => $Total]);

    //             echo $output;
    //         }
    //     }else{
    //         $cart->save();
    //         echo $output;
    //     }
    // }




    // Chuyển đến trang giỏ hàng
    // public function show_cart()
    // {
    //     $this->checkLogin();
    //     $this->checkCart();
    //     $list_category = Category::get();
    //     $list_brand = Brand::get();
    //     $recommend_pds_arrays = [];
    //     $checked_pro = [];

    //     $list_pd_cart = Cart::join('product', 'product.idProduct', '=', 'cart.idProduct')
    //         ->join('productimage', 'productimage.idProduct', 'cart.idProduct')
    //         ->join('product_attribute', 'product_attribute.idProAttr', '=', 'cart.idProAttr')
    //         ->where('idCustomer', Session::get('idCustomer'))->get();

    //     foreach ($list_pd_cart as $key => $pd_cart) {
    //         $idBrand = $pd_cart->idBrand;
    //         $idCategory = $pd_cart->idCategory;

    //         // Mảng các sản phẩm đã lặp qua
    //         $checked_pro[] = $pd_cart->idProduct;

    //         // Danh sách sản phẩm gợi ý của 1 sản phẩm trong giỏ hàng
    //         $list_recommend_pds = Product::whereNotIn('idProduct', [$pd_cart->idProduct])
    //             ->where(function ($query) use ($idBrand, $idCategory) {
    //                 $query->orWhere('idBrand', $idBrand)
    //                     ->orWhere('idCategory', $idCategory);
    //             })
    //             ->select('idProduct')
    //             ->get();

    //         if ($list_recommend_pds->count() > 0) {
    //             // Thêm từng sản phẩm gợi ý của 1 sản phẩm vào 1 mảng
    //             foreach ($list_recommend_pds as $recommend_pd) {
    //                 $recommend_pds_array[$key][] = $recommend_pd->idProduct;
    //             }

    //             // Thêm từng mảng thứ $key vào 1 mảng lớn
    //             $recommend_pds_arrays[] = $recommend_pds_array[$key];
    //         }
    //     }

    //     if (count($recommend_pds_arrays) > 0) {
    //         // Hàm gộp mảng, xen kẽ các phần tử của từng mảng
    //         $output = [];
    //         for ($args = $recommend_pds_arrays; count($args); $args = array_filter($args)) {
    //             // &$arg allows array_shift() to change the original.
    //             foreach ($args as &$arg) {
    //                 $output[] = array_shift($arg);
    //             }
    //         }

    //         $recommend_pds_last = array_diff($output, $checked_pro); // Xóa các sản phẩm đã lặp qua
    //         $recommend_pds_unique = array_unique($recommend_pds_last); // Lọc các phần tử trùng nhau
    //         $recommend_pds = json_encode($recommend_pds_unique);
    //     } else {
    //         $featured_pds = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
    //             ->orderBy('product.created_at', 'DESC')  // Specify 'product' table
    //             ->select('product.idProduct')
    //             ->get();

    //         $featured_pds_array = $featured_pds->pluck('idProduct')->toArray();

    //         $recommend_pds = json_encode($featured_pds_array);
    //     }

    //     return view('shop.cart.cart')->with(compact('list_category', 'list_brand', 'list_pd_cart', 'recommend_pds'));
    // }



    public function show_cart()
    {
        $this->checkLogin();
        $this->checkCart();
        $list_category = Category::get();
        $list_brand = Brand::get();

        $list_pd_cart = Cart::join('product', 'product.idProduct', '=', 'cart.idProduct')
            ->join('productimage', 'productimage.idProduct', 'cart.idProduct')
            ->join('product_attribute', 'product_attribute.idProAttr', '=', 'cart.idProAttr')
            ->where('idCustomer', Session::get('idCustomer'))->get();

        return view('shop.cart.cart')->with(compact('list_category', 'list_brand', 'list_pd_cart'));
    }






    // Xóa sản phẩm trong giỏ hàng
    public function delete_pd_cart($idCart)
    {
        $this->checkCart();
        Cart::destroy($idCart);
        return response()->json(['success' => 'Sản phẩm đã được xóa khỏi giỏ hàng']);
    }

    // Cập nhật số lượng mua trong giỏ hàng
    public function update_qty_cart(Request $request)
    {
        try {
            $this->checkLogin();

            $data = $request->all();

            $cart = Cart::find($data['idCart']);
            if ($cart) {
                $cart->QuantityBuy = $data['QuantityBuy'];
                $cart->Total = $cart->Price * $data['QuantityBuy'];
                $cart->save();

                return response()->json(['success' => 'Cập nhật giỏ hàng thành công']);
            } else {
                return response()->json(['error' => 'Không tìm thấy sản phẩm trong giỏ hàng'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi xảy ra trong quá trình cập nhật giỏ hàng'], 500);
        }
    }





    // Hiện giỏ hàng ở page
    public static function get_cart_header()
    {
        $sum_cart = Cart::where('idCustomer', Session::get('idCustomer'))->sum('QuantityBuy');

        $get_cart_header = Cart::join('product', 'product.idProduct', '=', 'cart.idProduct')
            ->join('productimage', 'productimage.idProduct', 'cart.idProduct')
            ->where('cart.idCustomer', Session::get('idCustomer'))->get();
        return ['sum_cart' => $sum_cart, 'get_cart_header' => $get_cart_header];
    }


    // Chuyển đến trang giỏ hàng trống
    public function empty_cart()
    {
        $list_category = Category::get();
        $list_brand = Brand::get();
        return view("shop.cart.empty-cart")->with(compact('list_category', 'list_brand'));
    }





    // Chuyển đến trang thanh toán
    public function payment()
    {
        $this->checkLogin();
        $this->checkCart();
        $list_category = Category::get();
        $list_brand = Brand::get();

        $list_pd_cart = Cart::join('product', 'product.idProduct', '=', 'cart.idProduct')
            ->join('productimage', 'productimage.idProduct', 'cart.idProduct')
            ->join('product_attribute', 'product_attribute.idProAttr', '=', 'cart.idProAttr')
            ->where('idCustomer', Session::get('idCustomer'))->get();

        return view("shop.cart.payment")->with(compact('list_category', 'list_brand', 'list_pd_cart'));
    }





    //ĐỊA CHỈ
    // Thêm địa chỉ nhận hàng
    public function insert_address(Request $request)
    {
        $this->checkLogin();
        $data = $request->all();

        $address = new AddressCustomer();
        $address->idCustomer = Session::get('idCustomer');
        $address->Address = $data['Address'];
        $address->CustomerName = $data['CustomerName'];
        $address->PhoneNumber = $data['PhoneNumber'];

        $address->save();
    }


    // Hiện danh sách địa chỉ nhận hàng
    public function fetch_address()
    {
        $list_address = AddressCustomer::where('idCustomer', Session::get('idCustomer'))->get();
        $output = '';

        foreach ($list_address as $key => $address) {
            $output .= '<li class="cus-radio align-items-center justify-content-between">
                            <input type="radio" name="address_rdo" value="' . $address->idAddress . '" id="radio' . $address->idAddress . '" checked>
                            <label for="radio' . $address->idAddress . '">
                                <span>' . $address->CustomerName . '</span>
                                <span>' . $address->PhoneNumber . '</span>
                                <span>' . $address->Address . '</span>
                            </label>
                            <div>
                                <button type="button" data-toggle="modal" data-target="#EditAddressModal" class="edit-address btn btn-outline-primary" data-id="' . $address->idAddress . '" data-name="' . $address->CustomerName . '" data-phone="' . $address->PhoneNumber . '" data-address="' . $address->Address . '">Sửa</button>
                                <button type="button" class="dlt-address btn btn-outline-primary ml-2" data-id="' . $address->idAddress . '">Xóa</button>
                            </div>     
                        </li>';
        }
        echo $output;
    }

    // Sửa địa chỉ nhận hàng
    public function edit_address(Request $request, $idAddress)
    {
        $this->checkLogin();
        $data = $request->all();

        $address = AddressCustomer::find($idAddress);
        $address->idCustomer = Session::get('idCustomer');
        $address->Address = $data['Address'];
        $address->CustomerName = $data['CustomerName'];
        $address->PhoneNumber = $data['PhoneNumber'];

        $address->save();
    }

    // Xóa địa chỉ nhận hàng
    public function delete_address($idAddress)
    {
        $this->checkLogin();
        AddressCustomer::destroy($idAddress);
    }










    //===========BILL=======ORDER========//

    public function submit_payment(Request $request)
    {
        $data = $request->all();
        $Bill = new Bill();

        if ($data['checkout'] == 'vnpay') {
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = rtrim(getenv('APP_URL'), '/') . '/success-order'; 

            $vnp_TmnCode = "CGXZLS0Z"; 
            $vnp_HashSecret = "XNBCJFAKAZQSGTARRLGCHVZWCIOIGSHN"; 

            $vnp_TxnRef = base64_encode(openssl_random_pseudo_bytes(30)); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
            $vnp_OrderInfo = $data['address_rdo'] . '_' . $data['Voucher'] . '_' . Session::get('idCustomer') . '_' . $data['idVoucher'];
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $data['TotalBill'] * 100;
            $vnp_Locale = 'vn';
            $vnp_BankCode = 'NCB';
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
            //Add Params of 2.0.1 Version
            // $vnp_ExpireDate = $_POST['txtexpire'];
            //Billing
            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
            if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
                $inputData['vnp_Bill_State'] = $vnp_Bill_State;
            }

            //var_dump($inputData);
            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
            $returnData = array(
                'code' => '00', 'message' => 'success', 'data' => $vnp_Url
            );
            if (isset($_POST['redirect'])) {
                header('Location: ' . $vnp_Url);
                die();
            } else {
                echo json_encode($returnData);
            }
        } else if ($data['checkout'] == 'cash') {
            $get_address = AddressCustomer::find($data['address_rdo']);
            $Bill->idCustomer = Session::get('idCustomer');
            $Bill->TotalBill = $data['TotalBill'];

            $Bill->Address = $get_address->Address;
            $Bill->PhoneNumber = $get_address->PhoneNumber;
            $Bill->CustomerName = $get_address->CustomerName;
            $Bill->Payment = 'cash';

            $Bill->save();
            $get_Bill = Bill::where('created_at', now())->where('idCustomer', Session::get('idCustomer'))->first();
            $get_cart = Cart::where('idCustomer', Session::get('idCustomer'))->get();

            foreach ($get_cart as $key => $cart) {
                $data_billinfo = array(
                    'idBill' => $get_Bill->idBill,
                    'idProduct' => $cart->idProduct,
                    'AttributeProduct' => $cart->AttributeProduct,
                    'Price' => $cart->Price,
                    'QuantityBuy' => $cart->QuantityBuy,
                    'idProAttr' => $cart->idProAttr,
                    'created_at' => now(),
                    'updated_at' => now()
                );
                BillInfo::insert($data_billinfo);
                // DB::update(DB::RAW('update product set QuantityTotal = QuantityTotal - '.$cart->QuantityBuy.' where idProduct = '.$cart->idProduct.''));
                // DB::update(DB::RAW('update product_attribute set Quantity = Quantity - '.$cart->QuantityBuy.' where idProAttr = '.$cart->idProAttr.''));
                DB::update('UPDATE product SET QuantityTotal = QuantityTotal - ? WHERE idProduct = ?', [
                    $cart->QuantityBuy,
                    $cart->idProduct,
                ]);

                // Cập nhật số lượng của thuộc tính sản phẩm
                DB::update('UPDATE product_attribute SET Quantity = Quantity - ? WHERE idProAttr = ?', [
                    $cart->QuantityBuy,
                    $cart->idProAttr,
                ]);
            }

            // if($get_Bill->Voucher != '') DB::update(DB::RAW('update voucher set VoucherQuantity = VoucherQuantity - 1 where idVoucher = '.$data['idVoucher'].''));
            // Cart::where('idCustomer',Session::get('idCustomer'))->delete();
            return Redirect::to('success-order')->send();
        }
    }




    // Chuyển đến trang đặt hàng thành công
    public function success_order(Request $request)
    {
        $list_category = Category::get();
        $list_brand = Brand::get();
        if ($request->vnp_TransactionStatus && $request->vnp_TransactionStatus == '00') {
            // $test = dd($request->toArray());
            $Bill = new Bill();
            $BillHistory = new BillHistory();
            $OrderInfo = explode("_", $request->vnp_OrderInfo);

            $get_address = AddressCustomer::find($OrderInfo[0]);
            $Bill->idCustomer = $OrderInfo[2];
            $Bill->TotalBill = $request->vnp_Amount / 100;
            $Bill->Address = $get_address->Address;
            $Bill->PhoneNumber = $get_address->PhoneNumber;
            $Bill->CustomerName = $get_address->CustomerName;
            $Bill->Status = 1;
            $Bill->Payment = 'vnpay';

            $Bill->save();
            $get_Bill = Bill::where('created_at', now())->where('idCustomer', $OrderInfo[2])->first();
            $get_cart = Cart::where('idCustomer', $OrderInfo[2])->get();

            foreach ($get_cart as $key => $cart) {
                $data_billinfo = array(
                    'idBill' => $get_Bill->idBill,
                    'idProduct' => $cart->idProduct,
                    'AttributeProduct' => $cart->AttributeProduct,
                    'Price' => $cart->Price,
                    'QuantityBuy' => $cart->QuantityBuy,
                    'idProAttr' => $cart->idProAttr,
                    'created_at' => now(),
                    'updated_at' => now()
                );
                BillInfo::insert($data_billinfo);
                DB::update('update product set QuantityTotal = QuantityTotal - ? where idProduct = ?', [$cart->QuantityBuy, $cart->idProduct]);
                DB::update('update product_attribute set Quantity = Quantity - ? where idProAttr = ?', [$cart->QuantityBuy, $cart->idProAttr]);
                // DB::update(DB::RAW('update product set QuantityTotal = QuantityTotal - ' . $cart->QuantityBuy . ' where idProduct = ' . $cart->idProduct . ''));
                // DB::update(DB::RAW('update product_attribute set Quantity = Quantity - ' . $cart->QuantityBuy . ' where idProAttr = ' . $cart->idProAttr . ''));
            }

            if ($get_Bill->Voucher != '') DB::update(DB::RAW('update voucher set VoucherQuantity = VoucherQuantity - 1 where idVoucher = ' . $OrderInfo[3] . ''));
            Cart::where('idCustomer', $OrderInfo[2])->delete();
            $BillHistory->idBill = $get_Bill->idBill;
            $BillHistory->AdminName = 'System';
            $BillHistory->Status = 1;
            $BillHistory->save();
            return view("shop.cart.success-order")->with(compact('list_category', 'list_brand'));
        } else if ($request->vnp_TransactionStatus && $request->vnp_TransactionStatus != '00')
            return Redirect::to('cart');
        else return view("shop.cart.success-order")->with(compact('list_category', 'list_brand'));
    }






    // Hiện tất cả đơn đặt hàng
    public function ordered()
    {
        $this->checkLogin();
        $list_category = Category::get();
        $list_brand = Brand::get();
        $list_bill = Bill::where('bill.idCustomer', Session::get('idCustomer'))->orderBy('idBill', 'desc')->get();
        return view("shop.order.ordered")->with(compact('list_category', 'list_brand', 'list_bill'));
    }




    public function delete_bill(Request $request, $idBill)
    {
        if ($request->isMethod('post')) {
            try {
                // Tìm đơn hàng
                $bill = Bill::find($idBill);

                if (!$bill) {
                    return response()->json(['success' => false, 'message' => 'Đơn hàng không tồn tại.'], 404);
                }



                // Cập nhật trạng thái đơn hàng
                $bill->update(['Status' => 99]);

                // Cập nhật số lượng sản phẩm
                $BillInfo = BillInfo::where('idBill', $idBill)->get();
                foreach ($BillInfo as $bi) {
                    DB::update('update product_attribute set Quantity = Quantity + ? where idProAttr = ?', [$bi->QuantityBuy, $bi->idProAttr]);
                    DB::update('update product set QuantityTotal = QuantityTotal + ? where idProduct = ?', [$bi->QuantityBuy, $bi->idProduct]);
                }

                // Xóa thông tin chi tiết đơn hàng
                BillInfo::where('idBill', $idBill)->delete();

                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                // Log lỗi và trả về phản hồi lỗi
                Log::error('Error deleting bill: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra. Vui lòng thử lại.'], 500);
            }
        }

        return response()->json(['success' => false, 'message' => 'Method not allowed'], 405);
    }






    // Hiện tất cả đơn đặt hàng đã hủy
    public function order_cancelled()
    {
        $this->checkLogin();
        $list_category = Category::get();
        $list_brand = Brand::get();
        $list_bill = Bill::where('bill.idCustomer', Session::get('idCustomer'))->where('Status', '99')->get();
        return view("shop.order.order-cancelled")->with(compact('list_category', 'list_brand', 'list_bill'));
    }

    // Hiện tất cả đơn đặt hàng đang giao
    public function order_shipping()
    {
        $this->checkLogin();
        $list_category = Category::get();
        $list_brand = Brand::get();
        $list_bill = Bill::where('bill.idCustomer', Session::get('idCustomer'))->where('Status', '1')->get();
        return view("shop.order.order-shipping")->with(compact('list_category', 'list_brand', 'list_bill'));
    }

    // Hiện tất cả đơn đặt hàng đã giao
    public function order_shipped()
    {
        $this->checkLogin();
        $list_category = Category::get();
        $list_brand = Brand::get();
        $list_bill = Bill::where('bill.idCustomer', Session::get('idCustomer'))->where('Status', '2')->get();
        return view("shop.order.order-shipped")->with(compact('list_category', 'list_brand', 'list_bill'));
    }
    // Hiện tất cả đơn đặt hàng đang chờ xác nhận
    public function order_waiting()
    {
        $this->checkLogin();
        $list_category = Category::get();
        $list_brand = Brand::get();
        $list_bill = Bill::where('bill.idCustomer', Session::get('idCustomer'))->where('Status', '0')->get();
        return view("shop.order.order-waiting")->with(compact('list_category', 'list_brand', 'list_bill'));
    }

    // Hiện chi tiết đơn đặt hàng
    public function ordered_info($idBill)
    {
        $this->checkLogin();
        $list_category = Category::get();
        $list_brand = Brand::get();

        $address = Bill::where('idBill', $idBill)->first();

        $list_bill_info = BillInfo::join('product', 'product.idProduct', '=', 'billinfo.idProduct')
            ->join('productimage', 'productimage.idProduct', '=', 'billinfo.idProduct')
            ->where('billinfo.idBill', $idBill)
            ->select('product.ProductName', 'product.idProduct', 'productimage.ImageName', 'billinfo.*')->get();

        return view("shop.order.ordered-info")->with(compact('list_category', 'list_brand', 'address', 'list_bill_info'));
    }



    // Mua ngay
    public function buy_now(Request $request)
    {
        $this->checkLogin();

        $data = $request->all();
        $cart = new Cart();

        $cart->idProduct = $data['idProduct'];
        $cart->QuantityBuy = $data['qty_buy'];
        $cart->Price = $data['Price'];
        $cart->Total = $data['Price'] * $data['qty_buy'];
        $cart->idCustomer = Session::get('idCustomer');
        $cart->AttributeProduct = $data['AttributeProduct'];
        $cart->idProAttr = $data['idProAttr'];
        $qty_of_attr = $data['qty_of_attr'];

        $find_pd = Cart::where('idProduct', $data['idProduct'])->where('idCustomer', Session::get('idCustomer'))
            ->where('AttributeProduct', $data['AttributeProduct'])->first();

        if ($find_pd) {
            $QuantityBuy = $data['qty_buy'] + $find_pd->QuantityBuy;
            if ($QuantityBuy > $qty_of_attr) {
                return redirect()->back()->with('error', 'Vượt quá số lượng sản phẩm hiện có!');
            } else {
                $Total = $QuantityBuy * $data['Price'];

                Cart::where('idProduct', $data['idProduct'])->where('idCustomer', Session::get('idCustomer'))
                    ->where('AttributeProduct', $data['AttributeProduct'])->update(['QuantityBuy' => $QuantityBuy, 'Total' => $Total]);

                return Redirect::to('cart')->send();
            }
        } else {
            $cart->save();
            return Redirect::to('cart')->send();
        }
    }






























    //=========================ADMIN=======================//
    // Kiểm tra đăng nhập
    public function checkLogin_Admin()
    {
        $idAdmin = Session::get('idAdmin');
        if ($idAdmin == false) return Redirect::to('admin')->send();
    }

    // Hiện tất cả đơn đặt hàng
    public function list_bill()
    {
        $this->checkLogin_Admin();
        $list_bill = Bill::join('customer', 'bill.idCustomer', '=', 'customer.idCustomer')->whereNotIn('Status', [99])
            ->select('customer.username', 'customer.PhoneNumber as CusPhone', 'bill.*')->get();
        return view("admin.order.list-bill")->with(compact('list_bill'));
    }


    // Xác nhận đơn hàng
    public function confirm_bill(Request $request, $idBill)
    {
        if ($request->Status == 2) {
            Bill::find($idBill)->update(['ReceiveDate' => now(), 'Status' => $request->Status]);

            $BillInfo = BillInfo::where('idBill', $idBill)->get();
            foreach ($BillInfo as $key => $bi) {
                DB::update('update product set Sold = Sold + ? where idProduct = ?', [$bi->QuantityBuy, $bi->idProduct]);
            }
        } else {
            Bill::find($idBill)->update(['Status' => $request->Status]);
            $BillHistory = new BillHistory();
            $BillHistory->idBill = $idBill;
            $BillHistory->AdminName = Session::get('AdminName');
            $BillHistory->Status = $request->Status;
            $BillHistory->save();
        }

        return redirect()->back();
    }


    // Hiện chi tiết đơn đặt hàng
    public function bill_info($idBill)
    {
        $this->checkLogin_Admin();

        $address = Bill::where('idBill', $idBill)->first();

        $list_bill_info = BillInfo::join('product', 'product.idProduct', '=', 'billinfo.idProduct')
            ->join('productimage', 'productimage.idProduct', '=', 'billinfo.idProduct')
            ->where('billinfo.idBill', $idBill)
            ->select('product.ProductName', 'product.idProduct', 'productimage.ImageName', 'billinfo.*')->get();

        return view("admin.order.bill-info")->with(compact('address', 'list_bill_info'));
    }

    // Hiện tất cả đơn đặt hàng đang chờ xác nhận
    public function waiting_bill()
    {
        $this->checkLogin_Admin();

        $list_bill = Bill::join('customer', 'bill.idCustomer', '=', 'customer.idCustomer')->where('Status', '0')
            ->select('bill.*', 'customer.username', 'customer.PhoneNumber as CusPhone')->get();
        return view("admin.order.waiting-bill")->with(compact('list_bill'));
    }

    // Hiện tất cả đơn đặt hàng đang giao
    public function shipping_bill()
    {
        $this->checkLogin_Admin();

        $list_bill = Bill::join('customer', 'bill.idCustomer', '=', 'customer.idCustomer')
            ->join('billhistory', 'billhistory.idBill', '=', 'bill.idBill')->where('bill.Status', '1')
            ->select('bill.*', 'customer.username', 'customer.PhoneNumber as CusPhone', 'billhistory.AdminName', 'billhistory.created_at AS TimeConfirm')->get();
        return view("admin.order.shipping-bill")->with(compact('list_bill'));
    }


    // Hiện tất cả đơn đặt hàng đã giao
    public function shipped_bill()
    {
        $this->checkLogin_Admin();

        $list_bill = Bill::join('customer', 'bill.idCustomer', '=', 'customer.idCustomer')->where('bill.Status', '2')
            ->select('bill.*', 'customer.username', 'customer.PhoneNumber as CusPhone')->get();
        return view("admin.order.shipped-bill")->with(compact('list_bill'));
    }

    // Hiện tất cả đơn đặt hàng đã hủy
    public function cancelled_bill()
    {
        $this->checkLogin_Admin();

        $list_bill = Bill::join('customer', 'bill.idCustomer', '=', 'customer.idCustomer')
            ->join('billhistory', 'billhistory.idBill', '=', 'bill.idBill')->where('bill.Status', '99')
            ->select('bill.*', 'customer.username', 'customer.PhoneNumber as CusPhone', 'billhistory.AdminName', 'billhistory.created_at AS TimeConfirm')->get();
        return view("admin.order.cancelled-bill")->with(compact('list_bill'));
    }

    // Hiện tất cả đơn đặt hàng đã xác nhận
    public function confirmed_bill()
    {
        $this->checkLogin_Admin();

        $list_bill = Bill::join('customer', 'bill.idCustomer', '=', 'customer.idCustomer')
            ->join('billhistory', 'billhistory.idBill', '=', 'bill.idBill')->where('billhistory.Status', '1')
            ->select('bill.*', 'customer.username', 'customer.PhoneNumber as CusPhone', 'billhistory.AdminName', 'billhistory.created_at AS TimeConfirm')->get();
        return view("admin.order.confirmed-bill")->with(compact('list_bill'));
    }
}
