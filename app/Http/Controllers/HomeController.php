<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Viewer;
use App\Models\WishList;

class HomeController extends Controller
{
    // public function index() {
    //     $list_category = Category::get();
    //     $list_brand = Brand::get();

    //     $recommend_pds_arrays = [];
    //     $idCustomer = session('idCustomer');
    //     // Lấy danh sách các sản phẩm yêu thích của người dùng
    //     $wishlistProducts = WishList::where('idCustomer', $idCustomer)->pluck('idProduct')->toArray();


    //     $newestProducts = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
    //         ->select('product.*', 'productimage.ImageName')
    //         ->orderBy('product.created_at', 'desc')
    //         ->take(6)
    //         ->get();
    
    //     $list_bestsellers_pd = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
    //         ->select('product.*', 'productimage.ImageName')
    //         ->orderBy('product.created_at', 'desc')
    //         ->get();
    
    //     $idCustomer = session()->get('idCustomer', session()->getId());
    
    //     if (Viewer::where('idCustomer', $idCustomer)->count() == 0) {
    //         $recommend_pds = $list_bestsellers_pd;
    //     } else {
    //         $list_viewed = Viewer::join('product', 'product.idProduct', '=', 'viewer.idProduct')
    //             ->where('idCustomer', $idCustomer)
    //             ->select('viewer.idProduct', 'idBrand', 'idCategory', 'ProductName')
    //             ->orderBy('idView', 'desc')
    //             ->get();
    
    //         $checked_pro = [];
    
    //         foreach ($list_viewed as $key => $viewed) {
    //             $idBrand = $viewed->idBrand;
    //             $idCategory = $viewed->idCategory;
    
    //             $checked_pro[] = $viewed->idProduct;
    
    //             $list_recommend_pds = Product::where('ProductName', 'LIKE', '%' . $viewed->ProductName . '%')
    //                 ->whereNotIn('idProduct', [$viewed->idProduct])
    //                 ->select('idProduct');
    //             $list_recommend_pds->where(function ($list_recommend_pds) use ($idBrand, $idCategory) {
    //                 $list_recommend_pds->orWhere('idBrand', $idBrand)->orWhere('idCategory', $idCategory);
    //             });
    //             $list_recommend_pd = $list_recommend_pds->get();
    
    //             if ($list_recommend_pd->count() > 0) {
    //                 foreach ($list_recommend_pd as $recommend_pd) {
    //                     $recommend_pds_array[$key][] = $recommend_pd->idProduct;
    //                 }
    
    //                 $recommend_pds_arrays[] = $recommend_pds_array[$key];
    //             }
    //         }
    
    //         if (count($recommend_pds_arrays) > 0) {
    //             for ($args = $recommend_pds_arrays; count($args); $args = array_filter($args)) {
    //                 foreach ($args as &$arg) {
    //                     $output[] = array_shift($arg);
    //                 }
    //             }
    
    //             $recommend_pds_last = array_diff($output, $checked_pro);
    //             $recommend_pds_unique = array_unique($recommend_pds_last);
    
    //             $recommend_pds = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
    //                 ->whereIn('product.idProduct', $recommend_pds_unique)
    //                 ->select('product.*', 'productimage.ImageName')
    //                 ->get();
    //         } else {
    //             $featured_pds = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
    //                 ->orderBy('product.created_at', 'desc')
    //                 ->select('product.idProduct')
    //                 ->get();
    
    //             $recommend_pds = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
    //                 ->whereIn('product.idProduct', $featured_pds->pluck('idProduct'))
    //                 ->select('product.*', 'productimage.ImageName')
    //                 ->get();
    //         }
    //     }
    
    //     return view('shop.home')->with(compact('list_category', 'newestProducts', 'list_brand', 'list_bestsellers_pd', 'recommend_pds','wishlistProducts'));
    // }
    
    public function index() {
        $list_category = Category::all();
        $list_brand = Brand::all();
        $idCustomer = session('idCustomer');
        
        // Lấy danh sách các sản phẩm yêu thích của người dùng
        $wishlistProducts = WishList::where('idCustomer', $idCustomer)->pluck('idProduct')->toArray();
    
        // Lấy các sản phẩm mới nhất
        $newestProducts = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->select('product.*', 'productimage.ImageName')
            ->orderBy('product.created_at', 'desc')
            ->take(6)
            ->get();
    
        // Lấy các sản phẩm bán chạy nhất
        $list_bestsellers_pd = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->select('product.*', 'productimage.ImageName')
            ->orderBy('product.created_at', 'desc')
            ->get();
    
        $recommend_pds = collect();
    
        // Kiểm tra xem người dùng đã xem sản phẩm nào
        if (Viewer::where('idCustomer', $idCustomer)->count() > 0) {
            // Lấy danh sách các sản phẩm đã xem của người dùng
            $viewedProducts = Viewer::where('idCustomer', $idCustomer)
                ->orderBy('idView', 'desc')
                ->pluck('idProduct');
    
            // Truy xuất thông tin sản phẩm chi tiết
            $list_viewed_products = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
                ->whereIn('product.idProduct', $viewedProducts)  // Chỉ định rõ ràng bảng `product`
                ->select('product.*', 'productimage.ImageName')
                ->get();
            
            $recommend_pds = $recommend_pds->merge($list_viewed_products);
        }
    
        // Lấy các sản phẩm yêu thích của người dùng
        if (!empty($wishlistProducts)) {
            $wishlist_recommend_pds = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
                ->whereIn('product.idProduct', $wishlistProducts)  // Chỉ định rõ ràng bảng `product`
                ->select('product.*', 'productimage.ImageName')
                ->get();
    
            $recommend_pds = $recommend_pds->merge($wishlist_recommend_pds);
        }
    
        // Loại bỏ các sản phẩm trùng lặp
        $recommend_pds = $recommend_pds->unique('idProduct');
    
        return view('shop.home')->with(compact('list_category', 'newestProducts', 'list_brand', 'list_bestsellers_pd', 'recommend_pds', 'wishlistProducts'));
    }
    
    
    
    
    public function show($idProduct) {
        // Lấy thông tin sản phẩm
        $product = Product::find($idProduct);
    
        // Lưu thông tin sản phẩm đã xem
        $idCustomer = session('idCustomer');
        if ($idCustomer) {
            Viewer::updateOrCreate(
                ['idCustomer' => $idCustomer, 'idProduct' => $idProduct],
                ['idView' => now()]
            );
        }
    
        // Hiển thị trang chi tiết sản phẩm
        return view('shop.product.product-detail', compact('product'));
    }
    
}
