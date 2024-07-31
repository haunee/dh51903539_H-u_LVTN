<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Cart;
use App\Models\OrderDetail;
use App\Models\ProductAttriBute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\Viewer;
use App\Models\WishList;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    // Kiểm tra đăng nhập
    public function checkLogin()
    {
        $idCustomer = Session::get('idCustomer');
        if ($idCustomer == false) return Redirect::to('/login')->send();
    }




    //===================ADMIN====================//

    public function add_product()
    {
        $list_category = Category::get();
        $list_brand = Brand::get();
        $list_attribute = Attribute::get();
        return view('admin.product.add-product')->with(compact('list_category', 'list_brand', 'list_attribute'));
    }


    // //them san pham
    // public function submit_add_product(Request $request)
    // {
    //     $data = $request->all();

    //     $select_product = Product::where('ProductName', $data['ProductName'])->first();

    

    //     if ($select_product) {
    //         return redirect()->back()->with('error', 'Sản phẩm này đã tồn tại');
    //     } else {

    //         $product = new Product();
    //         $product_image = new ProductImage();

    //         $product->ProductName = $data['ProductName'];
    //         $product->idCategory = $data['idCategory'];
    //         $product->idBrand = $data['idBrand'];
    //         $product->Price = $data['Price'];
    //         $product->QuantityTotal = $data['QuantityTotal'];
    //         $product->ShortDes = $data['ShortDes'];
    //         $product->DesProduct = $data['DesProduct'];
    //         $get_image = $request->file('ImageName');
    //         $timestamp = now();

    //         $product->save();
    //         $get_pd = Product::where('created_at', $timestamp)->first();

    //         // Thêm phân loại vào Product_Attribute
    //         if ($request->qty_attr) {
    //             foreach ($data['qty_attr'] as $key => $qty_attr) {
    //                 $data_all = array(
    //                     'idProduct' => $get_pd->idProduct,
    //                     'idAttriValue' => $data['chk_attr'][$key],
    //                     'Quantity' => $qty_attr,
    //                     'created_at' => now(),
    //                     'updated_at' => now()
    //                 );
    //                 ProductAttriBute::insert($data_all);
    //             }
    //         } else {
    //             $data_all = array(
    //                 'idProduct' => $get_pd->idProduct,
    //                 'Quantity' => $data['QuantityTotal'],
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             );
    //             ProductAttriBute::insert($data_all);
    //         }

    //         // Thêm hình ảnh vào table ProductImage
    //         foreach ($get_image as $image) {
    //             $get_name_image = $image->getClientOriginalName();
    //             $name_image = current(explode('.', $get_name_image));
    //             $new_image = $name_image . rand(0, 99) . '.' . $image->getClientOriginalExtension();
    //             $image->storeAs('public/kidadmin/images/product', $new_image);
    //             $images[] = $new_image;
    //         }

    //         $product_image->ImageName = json_encode($images);
    //         $product_image->idProduct = $get_pd->idProduct;
    //         $product_image->save();
    //         return redirect()->back()->with('message', 'Thêm sản phẩm thành công');
    //     }
    // }
    public function submit_add_product(Request $request)
{
    $data = $request->all();

    // Kiểm tra giá trị số lượng phân loại
    if (isset($data['qty_attr']) && is_array($data['qty_attr'])) {
        foreach ($data['qty_attr'] as $quantity) {
            if ($quantity < 0) {
                return redirect()->back()->with('error', 'Số lượng phân loại không được là số âm');
            }
        }
    }

    // Kiểm tra sản phẩm đã tồn tại
    $select_product = Product::where('ProductName', $data['ProductName'])->first();
    if ($select_product) {
        return redirect()->back()->with('error', 'Sản phẩm này đã tồn tại');
    } else {
        $product = new Product();
        $product_image = new ProductImage();

        $product->ProductName = $data['ProductName'];
        $product->idCategory = $data['idCategory'];
        $product->idBrand = $data['idBrand'];
        $product->Price = $data['Price'];
        $product->QuantityTotal = $data['QuantityTotal'];
        $product->ShortDes = $data['ShortDes'];
        $product->DesProduct = $data['DesProduct'];
        $get_image = $request->file('ImageName');
        $timestamp = now();

        $product->save();
        $get_pd = Product::where('created_at', $timestamp)->first();

        // Thêm phân loại vào Product_Attribute
        if ($request->qty_attr) {
            foreach ($data['qty_attr'] as $key => $qty_attr) {
                $data_all = array(
                    'idProduct' => $get_pd->idProduct,
                    'idAttriValue' => $data['chk_attr'][$key],
                    'Quantity' => $qty_attr,
                    'created_at' => now(),
                    'updated_at' => now()
                );
                ProductAttriBute::insert($data_all);
            }
        } else {
            $data_all = array(
                'idProduct' => $get_pd->idProduct,
                'Quantity' => $data['QuantityTotal'],
                'created_at' => now(),
                'updated_at' => now()
            );
            ProductAttriBute::insert($data_all);
        }

        // Thêm hình ảnh vào table ProductImage
        foreach ($get_image as $image) {
            $get_name_image = $image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . rand(0, 99) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/kidadmin/images/product', $new_image);
            $images[] = $new_image;
        }

        $product_image->ImageName = json_encode($images);
        $product_image->idProduct = $get_pd->idProduct;
        $product_image->save();
        return redirect()->back()->with('message', 'Thêm sản phẩm thành công');
    }
}






    public function manage_product()
    {
        $list_product = Product::join('brand', 'brand.idBrand', '=', 'product.idBrand')
            ->join('category', 'category.idCategory', '=', 'product.idCategory')
            ->join('productimage', 'productimage.idProduct', '=', 'product.idProduct')->get();
        $count_product = Product::count();

        return view("admin.product.manage-product")->with(compact('list_product', 'count_product'));
    }


  


    // Xóa sản phẩm
    public function delete_product($idProduct)
    {

    $productImages = ProductImage::where('idProduct', $idProduct)->get();
    foreach ($productImages as $productImage) {
        foreach (json_decode($productImage->ImageName) as $old_img) {
            Storage::delete('public/kidadmin/images/product/' . $old_img);
        }
        $productImage->delete();
    }

    ProductAttribute::where('idProduct', $idProduct)->delete();

    Cart::where('idProduct', $idProduct)->delete();

    OrderDetail::where('idProduct', $idProduct)->delete();
    
    Viewer::where('idProduct', $idProduct)->delete();
 
    Wishlist::where('idProduct', $idProduct)->delete();

    Product::find($idProduct)->delete();

    return redirect()->back()->with('success', 'Product deleted successfully.');
}








    // Chuyển đến trang sửa sản phẩm
    public function edit_product($idProduct)
    {
        $product = Product::join('brand', 'brand.idBrand', '=', 'product.idBrand')
            ->join('category', 'category.idCategory', '=', 'product.idCategory')
            ->join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->where('product.idProduct', $idProduct)->first();

        $list_pd_attr = ProductAttriBute::join('attributevalue', 'attributevalue.idAttriValue', '=', 'product_attribute.idAttriValue')
            ->join('attribute', 'attribute.idAttribute', '=', 'attributevalue.idAttribute')
            ->where('product_attribute.idProduct', $idProduct)->get();

        $name_attribute = ProductAttriBute::join('attributevalue', 'attributevalue.idAttriValue', '=', 'product_attribute.idAttriValue')
            ->join('attribute', 'attribute.idAttribute', '=', 'attributevalue.idAttribute')
            ->where('product_attribute.idProduct', $idProduct)->first();

        $list_attribute = Attribute::get();
        $list_category = Category::get();
        $list_brand = Brand::get();

        return view("admin.product.edit-product")->with(compact('product', 'list_category', 'list_brand', 'list_attribute', 'list_pd_attr', 'name_attribute'));
    }







    // Sửa sản phẩm
    public function submit_edit_product(Request $request, $idProduct)
    {
        $data = $request->all();
        $product = Product::find($idProduct);

        if (isset($data['qty_attr']) && is_array($data['qty_attr'])) {
            foreach ($data['qty_attr'] as $quantity) {
                if ($quantity < 0) {
                    return redirect()->back()->with('error', 'Số lượng phân loại không được là số âm');
                }
            }
        }
    
        $select_product = Product::where('ProductName', $data['ProductName'])->whereNotIn('idProduct', [$idProduct])->first();

        if ($select_product) {
            return redirect()->back()->with('error', 'Sản phẩm này đã tồn tại');
        } else {
            $product_image = new ProductImage();
            $product->ProductName = $data['ProductName'];
            $product->idCategory = $data['idCategory'];
            $product->idBrand = $data['idBrand'];
            $product->Price = $data['Price'];
            $product->QuantityTotal = $data['QuantityTotal'];
            $product->ShortDes = $data['ShortDes'];
            $product->DesProduct = $data['DesProduct'];


            // Sửa phân loại Product_Attribute
            if ($request->qty_attr) {
                ProductAttriBute::where('idProduct', $idProduct)->delete();
                foreach ($data['qty_attr'] as $key => $qty_attr) {
                    $data_all = array(
                        'idProduct' => $idProduct,
                        'idAttriValue' => $data['chk_attr'][$key],
                        'Quantity' => $qty_attr,
                        'created_at' => now(),
                        'updated_at' => now()
                    );
                    ProductAttriBute::insert($data_all);
                }
            } else {
                ProductAttriBute::where('idProduct', $idProduct)->delete();
                $data_all = array(
                    'idProduct' => $idProduct,
                    'Quantity' => $data['QuantityTotal'],
                    'created_at' => now(),
                    'updated_at' => now()
                );
                ProductAttriBute::insert($data_all);
            }

            // Thêm hình ảnh vào table ProductImage
            if ($request->file('ImageName')) {
                $get_image = $request->file('ImageName');

                foreach ($get_image as $image) {
                    $get_name_image = $image->getClientOriginalName();
                    $name_image = current(explode('.', $get_name_image));
                    $new_image = $name_image . rand(0, 99) . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('public/kidadmin/images/product', $new_image);
                    $images[] = $new_image;
                }

                // Xoá hình cũ trong csdl và trong folder 
                $get_old_mg = ProductImage::where('idProduct', $idProduct)->first();
                foreach (json_decode($get_old_mg->ImageName) as $old_img) {
                    Storage::delete('public/kidadmin/images/product/' . $old_img);
                }
                ProductImage::where('idProduct', $idProduct)->delete();

                $product_image->ImageName = json_encode($images);
                $product_image->idProduct = $idProduct;
                $product_image->save();
            }
            $product->save();
            return redirect()->back()->with('message', 'Sửa sản phẩm thành công');
        }
    }




    //===============================SHOP===============================//

    public function show_all_product()
    {
        //lấy id customer từ session
        $idCustomer = session('idCustomer');
        $idProduct = session('idProduct');
        // Lấy danh sách các sản phẩm yêu thích của người dùng
        $wishlistProducts = WishList::where('idCustomer', $idCustomer)->pluck('idProduct')->toArray();


        $list_category = Category::get();
        $list_brand = Brand::get();

       
     
   
        // danh sách sản phẩm
        $list_pd_query = Product::join('brand', 'brand.idBrand', '=', 'product.idBrand') 
            ->join('category', 'category.idCategory', '=', 'product.idCategory') 
            ->join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->select('product.*', 'ImageName', 'BrandName', 'CategoryName') 
            ->withCount(['orderdetail as Sold' => function ($query) { 
                $query->select(DB::raw('COALESCE(SUM(QuantityBuy), 0)'));
                //coalesce..0 chuyển đổi trả về 0 thay vì null
            }]);

    
        // Kiểm tra nếu tham số 'brand' được truyền vào URL, nếu có thì tách thành mảng
        if (isset($_GET['brand'])) $brand_arr = explode(",", $_GET['brand']);

  
        if (isset($_GET['category'])) $category_arr = explode(",", $_GET['category']);

    
        if (isset($_GET['category']) && isset($_GET['brand'])) {

        
            $list_pd_query->whereIn('product.idCategory', $category_arr)->whereIn('product.idBrand', $brand_arr);
            

        } else if (isset($_GET['brand'])) {

           
            $list_pd_query->whereIn('product.idBrand', $brand_arr);

        
        } else if (isset($_GET['category'])) {
            
            $list_pd_query->whereIn('product.idCategory', $category_arr);
        }

       
        if (isset($_GET['priceMin']) && isset($_GET['priceMax'])) {
         
            $list_pd_query->whereBetween('Price', [$_GET['priceMin'], $_GET['priceMax']]);
           
        } else if (isset($_GET['priceMin'])) {
            
            $list_pd_query->whereRaw('Price >= ?', $_GET['priceMin']);
           
        } else if (isset($_GET['priceMax'])) {
           
            $list_pd_query->whereRaw('Price <= ?', $_GET['priceMax']);
        }
      
        if (isset($_GET['sort_by'])) {
          
            if ($_GET['sort_by'] == 'new') $list_pd_query->orderBy('created_at', 'desc');
          
            else if ($_GET['sort_by'] == 'bestsellers') $list_pd_query->orderBy('Sold', 'desc');
          
            else if ($_GET['sort_by'] == 'price_desc') $list_pd_query->orderBy('Price', 'desc');
    
            else if ($_GET['sort_by'] == 'price_asc') $list_pd_query->orderBy('Price', 'asc');
           
        } else $list_pd_query->orderBy('created_at', 'desc');
    
        $count_pd = $list_pd_query->count();
        
        $list_pd = $list_pd_query->paginate(15);
        $filters = request()->except('page');
        return view("shop.product.shop-all-product")->with(compact('list_category','filters','list_brand', 'list_pd', 'count_pd', 'wishlistProducts'));
    }





    public function show_product_details($idProduct)
    {

        $idCustomer = session('idCustomer');
        $wishlistProducts = WishList::where('idCustomer', $idCustomer)->pluck('idProduct')->toArray();

        $list_category = Category::get();
        $list_brand = Brand::get();

        $this_pro = Product::where('idProduct', $idProduct)->first();

        $viewer = new Viewer();

        //kiểm tra id có trong session chưa
        if (Session::get('idCustomer') == '') $idCustomer = session()->getId(); //chưa sẽ được gán id sesstion hiện tại
        else $idCustomer = (string)Session::get('idCustomer'); //tồn tại id sẽ được gán gtri tu session ép kiểu về chuỗi

        $viewer->idCustomer = $idCustomer; 
        $viewer->idProduct = $this_pro->idProduct; 


        //truy vấn bảng view có idcustomer và idproduct bằng gtri hiện tại và k có bảng ghi 
        //tức là chưa xem sản phẩm này 
        if (Viewer::where('idCustomer', $idCustomer)->where('idProduct', $this_pro->idProduct)->count() == 0) {
            if (Viewer::where('idCustomer', $idCustomer)->count() >= 3) { 
                $idView = Viewer::where('idCustomer', $idCustomer)->orderBy('idView', 'asc')->take(1)->delete();
                $viewer->save();
            } else $viewer->save();
        }

        $idBrand = $this_pro->idBrand;
        $idCategory = $this_pro->idCategory;


        //lấy danh sách thuộc tính sản phẩm phân loại 
     
        $list_pd_attr = ProductAttriBute::join('attributevalue', 'attributevalue.idAttriValue', '=', 'product_attribute.idAttriValue')
            ->join('attribute', 'attribute.idAttribute', '=', 'attributevalue.idAttribute') 
            ->where('product_attribute.idProduct', $this_pro->idProduct)->get();
        //điều kiện chỉ lấy những bảng ghi trong bảng product_attribute có idproduct khớp nhau


        //lấy tên phân loại 
       
        $name_attribute = ProductAttriBute::join('attributevalue', 'attributevalue.idAttriValue', '=', 'product_attribute.idAttriValue')
            ->join('attribute', 'attribute.idAttribute', '=', 'attributevalue.idAttribute') 
            ->where('product_attribute.idProduct', $this_pro->idProduct)->first();
        
        $product = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')->where('product.idProduct', $this_pro->idProduct)->first();


        //danh sách sản phẩm liên quan
        $list_related_products = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->whereNotIn('product.idProduct', [$this_pro->idProduct]) 
            ->where(function ($query) use ($idBrand, $idCategory) {
                $query->orWhere('idBrand', $idBrand)->orWhere('idCategory', $idCategory);
            })
            ->select('productimage.ImageName', 'product.*') 
            ->get();


        return view("shop.product.shop-single")->with(compact('list_category', 'list_related_products', 'list_brand', 'product', 'list_pd_attr', 'name_attribute', 'wishlistProducts'));
    }





    public function search_suggestions(Request $request)
    {
        Log::info('Search suggestions called');
     
        $value = $request->value;
        Log::info('Search value: ' . $value);
        $output = '';

        //tk danh mục
        $get_cat = Category::select('CategoryName')
            ->where('CategoryName', 'like', '%' . $value . '%')//tk gtri tu khoa với cột name 
            ->limit(3)
            ->get();
        Log::info('Categories found: ' . json_encode($get_cat->pluck('CategoryName')));

        $get_brand = Brand::select('BrandName')
            ->where('BrandName', 'like', '%' . $value . '%')
            ->limit(3)
            ->get();
        Log::info('Brands found: ' . json_encode($get_brand->pluck('BrandName')));

        //tk sp 
        $pds = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->join('brand', 'brand.idBrand', '=', 'product.idBrand')
            ->join('category', 'category.idCategory', '=', 'product.idCategory')
            ->where('ProductName', 'like', '%' . $value . '%')
            ->select('product.idProduct', 'product.ProductName', 'productimage.ImageName')
            ->limit(3)
            ->get();
        Log::info('Products found: ' . json_encode($pds->pluck('ProductName')));

        //nếu tìm thấy
        if ($get_cat->count() > 0) {
            $output .= '<h5 class="p-1">Danh mục</h5>';
            foreach ($get_cat as $cat) {
                $output .= '
                <li class="search-product-item">
                    <a class="search-product-text one-line" href="/search?keyword=' . urlencode($cat->CategoryName) . '">' . $cat->CategoryName . '</a>
                </li>';
            }
        }

        if ($get_brand->count() > 0) {
            $output .= '<h5 class="p-1">Thương hiệu</h5>';
            foreach ($get_brand as $brand) {
                $output .= '
                <li class="search-product-item">
                    <a class="search-product-text one-line" href="/search?keyword=' . urlencode($brand->BrandName) . '">' . $brand->BrandName . '</a>
                </li>';
            }
        }

        if ($pds->count() > 0) {
            $output .= '<h5 class="p-1">Sản phẩm</h5>';
            foreach ($pds as $pd) {
                $image = json_decode($pd->ImageName)[0];
                $output .= '
                <li class="search-product-item d-flex align-items-center">
                    <a class="search-product-text" href="/shop-single/' . $pd->idProduct . '">
                        <div class="d-flex align-items-center">
                            <img width="50" height="50" src="/storage/kidadmin/images/product/' . $image . '" alt="">
                            <span class="two-line ml-2">' . $pd->ProductName . '</span>
                        </div>
                    </a>
                </li>';
            }
        }

        echo $output;
    }


   





    // Chuyển đến trang danh sách yêu thích
    public function wishlist()
    {
        $this->checkLogin();
        $list_category = Category::get();
        $list_brand = Brand::get();

        $wishlist = WishList::join('product', 'product.idProduct', '=', 'wishlist.idProduct')
            ->join('productimage', 'productimage.idProduct', 'wishlist.idProduct')
            ->where('idCustomer', Session::get('idCustomer'))->get();
    

        return view("shop.product.wishlist")->with(compact('list_category', 'list_brand', 'wishlist'));
    }


    // Thêm vào danh sách yêu thích
    public function add_to_wishlist(Request $request)
    {
        $this->checkLogin();
        $data = $request->all();

        $idCustomer = Session::get('idCustomer');

        if (!$idCustomer) {
            return response()->json(['error' => 'User not logged in'], 500);
        }

        $select_product = WishList::where('idProduct', $data['idProduct'])
            ->where('idCustomer', $idCustomer)->get();

        if ($select_product->count() == 0) {
            try {
                $wishlist = new WishList();
                $wishlist->idCustomer = $idCustomer;
                $wishlist->idProduct = $data['idProduct'];
                $wishlist->save();
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to add to wishlist'], 500);
            }

            return response()->json(['success' => 'Product added to wishlist']);
        } else {
            return response()->json(['already_in_wishlist' => 'Product already in wishlist']);
        }
    }

    public function delete_wish($idWish)
    {
        $this->checkLogin();
        WishList::destroy($idWish);
    }









    public function search() {
        $keyword = $_GET['keyword'] ?? ''; // Lấy từ khóa tìm kiếm, mặc định là chuỗi rỗng
        $idCustomer = session('idCustomer');
        $wishlistProducts = WishList::where('idCustomer', $idCustomer)->pluck('idProduct')->toArray();
        
       
        $list_category = Category::get();
        $list_brand = Brand::get();
        
        // Tạo truy vấn tìm kiếm
        $list_pd_query = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->join('brand', 'brand.idBrand', '=', 'product.idBrand')
            ->join('category', 'category.idCategory', '=', 'product.idCategory')
            ->select('ImageName', 'product.*', 'BrandName', 'CategoryName');
          
    
        // Tìm kiếm theo tên sản phẩm, th, dm
        if ($keyword) {
            $list_pd_query->where(function ($query) use ($keyword) {
                $query->where('ProductName', 'like', '%' . $keyword . '%')
                      ->orWhere('BrandName', 'like', '%' . $keyword . '%')
                      ->orWhere('CategoryName', 'like', '%' . $keyword . '%');
            });
        }
        
        // Lọc theo danh mục
        if (isset($_GET['category'])) {
            $category_arr = explode(",", $_GET['category']);
            $list_pd_query->whereIn('product.idCategory', $category_arr);
        }
        
        // Lọc theo thương hiệu
        if (isset($_GET['brand'])) {
            $brand_arr = explode(",", $_GET['brand']);
            $list_pd_query->whereIn('product.idBrand', $brand_arr);
        }
        
        // Lọc theo giá
        if (isset($_GET['priceMin']) && isset($_GET['priceMax'])) {
            $list_pd_query->whereBetween('Price', [$_GET['priceMin'], $_GET['priceMax']]);
        } else if (isset($_GET['priceMin'])) {
            $list_pd_query->where('Price', '>=', $_GET['priceMin']);
        } else if (isset($_GET['priceMax'])) {
            $list_pd_query->where('Price', '<=', $_GET['priceMax']);
        }
        
        // Sắp xếp
        if (isset($_GET['sort_by'])) {
            switch ($_GET['sort_by']) {
                case 'new':
                    $list_pd_query->orderBy('product.created_at', 'desc');
                    break;
                case 'old':
                    $list_pd_query->orderBy('product.created_at', 'asc');
                    break;
                case 'price_desc':
                    $list_pd_query->orderBy('Price', 'desc');
                    break;
                case 'price_asc':
                    $list_pd_query->orderBy('Price', 'asc');
                    break;
            }
        }
        
        $count_pd = $list_pd_query->count();
        $list_pd = $list_pd_query->paginate(15);
        
        
        $top_bestsellers_pd = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->orderBy('product.created_at', 'desc') 
            ->limit(3)
            ->get();
        
        return view("shop.search")->with(compact('list_category', 'wishlistProducts', 'list_brand', 'list_pd', 'count_pd', 'keyword', 'top_bestsellers_pd'));
    }
    
    
    
    
    
    
    
    









    public static function count_cat_search($idCategory) {
        $keyword = $_GET['keyword'] ?? ''; // Lấy từ khóa tìm kiếm, mặc định là chuỗi rỗng
    
        // Tạo truy vấn để đếm số lượng sản phẩm theo danh mục và từ khóa tìm kiếm
        $query_cat = Product::join('brand', 'brand.idBrand', '=', 'product.idBrand')
            ->join('category', 'category.idCategory', '=', 'product.idCategory')
            ->where('product.idCategory', $idCategory); 

        // Tìm kiếm theo tên sản phẩm, thương hiệu hoặc danh mục nếu có từ khóa
        if ($keyword) {
            $query_cat->where(function ($query_cat) use ($keyword) {
                $query_cat->where('ProductName', 'like', '%' . $keyword . '%')
                         ->orWhere('BrandName', 'like', '%' . $keyword . '%')
                         ->orWhere('CategoryName', 'like', '%' . $keyword . '%');
            });
        }
    
        // Đếm số lượng sản phẩm
        $count_cat = $query_cat->count();
    
        return $count_cat;
    }
    
    
    




    public static function count_brand_search($idBrand) {
        $keyword = $_GET['keyword'] ?? ''; 
    
        // Tạo truy vấn để đếm số lượng sản phẩm theo danh mục và từ khóa tìm kiếm
        $query_cat = Product::join('category', 'category.idCategory', '=', 'product.idCategory')
            ->join('brand', 'brand.idBrand', '=', 'product.idBrand')
            ->where('product.idBrand', $idBrand); 
        
        // Tìm kiếm theo tên sản phẩm, thương hiệu hoặc danh mục nếu có từ khóa
        if ($keyword) {
            $query_cat->where(function ($query_cat) use ($keyword) {
                $query_cat->where('ProductName', 'like', '%' . $keyword . '%')
                         ->orWhere('BrandName', 'like', '%' . $keyword . '%')
                         ->orWhere('CategoryName', 'like', '%' . $keyword . '%');
            });
        }
    
        // Đếm số lượng sản phẩm
        $count_brand = $query_cat->count();
    
        return $count_brand;
    }







   
    
}
