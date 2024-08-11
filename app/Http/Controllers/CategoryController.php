<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    public function add_category()
    {
        return view('admin.category.add_category');
    }

    public function submit_add_category(Request $request)
    {
        $data = $request->all();
        $category = new Category();

        $select_category = Category::where('CategoryName', $data['CategoryName'])->first();

        if ($select_category) {
            return Redirect::to('add-category')->with('error', 'Tên danh mục này đã tồn tại');
        } else {
            $category->CategoryName = $data['CategoryName'];
            $category->save();
            return Redirect::to('add-category')->with('message', 'Thêm danh mục thành công');
        }
    }


    public function  manage_category()
    {
        $list_category = Category::get();
        $count_category = Category::count();
        return view("admin.category.manage_category")->with(compact('list_category', 'count_category'));
    }





    public function delete_category($idCategory)
    {
        // Kiểm tra xem có sản phẩm nào đang sử dụng danh mục này không
        $hasProducts = Product::where('idCategory', $idCategory)->exists();

        if ($hasProducts) {
            // Nếu có sản phẩm, không cho phép xóa và trả về thông báo lỗi
            return redirect()->back()->withErrors('Không thể xóa danh mục vì nó đang được sử dụng trong một hoặc nhiều sản phẩm.');
        }

        // Nếu không có sản phẩm liên kết, thực hiện xóa danh mục
        Category::where('idCategory', $idCategory)->delete();

        // Trả về thông báo thành công
        return redirect()->back()->with('success', 'Danh mục đã được xóa thành công.');
    }






    public function  edit_category($idCategory)
    {
        $select_category = Category::where('idCategory', $idCategory)->first();
        return view("admin.category.edit_category")->with(compact('select_category'));
    }
    public function submit_edit_category(Request $request, $idCategory)
    {
        $data = $request->all();
        $category = Category::find($idCategory);
        //whereNotIn không so sánh với tên hiện tại của danh mục
        $select_category = Category::where('CategoryName', $data['CategoryName'])->where('idCategory', '<>', [$idCategory])->first();

        if ($select_category) {
            return redirect()->back()->with('error', 'Tên thương hiệu này đã tồn tại');
        } else {
            $category->CategoryName = $data['CategoryName'];

            $category->save();
            return redirect()->back()->with('message', 'Sửa thương hiệu thành công');
        }
    }
}
