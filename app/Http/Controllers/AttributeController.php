<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Attribute;

//NHÓM PHÂN LOẠI
class AttributeController extends Controller
{
    public function manage_attribute()
    {
        $list_attribute = Attribute::get();
        $count_attribute = Attribute::count();
        return view("admin.attribute.manage_attribute")->with(compact('list_attribute', 'count_attribute'));
    }




    public function add_attribute()
    {
        return view('admin.attribute.add_attribute');
    }

    public function submit_add_attribute(Request $request)
    {
        $validatedData = $request->validate([
            'AttributeName' => 'required|unique:attribute,AttributeName',
        ], [
            'AttributeName.required' => 'Tên nhóm phân loại là bắt buộc',
            'AttributeName.max' => 'Tên nhóm phân loại không được vượt quá 255 ký tự',
            'AttributeName.unique' => 'Tên nhóm phân loại này đã tồn tại',
        ]);

        $attribute = new Attribute();
        $attribute->AttributeName = $validatedData['AttributeName'];
        $attribute->save();

        return redirect()->back()->with('message', 'Thêm nhóm phân loại thành công');
    }





    public function edit_attribute($idAttribute)
    {
        $select_attribute = Attribute::where('idAttribute', $idAttribute)->first();
        return view('admin.attribute.edit_attribute')->with(compact('select_attribute'));
    }


    public function submit_edit_attribute(Request $request, $idAttribute)
    {
        $data = $request->all();

        // Tìm nhóm phân loại cần sửa
        $attribute = Attribute::find($idAttribute);

        // Kiểm tra xem tên nhóm phân loại đã tồn tại trong các nhóm khác chưa
        $select_attribute = Attribute::where('AttributeName', $data['AttributeName'])
            ->where('idAttribute', '<>', $idAttribute)->first();

        if ($select_attribute) {
            // Nếu tên nhóm phân loại đã tồn tại, redirect về trang trước với thông báo lỗi
            return redirect()->back()->with('error', 'Tên nhóm phân loại này đã tồn tại');
        } else {
            // Nếu tên nhóm phân loại chưa tồn tại, cập nhật và lưu vào cơ sở dữ liệu
            $attribute->AttributeName = $data['AttributeName'];
            $attribute->save();

            // Redirect về trang trước với thông báo thành công
            return redirect()->back()->with('message', 'Sửa nhóm phân loại thành công');
        }
    }



    public function delete_attribute($idAttribute)
    {
        Attribute::where('idAttribute', $idAttribute)->delete();
        return redirect()->back();
    }
}
