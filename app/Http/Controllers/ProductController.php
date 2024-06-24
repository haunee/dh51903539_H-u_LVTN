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

class ProductController extends Controller
{

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
public function edit_product($idProduct){
    $product = Product::join('brand','brand.idBrand','=','product.idBrand')
        ->join('category','category.idCategory','=','product.idCategory')
        ->join('productimage','productimage.idProduct','=','product.idProduct')
        ->where('product.idProduct',$idProduct)->first();

    $list_pd_attr = ProductAttriBute::join('attributevalue','attributevalue.idAttriValue','=','product_attribute.idAttriValue')
        ->join('attribute','attribute.idAttribute','=','attributevalue.idAttribute')
        ->where('product_attribute.idProduct', $idProduct)->get();

    $name_attribute = ProductAttriBute::join('attributevalue','attributevalue.idAttriValue','=','product_attribute.idAttriValue')
        ->join('attribute','attribute.idAttribute','=','attributevalue.idAttribute')
        ->where('product_attribute.idProduct', $idProduct)->first();
    
    $list_attribute = Attribute::get();
    $list_category = Category::get();
    $list_brand = Brand::get();

    return view("admin.product.edit-product")->with(compact('product','list_category','list_brand','list_attribute','list_pd_attr','name_attribute'));
}







     // Sửa sản phẩm
     public function submit_edit_product(Request $request, $idProduct){
        $data = $request->all();
        $product = Product::find($idProduct);

        $select_product = Product::where('ProductName', $data['ProductName'])->whereNotIn('idProduct',[$idProduct])->first();

        if($select_product){
            return redirect()->back()->with('error', 'Sản phẩm này đã tồn tại');
        }else{
            $product_image = new ProductImage();
            $product->ProductName = $data['ProductName'];
            $product->idCategory = $data['idCategory'];
            $product->idBrand = $data['idBrand'];
            $product->Price = $data['Price'];
            $product->QuantityTotal = $data['QuantityTotal'];

            // Sửa phân loại Product_Attribute
            if($request->qty_attr){
                ProductAttriBute::where('idProduct',$idProduct)->delete();
                foreach($data['qty_attr'] as $key => $qty_attr)
                {
                    $data_all = array(
                        'idProduct' => $idProduct,
                        'idAttrValue' => $data['chk_attr'][$key],
                        'Quantity' => $qty_attr,
                        'created_at' => now(),
                        'updated_at' => now()
                    );
                    ProductAttriBute::insert($data_all);
                }
            }else{
                ProductAttriBute::where('idProduct',$idProduct)->delete();
                $data_all = array(
                    'idProduct' => $idProduct,
                    'Quantity' => $data['QuantityTotal'],
                    'created_at' => now(),
                    'updated_at' => now()
                );
                ProductAttriBute::insert($data_all);
            }
            
            // Thêm hình ảnh vào table ProductImage
            if($request->file('ImageName')){
                $get_image = $request->file('ImageName');

                foreach($get_image as $image){
                    $get_name_image = $image->getClientOriginalName();
                    $name_image = current(explode('.',$get_name_image));
                    $new_image = $name_image.rand(0,99).'.'.$image->getClientOriginalExtension();
                    $image->storeAs('public/kidadmin/images/product',$new_image);
                    $images[] = $new_image;
                }

                // Xoá hình cũ trong csdl và trong folder 
                $get_old_mg = ProductImage::where('idProduct', $idProduct)->first();
                foreach(json_decode($get_old_mg->ImageName) as $old_img){
                    Storage::delete('public/kidadmin/images/product/'.$old_img);
                }
                ProductImage::where('idProduct', $idProduct)->delete();
                
                $product_image->ImageName=json_encode($images);
                $product_image->idProduct = $idProduct;
                $product_image->save();
            }
            $product->save();
            return redirect()->back()->with('message', 'Sửa sản phẩm thành công');
        }
    }
    
}
