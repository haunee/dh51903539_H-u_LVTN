<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Attribute;
use Illuminate\Support\Facades\Log;
use App\Models\AttributeValue;
use App\Models\ProductAttriBute;

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

     
        $attribute = Attribute::find($idAttribute);

      
        $select_attribute = Attribute::where('AttributeName', $data['AttributeName'])
            ->where('idAttribute', '<>', $idAttribute)->first();

        if ($select_attribute) {
           
            return redirect()->back()->with('error', 'Tên nhóm phân loại này đã tồn tại');
        } else {
            
            $attribute->AttributeName = $data['AttributeName'];
            $attribute->save();

            
            return redirect()->back()->with('message', 'Sửa nhóm phân loại thành công');
        }
    }


    public function delete_attribute($idAttribute)
    {
        // Kiểm tra xem có sản phẩm nào đang sử dụng thuộc tính này không
        $hasProducts = AttributeValue::where('idAttribute', $idAttribute)->exists();
    
        if ($hasProducts) {
            // Nếu có sản phẩm, không cho phép xóa và trả về thông báo lỗi
            return redirect()->back()->withErrors('Không thể xóa nhóm phân loại vì có phân loại được lưu vào');
        }
    
        // Nếu không có sản phẩm liên kết, thực hiện xóa thuộc tính
        Attribute::where('idAttribute', $idAttribute)->delete();
    
        // Trả về thông báo thành công
        return redirect()->back()->with('success', 'Thuộc tính đã được xóa thành công.');
    }
    
    


    // Hiện checkbox chọn phân loại sản phẩm
    public function select_attribute(Request $request)
    {
        $data = $request->all();
        $output = '';

        if ($data['action'] && $data['action'] == "attribute") {
            $list_attribute_val = AttributeValue::where('idAttribute', $data['idAttribute'])->get();
            foreach ($list_attribute_val as $key => $attribute_val) {
                $output .= '<label for="chk-attr-' . $attribute_val->idAttriValue . '" class="d-block col-lg-3 p-0 m-0"><div id="attr-name-' . $attribute_val->idAttriValue . '" class="select-attr text-center mr-2 mt-2">' . $attribute_val->AttriValName . '</div></label>
                            <input type="checkbox" class="checkstatus d-none chk_attr" id="chk-attr-' . $attribute_val->idAttriValue . '" data-id="' . $attribute_val->idAttriValue . '" data-name="' . $attribute_val->AttriValName . '" name="chk_attr[]" value="' . $attribute_val->idAttriValue . '">';
            }
        }

        //text phân loại
        return $output; 
    }
}
