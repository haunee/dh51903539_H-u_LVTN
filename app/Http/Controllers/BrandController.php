<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    // Chuyển đến trang thêm thương hiệu
    public function add_brand()
    {
      
        return view("admin.brand.add_brand");
    }

 
}
