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
class ProductController extends Controller
{
    //ADMIN



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
                $product->ShortDes = $data['ShortDes'];
                $product->DesProduct = $data['DesProduct'];
         

                // Sửa phân loại Product_Attribute
                if($request->qty_attr){
                    ProductAttriBute::where('idProduct',$idProduct)->delete();
                    foreach($data['qty_attr'] as $key => $qty_attr)
                    {
                        $data_all = array(
                            'idProduct' => $idProduct,
                            'idAttriValue' => $data['chk_attr'][$key],
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




    //SHOP
    public function show_all_product(Request $request)
    {
        $list_category = Category::all();
        $list_brand = Brand::all();

        $query = Product::query();
        $query->join('brand', 'brand.idBrand', '=', 'product.idBrand')
            ->join('category', 'category.idCategory', '=', 'product.idCategory')
            ->join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->select('product.*', 'BrandName', 'CategoryName', 'ImageName');

        $query->orderBy('created_at', 'desc');

        $count_pd = $query->count();
        $list_pd = $query->paginate(15);

        return view("shop.product.shop-all-product")->with(compact('list_category', 'list_brand', 'list_pd', 'count_pd'));
    }




    






































    // Tìm kiếm sản phẩm
    public function search()
    {
        $keyword = $_GET['keyword'];
        $sub30days = Carbon::now()->subDays(30)->toDateString();

        $list_category = Category::get();
        $list_brand = Brand::get();

        $list_pd_query = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
            ->join('brand', 'brand.idBrand', '=', 'product.idBrand')
            ->join('category', 'category.idCategory', '=', 'product.idCategory')
            ->where('StatusPro', '1')
            ->whereRaw("MATCH (ProductName) AGAINST (?)", Product::fullTextWildcards($keyword))
            ->select('ImageName', 'product.*');

        if ($list_pd_query->count() < 1) {
            $list_pd_query = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')
                ->join('brand', 'brand.idBrand', '=', 'product.idBrand')
                ->join('category', 'category.idCategory', '=', 'product.idCategory')
                ->where('StatusPro', '1')
                ->select('ImageName', 'product.*', 'BrandName', 'CategoryName');
            $list_pd_query->where(function ($list_pd_query) use ($keyword) {
                $list_pd_query->orWhere('BrandName', 'like', '%' . $keyword . '%')->orWhere('CategoryName', 'like', '%' . $keyword . '%');
            });
        }

        //whereRaw("MATCH (ProductName) AGAINST (?)", Product::fullTextWildcards($keyword)) //
        // $list_pd_query = Product::whereRaw("MATCH (ProductName) AGAINST (? IN BOOLEAN MODE)", Product::fullTextWildcards($keyword));

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
            else if ($_GET['sort_by'] == 'bestsellers') $list_pd_query->orderBy('Sold', 'desc');
            else if ($_GET['sort_by'] == 'featured') $list_pd_query->whereBetween('product.created_at', [$sub30days, now()])->orderBy('Sold', 'desc');
            else if ($_GET['sort_by'] == 'sale') $list_pd_query->join('saleproduct', 'saleproduct.idProduct', '=', 'product.idProduct')->whereRaw('SaleStart < NOW()')->whereRaw('SaleEnd > NOW()')->orderBy('created_at', 'desc');
            else if ($_GET['sort_by'] == 'price_desc') $list_pd_query->orderBy('Price', 'desc');
            else if ($_GET['sort_by'] == 'price_asc') $list_pd_query->orderBy('Price', 'asc');
        }

        $count_pd = $list_pd_query->count();

        $list_pd = $list_pd_query->paginate(15);

        $top_bestsellers_pd = Product::join('productimage', 'productimage.idProduct', '=', 'product.idProduct')->orderBy('Sold', 'DESC')->limit(3)->get();

        return view("shop.search")->with(compact('list_category', 'list_brand', 'list_pd', 'count_pd', 'keyword', 'top_bestsellers_pd'));
    }
}
