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



    public function submit_add_brand(Request $request)
    {
        $data = $request->all();
        $brand = new Brand();

        $select_brand = Brand::where('BrandName', $data['BrandName'])->first();

        if ($select_brand) {
            return Redirect::to('add-brand')->with('error', 'Tên thương hiệu này đã tồn tại');
        } else {
            $brand->BrandName = $data['BrandName'];

            $brand->save();
            return Redirect::to('add-brand')->with('message', 'Thêm thương hiệu thành công');
        }
    }

   
   
    public function manage_brand()
    {
        $list_brand = Brand::get();
        $count_brand = Brand::count();
        return view("admin.brand.manage_brand")->with(compact('list_brand', 'count_brand'));
    }



    public function delete_brand( $idBrand)  {
        Brand::where('idBrand', $idBrand)->delete();
        return redirect()->back();
    }









    //chuyển trang edit
    public function edit_brand($idBrand) {
        $select_brand = Brand::where('idBrand', $idBrand)->first();
        return view("admin.brand.edit_brand")->with(compact('select_brand'));
        
    }


    public function submit_edit_brand(Request $request, $idBrand) {
        $data = $request->all();
        $brand = Brand::find($idBrand);
        
        $select_brand = Brand::where('BrandName', $data['BrandName'])->where('idBrand','<>',[$idBrand])->first();

        if($select_brand){
            return redirect()->back()->with('error', 'Tên thương hiệu này đã tồn tại');
        }else{
            $brand->BrandName = $data['BrandName'];
          
            $brand->save();
            return redirect()->back()->with('message', 'Sửa thương hiệu thành công');
        }
    }


}
