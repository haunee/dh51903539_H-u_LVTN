<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
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
    public function show_cart()
    {
        $this->checkLogin();
        $this->checkCart();
        $list_category = Category::get();
        $list_brand = Brand::get();
        $recommend_pds_arrays = [];
        $checked_pro = [];

        $list_pd_cart = Cart::join('product', 'product.idProduct', '=', 'cart.idProduct')
            ->join('productimage', 'productimage.idProduct', 'cart.idProduct')
            ->join('product_attribute', 'product_attribute.idProAttr', '=', 'cart.idProAttr')
            ->where('idCustomer', Session::get('idCustomer'))->get();

        foreach ($list_pd_cart as $key => $pd_cart) {
            $idBrand = $pd_cart->idBrand;
            $idCategory = $pd_cart->idCategory;

            // Mảng các sản phẩm đã lặp qua
            $checked_pro[] = $pd_cart->idProduct;

            // Danh sách sản phẩm gợi ý của 1 sản phẩm trong giỏ hàng
            $list_recommend_pds = Product::whereNotIn('idProduct', [$pd_cart->idProduct])
                ->where(function ($query) use ($idBrand, $idCategory) {
                    $query->orWhere('idBrand', $idBrand)
                        ->orWhere('idCategory', $idCategory);
                })
                ->select('idProduct')
                ->get();

            if ($list_recommend_pds->count() > 0) {
                // Thêm từng sản phẩm gợi ý của 1 sản phẩm vào 1 mảng
                foreach ($list_recommend_pds as $recommend_pd) {
                    $recommend_pds_array[$key][] = $recommend_pd->idProduct;
                }

                // Thêm từng mảng thứ $key vào 1 mảng lớn
                $recommend_pds_arrays[] = $recommend_pds_array[$key];
            }
        }

        if (count($recommend_pds_arrays) > 0) {
            // Hàm gộp mảng, xen kẽ các phần tử của từng mảng
            $output = [];
            for ($args = $recommend_pds_arrays; count($args); $args = array_filter($args)) {
                // &$arg allows array_shift() to change the original.
                foreach ($args as &$arg) {
                    $output[] = array_shift($arg);
                }
            }

            $recommend_pds_last = array_diff($output, $checked_pro); // Xóa các sản phẩm đã lặp qua
            $recommend_pds_unique = array_unique($recommend_pds_last); // Lọc các phần tử trùng nhau
            $recommend_pds = json_encode($recommend_pds_unique);
        } else {
            $featured_pds = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
                ->orderBy('product.created_at', 'DESC')  // Specify 'product' table
                ->select('product.idProduct')
                ->get();

            $featured_pds_array = $featured_pds->pluck('idProduct')->toArray();

            $recommend_pds = json_encode($featured_pds_array);
        }

        return view('shop.cart.cart')->with(compact('list_category', 'list_brand', 'list_pd_cart', 'recommend_pds'));
    }






    // Xóa 1 sản phẩm trong giỏ hàng
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
     public function empty_cart(){
        $list_category = Category::get();
        $list_brand = Brand::get();
        return view("shop.cart.empty-cart")->with(compact('list_category','list_brand'));
    }
}
