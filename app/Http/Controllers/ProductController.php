<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductAttriBute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\Viewer;
use App\Models\WishList;
use Illuminate\Support\Facades\Redirect;


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
    public function submit_add_product(Request $request)
    {
        $data = $request->all();

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
        $get_old_mg = ProductImage::where('idProduct', $idProduct)->first();
        foreach (json_decode($get_old_mg->ImageName) as $old_img) {
            Storage::delete('public/kidadmin/images/product/' . $old_img);
        }
        Product::find($idProduct)->delete();
        return redirect()->back();
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

        $idCustomer = session('idCustomer');
        // Lấy danh sách các sản phẩm yêu thích của người dùng
        $wishlistProducts = WishList::where('idCustomer', $idCustomer)->pluck('idProduct')->toArray();

        $sub30days = Carbon::now()->subDays(30)->toDateString();
        $list_category = Category::get();
        $list_brand = Brand::get();

        $list_pd_query = Product::join('brand', 'brand.idBrand', '=', 'product.idBrand')
            ->join('category', 'category.idCategory', '=', 'product.idCategory')
            ->join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->select('ImageName', 'product.*', 'BrandName', 'CategoryName');

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
            else if ($_GET['sort_by'] == 'old') $list_pd_query->orderBy('created_at', 'asc');
            else if ($_GET['sort_by'] == 'featured') $list_pd_query->whereBetween('product.created_at', [$sub30days, now()]);
            else if ($_GET['sort_by'] == 'price_desc') $list_pd_query->orderBy('Price', 'desc');
            else if ($_GET['sort_by'] == 'price_asc') $list_pd_query->orderBy('Price', 'asc');
        } else $list_pd_query->orderBy('created_at', 'desc');

        $count_pd = $list_pd_query->count();
        $list_pd = $list_pd_query->paginate(15);

        return view("shop.product.shop-all-product")->with(compact('list_category', 'list_brand', 'list_pd', 'count_pd', 'wishlistProducts'));
    }





    public function show_product_details($idProduct)
    {

        $idCustomer = session('idCustomer');
        // Lấy danh sách các sản phẩm yêu thích của người dùng
        $wishlistProducts = WishList::where('idCustomer', $idCustomer)->pluck('idProduct')->toArray();

        $list_category = Category::get();
        $list_brand = Brand::get();

        $this_pro = Product::where('idProduct', $idProduct)->first();

        $viewer = new Viewer();

        if (Session::get('idCustomer') == '') $idCustomer = session()->getId();
        else $idCustomer = (string)Session::get('idCustomer');

        $viewer->idCustomer = $idCustomer;
        $viewer->idProduct = $this_pro->idProduct;

        if (Viewer::where('idCustomer', $idCustomer)->where('idProduct', $this_pro->idProduct)->count() == 0) {
            if (Viewer::where('idCustomer', $idCustomer)->count() >= 3) {
                $idView = Viewer::where('idCustomer', $idCustomer)->orderBy('idView', 'asc')->take(1)->delete();
                $viewer->save();
            } else $viewer->save();
        }

        $idBrand = $this_pro->idBrand;
        $idCategory = $this_pro->idCategory;


        $list_pd_attr = ProductAttriBute::join('attributevalue', 'attributevalue.idAttriValue', '=', 'product_attribute.idAttriValue')
            ->join('attribute', 'attribute.idAttribute', '=', 'attributevalue.idAttribute')
            ->where('product_attribute.idProduct', $this_pro->idProduct)->get();

        $name_attribute = ProductAttriBute::join('attributevalue', 'attributevalue.idAttriValue', '=', 'product_attribute.idAttriValue')
            ->join('attribute', 'attribute.idAttribute', '=', 'attributevalue.idAttribute')
            ->where('product_attribute.idProduct', $this_pro->idProduct)->first();

        $product = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')->where('product.idProduct', $this_pro->idProduct)->first();

        // $list_related_products = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
        //     ->where('product.idBrand', $idBrand)
        //     ->orWhere('product.idCategory', $idCategory)
        //     ->whereNotIn('product.idProduct', [$this_pro->idProduct])
        //     ->select('ImageName', 'product.*')
        //     ->get();

        return view("shop.product.shop-single")->with(compact('list_category', 'list_brand', 'product', 'list_pd_attr', 'name_attribute', 'wishlistProducts'));
    }








    // Tìm kiếm sản phẩm
    public function search()
    {
        $idCustomer = session('idCustomer');
        // Lấy danh sách các sản phẩm yêu thích của người dùng
        $wishlistProducts = WishList::where('idCustomer', $idCustomer)->pluck('idProduct')->toArray();
    
        $keyword = $_GET['keyword'] ?? ''; // Sử dụng toán tử null coalescing để đảm bảo có giá trị mặc định
       // $sub30days = Carbon::now()->subDays(30)->toDateString();
    
        $list_category = Category::get();
        $list_brand = Brand::get();
    
        // Tạo một đối tượng query cho tìm kiếm sản phẩm
        $list_pd_query = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->join('brand', 'brand.idBrand', '=', 'product.idBrand')
            ->join('category', 'category.idCategory', '=', 'product.idCategory')
            ->where('ProductName', 'like', '%' . $keyword . '%')
            ->select('product.idProduct', 'product.ProductName', 'product.Price', 'product.created_at', 'productimage.ImageName', 'brand.BrandName', 'category.CategoryName');
    
        // Bộ lọc theo thương hiệu và danh mục
        if (isset($_GET['brand'])) $brand_arr = explode(",", $_GET['brand']);
        if (isset($_GET['category'])) $category_arr = explode(",", $_GET['category']);
    
        if (isset($_GET['category']) && isset($_GET['brand'])) {
            $list_pd_query->whereIn('product.idCategory', $category_arr)
                ->whereIn('product.idBrand', $brand_arr);
        } elseif (isset($_GET['brand'])) {
            $list_pd_query->whereIn('product.idBrand', $brand_arr);
        } elseif (isset($_GET['category'])) {
            $list_pd_query->whereIn('product.idCategory', $category_arr);
        }
    
        // Bộ lọc theo giá
        if (isset($_GET['priceMin']) && isset($_GET['priceMax'])) {
            $list_pd_query->whereBetween('Price', [$_GET['priceMin'], $_GET['priceMax']]);
        } elseif (isset($_GET['priceMin'])) {
            $list_pd_query->where('Price', '>=', $_GET['priceMin']);
        } elseif (isset($_GET['priceMax'])) {
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
                default:
                    break;
            }
        }
    
        // Phân trang
        $list_pd = $list_pd_query->paginate(15);
    
        // Kiểm tra số lượng sản phẩm tìm thấy
        $count_pd = $list_pd->total(); // Sử dụng total() để lấy số lượng tổng sản phẩm
    
        // Nếu không tìm thấy sản phẩm nào
        if ($count_pd < 1) {
            // Hiển thị thông báo không tìm thấy sản phẩm
            session()->flash('message', 'Không tìm thấy sản phẩm nào.');
        }
    
        $top_bestsellers_pd = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->orderBy('product.created_at', 'DESC')
            ->limit(3)
            ->get();
    
        return view("shop.search")->with(compact('list_category', 'list_brand', 'list_pd', 'count_pd', 'keyword', 'top_bestsellers_pd', 'wishlistProducts'));
    }
    
    

    








    // Gợi ý Tìm kiếm 
    public function search_suggestions(Request $request)
    {
        $value = $request->value;
        $output = '';

        // Tìm kiếm danh mục
        $get_cat = Category::select('CategoryName')
            ->where('CategoryName', 'like', '%' . $value . '%')
            ->limit(3)
            ->get();

        // Tìm kiếm thương hiệu
        $get_brand = Brand::select('BrandName')
            ->where('BrandName', 'like', '%' . $value . '%')
            ->limit(3)
            ->get();

        // Tìm kiếm sản phẩm
        $pds = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->join('brand', 'brand.idBrand', '=', 'product.idBrand')
            ->join('category', 'category.idCategory', '=', 'product.idCategory')
            ->where('ProductName', 'like', '%' . $value . '%') // Tìm kiếm cơ bản với LIKE
            ->select('product.idProduct', 'product.ProductName', 'productimage.ImageName')
            ->limit(3) // Đảm bảo giới hạn số lượng kết quả trả về
            ->get();

        // Tạo HTML cho danh mục
        if ($get_cat->count() > 0) {
            $output .= '<h5 class="p-1">Danh mục</h5>';
            foreach ($get_cat as $cat) {
                $output .= '
                    <li class="search-product-item">
                        <a class="search-product-text one-line" href="/search?keyword=' . urlencode($cat->CategoryName) . '">' . $cat->CategoryName . '</a>
                    </li>';
            }
        }

        // Tạo HTML cho thương hiệu
        if ($get_brand->count() > 0) {
            $output .= '<h5 class="p-1">Thương hiệu</h5>';
            foreach ($get_brand as $brand) {
                $output .= '
                    <li class="search-product-item">
                        <a class="search-product-text one-line" href="/search?keyword=' . urlencode($brand->BrandName) . '">' . $brand->BrandName . '</a>
                    </li>';
            }
        }

        // Tạo HTML cho sản phẩm
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

        // Trả về kết quả HTML
        echo $output;
    }




    public static function count_cat_search($idCategory)
    {
        $keyword = $_GET['keyword'];

        $query_cat = Product::join('brand', 'brand.idBrand', '=', 'product.idBrand')
            ->join('category', 'category.idCategory', '=', 'product.idCategory')
            ->where('product.idCategory', $idCategory)
            ->where('ProductName', 'like', '%' . $keyword . '%')
            ->select('idProduct');

        return $query_cat->count();
    }

    // Đếm số sản phẩm theo thương hiệu thuộc từ khóa tìm kiếm
    public static function count_brand_search($idBrand)
    {
        $keyword = $_GET['keyword'];

        $query_brand = Product::join('brand', 'brand.idBrand', '=', 'product.idBrand')
            ->join('category', 'category.idCategory', '=', 'product.idCategory')
            ->where('product.idBrand', $idBrand)
            ->where('ProductName', 'like', '%' . $keyword . '%')
            ->select('idProduct');

        return $query_brand->count();
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
}
