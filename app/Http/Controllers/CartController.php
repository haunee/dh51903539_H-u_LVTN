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
use App\Models\Voucher;
use App\Models\OrderHistory;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{







    // Kiểm tra đăng nhập
    public function checkLogin()
    {
        //lấy giá trị của biến session có tên id khi đăng nhập thành công 
        $idCustomer = Session::get('idCustomer');
        if ($idCustomer == false) return Redirect::to('login')->send();
    }

    // Kiểm tra giỏ hàng
    public function checkCart()
    {
        //truy vấn đến cart để đếm số lượng các mục giỏ hàng có id =id 
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
            $qty_of_attr = $data['qty_of_attr'];//số lượng tối đa của 1 thuộc tính

            Log::info('Dữ liệu giỏ hàng', ['cart' => $cart]);

            $output = '<div class="modal fade modal-AddToCart" id="successAddToCart" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"><div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="exampleModalCenterTitle">Thông báo</h5></div><button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" aria-hidden="true"></span></button><div class="modal-body text-center p-3 h4"><div class="mb-3"><i class="fa fa-check-circle text-primary" style="font-size:50px;"></i></div>Đã thêm sản phẩm vào giỏ hàng</div><div class="modal-footer justify-content-center"><button type="button" class="btn btn-secondary" data-dismiss="modal">Tiếp tục mua sắm</button><a href="../cart" type="button" class="btn btn-primary">Đi đến giỏ hàng</a></div></div></div></div>';

            //truy vấn đến bảng cart với điều kiện id= id
            $find_pd = Cart::where('idProduct', $data['idProduct'])//gtri lấy từ request
                ->where('idCustomer', Session::get('idCustomer'))//id = id trong session
                ->where('AttributeProduct', $data['AttributeProduct'])->first();//gtri từ request đại diện thuộc tính cho sp

            if ($find_pd) {
                $QuantityBuy = $data['QuantityBuy'] + $find_pd->QuantityBuy;//số lượng kh mua + sl sp có trong giỏ hàng
                if ($QuantityBuy > $qty_of_attr) {
                    $output = '<div id="errorAddToCart" class="modal fade bd-example-modal-sm modal-AddToCart" tabindex="-1" role="dialog"  aria-hidden="true"><div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Thông báo</h5></div><button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button><div class="modal-body"><p>Vượt quá số lượng sản phẩm hiện có!</p></div><div class="modal-footer justify-content-center"><button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button></div></div></div></div>';
                    // Log::info('Số lượng sản phẩm vượt quá số lượng hiện có');
                    echo $output;
                } else {
                    $Total = $QuantityBuy * $data['Price'];

                    //cập nhật giỏ hàng dựa trên id cập nhật QuantityBuy với tổng sl và tổng tiền mới
                    Cart::where('idProduct', $data['idProduct'])
                        ->where('idCustomer', Session::get('idCustomer'))
                        ->where('AttributeProduct', $data['AttributeProduct'])
                        ->update(['QuantityBuy' => $QuantityBuy, 'Total' => $Total]);

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





    public function show_cart()
    {
        $this->checkLogin();
        $this->checkCart();
        $list_category = Category::get();
        $list_brand = Brand::get();

        //join bảng pd với cart dựa trên id :lấy thông tin sp từ bảng pd dựa id sp trong bảng cart
        $list_pd_cart = Cart::join('product', 'product.idProduct', '=', 'cart.idProduct')
            //join bảng pdim dựa id để lấy ảnh 
            ->join('productimage', 'productimage.idProduct', 'cart.idProduct')
            //join bảng pdA dựa id để lấy thuộc tính 
            ->join('product_attribute', 'product_attribute.idProAttr', '=', 'cart.idProAttr')
            ->where('idCustomer', Session::get('idCustomer'))->get();//chỉ lấy gtri id bằng với id lưu ở session 

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

            //tìm gtri id từ request 
            $cart = Cart::find($data['idCart']);
            if ($cart) {
                $cart->QuantityBuy = $data['QuantityBuy'];//lấy số lượng thay đổi ở request 
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
        // $sum_cart = Cart::where('idCustomer', Session::get('idCustomer'))
        //             ->distinct('idProduct')
        //             ->count('idProduct');

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








    //============PAGE===================//

    //===========BILL=======ORDER========//

    //0 chờ xác nhận 1 đang giao 2 đã giao 99 đã hủy

    // Hiện tất cả đơn đặt hàng
    public function ordered()
    {
        $this->checkLogin();
        $list_category = Category::get();
        $list_brand = Brand::get();
        $list_order = Order::where('order.idCustomer', Session::get('idCustomer'))->orderBy('idOrder', 'desc')->get();
        return view("shop.order.ordered")->with(compact('list_category', 'list_brand', 'list_order'));
    }


    // Hiện tất cả đơn đặt hàng đã hủy
    public function order_cancelled()
    {
        $this->checkLogin();
        $list_category = Category::get();
        $list_brand = Brand::get();
        $list_order = Order::where('idCustomer', Session::get('idCustomer'))->where('Status', 99)->get();
        return view("shop.order.order-cancelled")->with(compact('list_category', 'list_brand', 'list_order'));
    }


    // Hiện tất cả đơn đặt hàng đang giao
    public function order_shipping()
    {
        $this->checkLogin();
        $list_category = Category::get();
        $list_brand = Brand::get();
        $list_order = Order::where('order.idCustomer', Session::get('idCustomer'))->where('Status', '1')->get();
        return view("shop.order.order-shipping")->with(compact('list_category', 'list_brand', 'list_order'));
    }

    // Hiện tất cả đơn đặt hàng đã giao
    public function order_shipped()
    {
        $this->checkLogin();
        $list_category = Category::get();
        $list_brand = Brand::get();
        $list_order = Order::where('order.idCustomer', Session::get('idCustomer'))->where('Status', '2')->get();
        return view("shop.order.order-shipped")->with(compact('list_category', 'list_brand', 'list_order'));
    }
    // Hiện tất cả đơn đặt hàng đang chờ xác nhận
    public function order_waiting()
    {
        $this->checkLogin();
        $list_category = Category::get();
        $list_brand = Brand::get();
        $list_order = Order::where('order.idCustomer', Session::get('idCustomer'))->where('Status', '0')->get();
        return view("shop.order.order-waiting")->with(compact('list_category', 'list_brand', 'list_order'));
    }

    // Hiện chi tiết đơn đặt hàng
    public function ordered_info($idOrder)
    {
        $this->checkLogin();
        $list_category = Category::get();
        $list_brand = Brand::get();

        $order = Order::where('idOrder', $idOrder)->first();

        $list_order_info = OrderDetail::join('product', 'product.idProduct', '=', 'orderdetail.idProduct')
            ->join('productimage', 'productimage.idProduct', '=', 'orderdetail.idProduct')
            ->where('orderdetail.idOrder', $idOrder)
            ->select('product.ProductName', 'product.idProduct', 'productimage.ImageName', 'orderdetail.*')->get();

        return view("shop.order.ordered-info")->with(compact('list_category', 'list_brand', 'order', 'list_order_info'));
    }



    // public function submit_payment(Request $request)
    // {
    //     $data = $request->all();
    //     $Order = new Order();
    
    //     // Kiểm tra xem người dùng đã nhập địa chỉ chưa
    //     if (!isset($data['address_rdo']) || empty($data['address_rdo'])) {
    //         return redirect()->back()->with('error', 'Bạn chưa nhập địa chỉ');
    //     }
    
    //     if ($data['checkout'] == 'vnpay') {
    //         $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    //         $vnp_Returnurl = rtrim(getenv('APP_URL'), '/') . '/success-order';
    
    //         $vnp_TmnCode = "CGXZLS0Z";
    //         $vnp_HashSecret = "XNBCJFAKAZQSGTARRLGCHVZWCIOIGSHN";
    
    //         $vnp_TxnRef = uniqid(); // Mã đơn hàng duy nhất.
    //         $vnp_OrderInfo = $data['address_rdo'] . '_' . Session::get('idCustomer').'_'.$data['Voucher'].'_'.$data['idVoucher'];
    //         $vnp_OrderType = 'billpayment';
    //         $vnp_Amount = $data['TotalBill'] * 100;
    //         $vnp_Locale = 'vn';
    //         $vnp_BankCode = 'NCB';
    //         $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
    
    //         $inputData = array(
    //             "vnp_Version" => "2.1.0",
    //             "vnp_TmnCode" => $vnp_TmnCode,
    //             "vnp_Amount" => $vnp_Amount,
    //             "vnp_Command" => "pay",
    //             "vnp_CreateDate" => date('YmdHis'),
    //             "vnp_CurrCode" => "VND",
    //             "vnp_IpAddr" => $vnp_IpAddr,
    //             "vnp_Locale" => $vnp_Locale,
    //             "vnp_OrderInfo" => $vnp_OrderInfo,
    //             "vnp_OrderType" => $vnp_OrderType,
    //             "vnp_ReturnUrl" => $vnp_Returnurl,
    //             "vnp_TxnRef" => $vnp_TxnRef
    //         );
    
    //         if (isset($vnp_BankCode) && $vnp_BankCode != "") {
    //             $inputData['vnp_BankCode'] = $vnp_BankCode;
    //         }
    //         if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
    //             $inputData['vnp_Bill_State'] = $vnp_Bill_State;
    //         }
    
    //         ksort($inputData);
    //         $query = "";
    //         $i = 0;
    //         $hashdata = "";
    //         foreach ($inputData as $key => $value) {
    //             if ($i == 1) {
    //                 $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    //             } else {
    //                 $hashdata .= urlencode($key) . "=" . urlencode($value);
    //                 $i = 1;
    //             }
    //             $query .= urlencode($key) . "=" . urlencode($value) . '&';
    //         }
    
    //         $vnp_Url = $vnp_Url . "?" . $query;
    //         if (isset($vnp_HashSecret)) {
    //             $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
    //             $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
    //         }
    //         $returnData = array(
    //             'code' => '00', 'message' => 'success', 'data' => $vnp_Url
    //         );
    //         if (isset($_POST['redirect'])) {
    //             header('Location: ' . $vnp_Url);
    //             die();
    //         } else {
    //             echo json_encode($returnData);
    //         }
    //     } else if ($data['checkout'] == 'cash') {
    //         //tìm address từ request ở bảng address
    //         $get_address = AddressCustomer::find($data['address_rdo']);
    //         $Order->idCustomer = Session::get('idCustomer');
    //         $Order->TotalBill = $data['TotalBill'];
    //         $Order->Voucher = $data['Voucher'];
    //         $Order->Address = $get_address->Address;
    //         $Order->PhoneNumber = $get_address->PhoneNumber;
    //         $Order->CustomerName = $get_address->CustomerName;
    //         $Order->Payment = 'cash';
    
    //         $Order->save();
    //         //truy vấn order lấy đơn hàng vừa tạo của kh
    //         $get_Order = Order::where('created_at', now())
    //                           ->where('idCustomer', Session::get('idCustomer'))->first();

    //         // lấy giỏ hàng của kh
    //         $get_cart = Cart::where('idCustomer', Session::get('idCustomer'))->get();
    
    //         //thêm chi tiết vào orderdetail 
    //         foreach ($get_cart as $key => $cart) {
    //             $data_orderdetail = array(
    //                 'idOrder' => $get_Order->idOrder,//id được lấy từ get order
    //                 'idProduct' => $cart->idProduct,
    //                 'AttributeProduct' => $cart->AttributeProduct,
    //                 'Price' => $cart->Price,
    //                 'QuantityBuy' => $cart->QuantityBuy,
    //                 'idProAttr' => $cart->idProAttr,
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             );
    //             OrderDetail::insert($data_orderdetail);//thêm dữ liệu vào bảng
    
    //             //cập nhật số lượng sản phẩm 
    //             DB::update('UPDATE product SET QuantityTotal = QuantityTotal - ? WHERE idProduct = ?', [
    //                 $cart->QuantityBuy,
    //                 $cart->idProduct,
    //             ]);
    
    //             DB::update('UPDATE product_attribute SET Quantity = Quantity - ? WHERE idProAttr = ?', [
    //                 $cart->QuantityBuy,
    //                 $cart->idProAttr,
    //             ]);
    //         }
    //         if (!empty($get_Order->Voucher)) {
    //             DB::table('voucher')
    //                 ->where('idVoucher', $data['idVoucher'])    
    //                 ->decrement('VoucherQuantity');
    //         }
            
    //         //Cart::where('idCustomer', Session::get('idCustomer'))->delete();
    //         return Redirect::to('success-order')->send();
    //     }
    // }
    



    public function submit_payment(Request $request)
    {
        $data = $request->all();
        $Order = new Order();
    
        // Kiểm tra xem người dùng đã nhập địa chỉ chưa
        if (!isset($data['address_rdo']) || empty($data['address_rdo'])) {
            return redirect()->back()->with('error', 'Bạn chưa nhập địa chỉ');
        }
    
        if ($data['checkout'] == 'vnpay') {
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = rtrim(getenv('APP_URL'), '/') . '/success-order';
    
            $vnp_TmnCode = "CGXZLS0Z";
            $vnp_HashSecret = "XNBCJFAKAZQSGTARRLGCHVZWCIOIGSHN";
    
            $vnp_TxnRef = uniqid(); // Mã đơn hàng duy nhất.
            $vnp_OrderInfo = $data['address_rdo'] . '_' . Session::get('idCustomer').'_'.$data['Voucher'].'_'.$data['idVoucher'];
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $data['TotalBill'] * 100;
            $vnp_Locale = 'vn';
            $vnp_BankCode = 'NCB';
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
    
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
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
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
            //tìm address từ request ở bảng address
            $get_address = AddressCustomer::find($data['address_rdo']);
            $Order->idCustomer = Session::get('idCustomer');
            $Order->TotalBill = $data['TotalBill'];
            $Order->Voucher = $data['Voucher'];
            $Order->Address = $get_address->Address;
            $Order->PhoneNumber = $get_address->PhoneNumber;
            $Order->CustomerName = $get_address->CustomerName;
            $Order->Payment = 'cash';
    
            // Kiểm tra nếu idVoucher tồn tại trong request
            if (isset($data['idVoucher']) && !empty($data['idVoucher'])) {
                $Order->idVoucher = $data['idVoucher'];
            }
    
            $Order->save();
            //truy vấn order lấy đơn hàng vừa tạo của kh
            $get_Order = Order::where('created_at', now())
                              ->where('idCustomer', Session::get('idCustomer'))->first();
    
            // lấy giỏ hàng của kh
            $get_cart = Cart::where('idCustomer', Session::get('idCustomer'))->get();
    
            //thêm chi tiết vào orderdetail 
            foreach ($get_cart as $key => $cart) {
                $data_orderdetail = array(
                    'idOrder' => $get_Order->idOrder,//id được lấy từ get order
                    'idProduct' => $cart->idProduct,
                    'AttributeProduct' => $cart->AttributeProduct,
                    'Price' => $cart->Price,
                    'QuantityBuy' => $cart->QuantityBuy,
                    'idProAttr' => $cart->idProAttr,
                    'created_at' => now(),
                    'updated_at' => now()
                );
                OrderDetail::insert($data_orderdetail);//thêm dữ liệu vào bảng
    
                //cập nhật số lượng sản phẩm 
                DB::update('UPDATE product SET QuantityTotal = QuantityTotal - ? WHERE idProduct = ?', [
                    $cart->QuantityBuy,
                    $cart->idProduct,
                ]);
    
                DB::update('UPDATE product_attribute SET Quantity = Quantity - ? WHERE idProAttr = ?', [
                    $cart->QuantityBuy,
                    $cart->idProAttr,
                ]);
            }
            if (!empty($get_Order->Voucher)) {
                DB::table('voucher')
                    ->where('idVoucher', $data['idVoucher'])    
                    ->decrement('VoucherQuantity');
            }
            
            //Cart::where('idCustomer', Session::get('idCustomer'))->delete();
            return Redirect::to('success-order')->send();
        }
    }
    











    


    //xử lý thanh toán thành công vnpay
    public function success_order(Request $request)
    {
        $list_category = Category::get();
        $list_brand = Brand::get();
        //trạng thái 00 là mã trạng thái thành công
        if ($request->vnp_TransactionStatus && $request->vnp_TransactionStatus == '00') {

            $Order = new Order();
            $OrderHistory = new OrderHistory();
            $OrderInfo = explode("_", $request->vnp_OrderInfo);// Phân tách chuỗi thông tin đơn hàng  nhận từ VNPay

            //Tìm địa chỉ khách hàng từ cơ sở dữ liệu
            $get_address = AddressCustomer::find($OrderInfo[0]);
            $Order->idCustomer = $OrderInfo[1];
            $Order->TotalBill = $request->vnp_Amount / 100;
            $Order->Voucher = $OrderInfo[2];//id
            $Order->Address = $get_address->Address;
            $Order->PhoneNumber = $get_address->PhoneNumber;
            $Order->CustomerName = $get_address->CustomerName;
            $Order->Status = 1;
            $Order->Payment = 'vnpay';

            $Order->save();
            $get_Order = Order::where('created_at', now())->where('idCustomer', $OrderInfo[1])->first();
            $get_cart = Cart::where('idCustomer', $OrderInfo[1])->get();

            foreach ($get_cart as $key => $cart) {
                $data_orderdetail = array(
                    'idOrder' => $get_Order->idOrder,
                    'idProduct' => $cart->idProduct,
                    'AttributeProduct' => $cart->AttributeProduct,
                    'Price' => $cart->Price,
                    'QuantityBuy' => $cart->QuantityBuy,
                    'idProAttr' => $cart->idProAttr,
                    'created_at' => now(),
                    'updated_at' => now()
                );
                OrderDetail::insert($data_orderdetail);

                
                DB::update('update product set QuantityTotal = QuantityTotal - ? where idProduct = ?', [$cart->QuantityBuy, $cart->idProduct]);
                DB::update('update product_attribute set Quantity = Quantity - ? where idProAttr = ?', [$cart->QuantityBuy, $cart->idProAttr]);
              
            }

            if (!empty($get_Order->Voucher)) {
                DB::table('voucher')
                    ->where('idVoucher', $OrderInfo[3])
                    ->decrement('VoucherQuantity');
            }
            Cart::where('idCustomer', $OrderInfo[1])->delete();
            $OrderHistory->idOrder = $get_Order->idOrder;
            $OrderHistory->AdminName = 'System';
            $OrderHistory->Status = 1;// khi đã được xác nhận sẽ lưu vào bảng history
            $OrderHistory->save();
            return view("shop.cart.success-order")->with(compact('list_category', 'list_brand'));
        } else if ($request->vnp_TransactionStatus && $request->vnp_TransactionStatus != '00')
            return Redirect::to('cart');
        else return view("shop.cart.success-order")->with(compact('list_category', 'list_brand'));
    }




    public function delete_order(Request $request, $idOrder)
    {
        if ($request->isMethod('post')) {
            try {
                // Tìm đơn hàng
                $order = Order::find($idOrder);

                if (!$order) {
                    return response()->json(['success' => false, 'message' => 'Đơn hàng không tồn tại.'], 404);
                }

                // Cập nhật trạng thái đơn hàng
                $order->update(['Status' => 99]);


                if($order->Voucher != ''){
                    $Voucher = explode("-",$order->Voucher);
                    $idVoucher = $Voucher[0];
                    DB::update('update voucher set VoucherQuantity = VoucherQuantity + 1 where idVoucher = ?',[$idVoucher]);
                } 


                // Cập nhật số lượng sản phẩm
                $OrderDetail = OrderDetail::where('idOrder', $idOrder)->get();
                foreach ($OrderDetail as $bi) {
                    DB::update('update product_attribute set Quantity = Quantity + ? where idProAttr = ?', [$bi->QuantityBuy, $bi->idProAttr]);
                    DB::update('update product set QuantityTotal = QuantityTotal + ? where idProduct = ?', [$bi->QuantityBuy, $bi->idProduct]);
                }



                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                // Log lỗi và trả về phản hồi lỗi
                Log::error('Error deleting order: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra. Vui lòng thử lại.'], 500);
            }
        }

        return response()->json(['success' => false, 'message' => 'Method not allowed'], 405);
    }




     // Áp dụng mã giảm giá
    public function check_voucher(Request $request){
        $data = $request->all();

        $check_voucher = Voucher::whereRaw('BINARY `VoucherCode` = ?',[$data['VoucherCode']])->first();

        if($check_voucher){
            if($check_voucher->VoucherEnd < now()) echo $output = 'Mã giảm giá này đã hết hạn';
            else if($check_voucher->VoucherStart > now()) echo $output = 'Chưa đến thời gian áp dụng mã giảm giá này';
            else if($check_voucher->VoucherQuantity <= 0) echo $output = 'Mã giảm giá này đã hết số lần sử dụng';
            else{
        
                echo $output = 'Success-'.$check_voucher->VoucherCondition.'-'.$check_voucher->VoucherNumber.'-'.$check_voucher->idVoucher;

                
            }
          
        }else {
            echo $output = 'Mã giảm giá không hợp lệ';

        }
    }


    
    
    

























    //=========================ADMIN=======================//

    //===========BILL=======ORDER========//

    // Kiểm tra đăng nhập
    public function checkLogin_Admin()
    {
        $idAdmin = Session::get('idAdmin');
        if ($idAdmin == false) return Redirect::to('admin')->send();
    }

    // Hiện tất cả đơn đặt hàng
    public function list_order()
    {
        $this->checkLogin_Admin();
        $list_order = Order::join('customer', 'order.idCustomer', '=', 'customer.idCustomer')->whereNotIn('Status', [99])
            ->select('customer.username', 'customer.PhoneNumber as CusPhone', 'order.*')->get();
        return view("admin.order.list-order")->with(compact('list_order'));
    }


    // Hiện chi tiết đơn đặt hàng
    public function order_info($idOrder)
    {
        $this->checkLogin_Admin();

        $address = Order::where('idOrder', $idOrder)->first();

        $list_order_info = OrderDetail::join('product', 'product.idProduct', '=', 'orderdetail.idProduct')
            ->join('productimage', 'productimage.idProduct', '=', 'orderdetail.idProduct')
            ->where('orderdetail.idOrder', $idOrder)
            ->select('product.ProductName', 'product.idProduct', 'productimage.ImageName', 'orderdetail.*')->get();

        return view("admin.order.order-info")->with(compact('address', 'list_order_info'));
    }

    // Hiện tất cả đơn đặt hàng đang chờ xác nhận
    public function waiting_order()
    {
        $this->checkLogin_Admin();

        $list_order = Order::join('customer', 'order.idCustomer', '=', 'customer.idCustomer')->where('Status', '0')
            ->select('order.*', 'customer.username', 'customer.PhoneNumber as CusPhone')->get();
        return view("admin.order.waiting-order")->with(compact('list_order'));
    }

    // Hiện tất cả đơn đặt hàng đang giao
    public function shipping_order()
    {
        $this->checkLogin_Admin();

        $list_order = Order::join('customer', 'order.idCustomer', '=', 'customer.idCustomer')
            ->join('orderhistory', 'orderhistory.idOrder', '=', 'order.idOrder')->where('order.Status', '1')
            ->select('order.*', 'customer.username', 'customer.PhoneNumber as CusPhone', 'orderhistory.AdminName', 'orderhistory.created_at AS TimeConfirm')->get();
        return view("admin.order.shipping-order")->with(compact('list_order'));
    }


    // Hiện tất cả đơn đặt hàng đã giao
    public function shipped_order()
    {
        $this->checkLogin_Admin();

        $list_order = Order::join('customer', 'order.idCustomer', '=', 'customer.idCustomer')->where('order.Status', '2')
            ->select('order.*', 'customer.username', 'customer.PhoneNumber as CusPhone')->get();
        return view("admin.order.shipped-order")->with(compact('list_order'));
    }

    // Hiện tất cả đơn đặt hàng đã hủy
    public function cancelled_order()
    {
        $this->checkLogin_Admin();

        $list_order = Order::join('customer', 'order.idCustomer', '=', 'customer.idCustomer')
            ->join('orderhistory', 'orderhistory.idOrder', '=', 'order.idOrder')->where('order.Status', '99')
            ->select('order.*', 'customer.username', 'customer.PhoneNumber as CusPhone', 'orderhistory.AdminName', 'orderhistory.created_at AS TimeConfirm')->get();
        return view("admin.order.cancelled-order")->with(compact('list_order'));
    }
    // Hiện tất cả đơn đặt hàng đã xác nhận
    public function confirmed_order()
    {
        $this->checkLogin_Admin();

        $list_order = Order::join('customer', 'order.idCustomer', '=', 'customer.idCustomer')
            ->join('orderhistory', 'orderhistory.idOrder', '=', 'order.idOrder')->where('orderhistory.Status', '1')
            ->select('order.*', 'customer.username', 'customer.PhoneNumber as CusPhone', 'orderhistory.AdminName', 'orderhistory.created_at AS TimeConfirm')->get();
        return view("admin.order.confirmed-order")->with(compact('list_order'));
    }





    // Xác nhận đơn hàng
    public function confirm_bill(Request $request, $idOrder)
    {
        // Tìm và cập nhật đơn hàng
        $order = Order::find($idOrder);

        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại.');
        }

        if ($request->Status == 2) {
            // Cập nhật ngày nhận và trạng thái đơn hàng
            $order->update(['ReceiveDate' => now(), 'Status' => $request->Status]);

            // Không cần cập nhật cột Sold vì bảng product không có cột này
            // Nếu bạn cần thực hiện các cập nhật khác, hãy thêm mã ở đây
        } else {
            // Chỉ cập nhật trạng thái đơn hàng
            $order->update(['Status' => $request->Status]);
        }

        // Lưu lịch sử đơn hàng
        $OrderHistory = new OrderHistory();
        $OrderHistory->idOrder = $idOrder;
        $OrderHistory->idAdmin= Session::get('idAdmin');
        $OrderHistory->AdminName = Session::get('AdminName');
        $OrderHistory->Status = $request->Status;
        $OrderHistory->save();

        return redirect()->back()->with('success', 'Đơn hàng đã được cập nhật.');
    }

    public function delete_bill($idOrder)
    {
        $OrderHistory = new OrderHistory();
        $OrderHistory->idOrder = $idOrder;
        $OrderHistory->AdminName = Session::get('AdminName');
        $OrderHistory->Status = 99;
        $OrderHistory->save();
        Order::find($idOrder)->update(['Status' => '99']);
        $Order = Order::find($idOrder);

        $OrderDetail = OrderDetail::where('idOrder', $idOrder)->get();
        foreach ($OrderDetail as $key => $bi) {
            DB::update('update product_attribute set Quantity = Quantity + ? where idProAttr = ?', [$bi->QuantityBuy, $bi->idProAttr]);
            DB::update('update product set QuantityTotal = QuantityTotal + ? where idProduct = ?', [$bi->QuantityBuy, $bi->idProduct]);
        }
    }
}
