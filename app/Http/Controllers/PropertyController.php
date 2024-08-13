<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\PropertyValue;
use App\Models\Property;

//NHÓM PHÂN LOẠI
class PropertyController extends Controller
{
    public function manage_attribute()
    {
        $list_attribute = Property::get();
        $count_attribute = Property::count();
        return view("admin.attribute.manage_attribute")->with(compact('list_attribute', 'count_attribute'));
    }




    public function add_attribute()
    {
        return view('admin.attribute.add_attribute');
    }

    public function submit_add_attribute(Request $request)
    {
        $validatedData = $request->validate([
            'PropertyName' => 'required|unique:property,PropertyName',
        ], [
            'PropertyName.required' => 'Tên nhóm phân loại là bắt buộc',
            'PropertyName.max' => 'Tên nhóm phân loại không được vượt quá 255 ký tự',
            'PropertyName.unique' => 'Tên nhóm phân loại này đã tồn tại',
        ]);

        $attribute = new Property();
        $attribute->PropertyName = $validatedData['PropertyName'];
        $attribute->save();

        return redirect()->back()->with('message', 'Thêm nhóm phân loại thành công');
    }





    public function edit_attribute($idProperty)
    {
        $select_attribute = Property::where('idProperty', $idProperty)->first();
        return view('admin.attribute.edit_attribute')->with(compact('select_attribute'));
    }


    public function submit_edit_attribute(Request $request, $idProperty)
    {
        $data = $request->all();

     
        $attribute = Property::find($idProperty);

      
        $select_attribute = Property::where('PropertyName', $data['PropertyName'])
            ->where('idProperty', '<>', $idProperty)->first();

        if ($select_attribute) {
           
            return redirect()->back()->with('error', 'Tên nhóm phân loại này đã tồn tại');
        } else {
            
            $attribute->PropertyName = $data['PropertyName'];
            $attribute->save();

            
            return redirect()->back()->with('message', 'Sửa nhóm phân loại thành công');
        }
    }


    public function delete_attribute($idProperty)
    {
        // Kiểm tra xem có sản phẩm nào đang sử dụng thuộc tính này không
        $hasProducts = propertyvalue::where('idProperty', $idProperty)->exists();
    
        if ($hasProducts) {
            // Nếu có sản phẩm, không cho phép xóa và trả về thông báo lỗi
            return redirect()->back()->withErrors('Không thể xóa nhóm phân loại vì có phân loại được lưu vào');
        }
    
        // Nếu không có sản phẩm liên kết, thực hiện xóa thuộc tính
        Property::where('idProperty', $idProperty)->delete();
    
        // Trả về thông báo thành công
        return redirect()->back()->with('success', 'Thuộc tính đã được xóa thành công.');
    }
    
    


    // Hiện checkbox chọn phân loại sản phẩm
    public function select_attribute(Request $request)
    {
        $data = $request->all();
        $output = '';

        if ($data['action'] && $data['action'] == "attribute") {
            $list_attribute_val = propertyvalue::where('idProperty', $data['idProperty'])->get();
            foreach ($list_attribute_val as $key => $attribute_val) {
                $output .= '<label for="chk-attr-' . $attribute_val->idProVal . '" class="d-block col-lg-3 p-0 m-0"><div id="attr-name-' . $attribute_val->idProVal . '" class="select-attr text-center mr-2 mt-2">' . $attribute_val->PropertyName . '</div></label>
                            <input type="checkbox" class="checkstatus d-none chk_attr" id="chk-attr-' . $attribute_val->idProVal . '" data-id="' . $attribute_val->idProVal . '" data-name="' . $attribute_val->PropertyName . '" name="chk_attr[]" value="' . $attribute_val->idProVal . '">';
            }
        }

        //text phân loại
        return $output; 
    }
}
