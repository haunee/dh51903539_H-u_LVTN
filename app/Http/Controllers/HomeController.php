<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Carbon;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Session\Session;
class HomeController extends Controller
{
    public function index(){
        
        $newestProducts = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
        ->select('product.*', 'productimage.ImageName')
        ->orderBy('product.created_at', 'desc')
        ->take(10)
        ->get();

        return view('shop.home', compact('newestProducts'));
    }

    
}
