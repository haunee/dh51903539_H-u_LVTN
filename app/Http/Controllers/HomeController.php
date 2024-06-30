<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Viewer;
class HomeController extends Controller
{
    public function index(){
        $list_category = Category::get();
        $list_brand = Brand::get();
        $recommend_pds_arrays = [];
        
        $newestProducts = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->select('product.*', 'productimage.ImageName')
            ->orderBy('product.created_at', 'desc')  // Specify 'product' table
            ->take(10)
            ->get();
            
        // Assuming you still want to get some kind of bestsellers list without using 'Sold'
        // Here we just order by 'product.created_at' desc for the sake of example
        $list_bestsellers_pd = Product::join('productimage','productimage.idProduct','=','product.idProduct')
            ->orderBy('product.created_at','DESC')  // Specify 'product' table
            ->get();
            
        $idCustomer = session()->get('idCustomer', session()->getId()); // Use session() helper
    
        if(Viewer::where('idCustomer', $idCustomer)->count() == 0) {
            $recommend_pds = $list_bestsellers_pd;
        } else {
            $list_viewed = Viewer::join('product','product.idProduct','=','viewer.idProduct')
                ->where('idCustomer', $idCustomer)
                ->select('viewer.idProduct', 'idBrand', 'idCategory', 'ProductName')
                ->orderBy('idView', 'DESC')
                ->get();
    
            foreach($list_viewed as $key => $viewed){
                $idBrand = $viewed->idBrand;
                $idCategory = $viewed->idCategory;
    
                //Mảng các sản phẩm đã lặp qua
                $checked_pro[] = $viewed->idProduct;
    
                // Danh sách sản phẩm gợi ý của 1 sản phẩm trong giỏ hàng
                $list_recommend_pds = Product::whereRaw("MATCH (ProductName) AGAINST (?)", Product::fullTextWildcards($viewed->ProductName))
                    ->whereNotIn('idProduct',[$viewed->idProduct])
                    ->select('idProduct');
                $list_recommend_pds->where(function ($list_recommend_pds) use ($idBrand, $idCategory){
                    $list_recommend_pds->orWhere('idBrand', $idBrand)->orWhere('idCategory', $idCategory);
                });
                $list_recommend_pd = $list_recommend_pds->get();
    
                if($list_recommend_pd->count() > 0){
                    // Thêm từng sản phẩm gợi ý của 1 sản phẩm vào 1 mảng
                    foreach($list_recommend_pd as $recommend_pd){
                        $recommend_pds_array[$key][] = $recommend_pd->idProduct;
                    }   
    
                    // Thêm từng mảng thứ $key vào 1 mảng lớn
                    $recommend_pds_arrays[] = $recommend_pds_array[$key];
                }
            }
    
            if(count($recommend_pds_arrays) > 0){
                // Hàm gộp mảng, xen kẽ các phần tử của từng mảng
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
                $featured_pds = Product::join('productimage','productimage.idProduct','=','product.idProduct')
                    ->orderBy('product.created_at','DESC')  // Specify 'product' table
                    ->select('product.idProduct')->get();
    
                $featured_pds_array = $featured_pds->pluck('idProduct')->toArray();
    
                $recommend_pds = json_encode($featured_pds_array);
            }
        }
        return view('shop.home')->with(compact('list_category', 'newestProducts', 'list_brand', 'list_bestsellers_pd', 'recommend_pds')); 
    }
    
    
}
