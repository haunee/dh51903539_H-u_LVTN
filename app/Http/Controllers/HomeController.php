<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Viewer;
use App\Models\WishList;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{


    public function index()
    {
        $list_category = Category::all();
        $list_brand = Brand::all();

        $idCustomer = session('idCustomer');

      
        $wishlistProducts = WishList::where('idCustomer', $idCustomer)->pluck('idProduct')->toArray();

        // Lấy các sản phẩm mới nhất
        $newestProducts = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->select('product.*', 'productimage.ImageName')
            ->orderBy('product.created_at', 'desc')
            ->take(6)
            ->get();


   

        // Lấy các sản phẩm đã xem của người dùng
        $recentlyViewedProducts = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
        ->whereIn('product.idProduct', Viewer::where('idCustomer', $idCustomer)->pluck('idProduct'))
        ->distinct('product.idProduct')
        ->select('product.*', 'productimage.ImageName')
        ->get();
        Log::info($recentlyViewedProducts->toArray());
        return view('shop.home')->with(compact('list_category','newestProducts','recentlyViewedProducts' ,'list_brand', 'wishlistProducts'));
    }

  
   
    public function show($idProduct)
    {
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
