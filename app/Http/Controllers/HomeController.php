<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Viewer;
use App\Models\WishList;
use Illuminate\Support\Collection;

class HomeController extends Controller
{


    public function index()
    {
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

        // $recommend_pds = collect();
        // // Kiểm tra xem người dùng đã xem sản phẩm nào
        // if (Viewer::where('idCustomer', $idCustomer)->count() > 0) {
        //     // Lấy danh sách các sản phẩm đã xem của người dùng
        //     $viewedProducts = Viewer::where('idCustomer', $idCustomer)
        //         ->orderBy('idView', 'desc')
        //         ->pluck('idProduct');

        //     // Truy xuất thông tin sản phẩm chi tiết
        //     $list_viewed_products = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
        //         ->whereIn('product.idProduct', $viewedProducts)  // Chỉ định rõ ràng bảng `product`
        //         ->select('product.*', 'productimage.ImageName')
        //         ->get();

        //     $recommend_pds = $recommend_pds->merge($list_viewed_products);
        // }

        // // Lấy các sản phẩm yêu thích của người dùng
        // if (!empty($wishlistProducts)) {
        //     $wishlist_recommend_pds = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
        //         ->whereIn('product.idProduct', $wishlistProducts)  // Chỉ định rõ ràng bảng `product`
        //         ->select('product.*', 'productimage.ImageName')
        //         ->get();

        //     $recommend_pds = $recommend_pds->merge($wishlist_recommend_pds);
        // }



        // Khai báo kiểu dữ liệu là Collection
 
        $recommend_pds = collect();

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

        // Loại bỏ các sản phẩm trùng lặp dựa trên idProduct
        $recommend_pds = $recommend_pds->unique('idProduct');

        // Nếu cần, bạn có thể sắp xếp lại sản phẩm sau khi loại bỏ trùng lặp
        $recommend_pds = $recommend_pds->values();  // Reset các chỉ số của Collection nếu cần



        return view('shop.home')->with(compact('list_category', 'newestProducts', 'list_brand', 'list_bestsellers_pd', 'recommend_pds', 'wishlistProducts'));
    }




    // public function show($idProduct) {
    //     // Lấy thông tin sản phẩm
    //     $product = Product::find($idProduct);

    //     // Lưu thông tin sản phẩm đã xem
    //     $idCustomer = session('idCustomer');
    //     if ($idCustomer) {
    //         Viewer::updateOrCreate(
    //             ['idCustomer' => $idCustomer, 'idProduct' => $idProduct],
    //             ['idView' => now()]
    //         );
    //     }

    //     // Hiển thị trang chi tiết sản phẩm
    //     return view('shop.product.product-detail', compact('product'));
    // }

}
