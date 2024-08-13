<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PropertyValue;

use App\Models\Property;
use App\Models\PropertyPro;

//PHÂN LOẠI
class PropertyValueController extends Controller
{
    public function manage_attri_value()
    {
        $list_attr_value = propertyvalue::join('property', 'property.idProperty', '=', 'propertyvalue.idProperty')->get();
        $count_attr_value = propertyvalue::count();
        return view("admin.attribute-value.manage_attri_value")->with(compact('list_attr_value', 'count_attr_value'));
    }

    public function add_attri_value()
    {
        $list_attribute = Property::get();
        return view("admin.attribute-value.add_attri_value")->with(compact('list_attribute'));
    }

    public function submit_add_attrival(Request $request)
    {

        $data = $request->all();
        $attr_value = new propertyvalue();

        $select_attr_value = propertyvalue::where('idProperty', $data['idProperty'])
            ->where('ProValName', $data['ProValName'])->first();

        if ($select_attr_value) {
            return redirect()->back()->with('error', 'Tên phân loại này đã tồn tại');
        } else {
            $attr_value->ProValName = $data['ProValName'];
            $attr_value->idProperty = $data['idProperty'];
            $attr_value->save();
            return redirect()->back()->with('message', 'Thêm phân loại thành công');
        }
    }



    // Chuyển đến trang sửa phân loại
    public function edit_at_value($idProVal)
    {

        $list_attribute = property::get();
        $select_attr_value = propertyvalue::join('property', 'property.idProperty', '=', 'propertyvalue.idProperty')
            ->where('idProVal', $idProVal)->first();
        return view("admin.attribute-value.edit_attri_value")->with(compact('select_attr_value', 'list_attribute'));
    }


    public function submit_edit_attri_value(Request $request, $idProperty)
    {
        $data = $request->all();
        $attr_value = propertyvalue::find($idProperty);

        // Kiểm tra xem Tên phân loại có chứa số âm hay không
        if (is_numeric($data['ProValName']) && $data['ProValName'] < 0) {
            return redirect()->back()->with('error', ' Phân loại không được chứa số âm');
        }

        $select_attr_value = propertyvalue::where('ProValName', $data['ProValName'])
            ->where('idProperty', '<>', $data['idProperty'])->first(); //loại trừ id hiện tại ra

        if ($select_attr_value) {
            return redirect()->back()->with('error', 'Tên phân loại này đã tồn tại');
        } else {
            $attr_value->ProValName = $data['ProValName'];
            $attr_value->idProperty = $data['idProperty'];
            $attr_value->save();
            return redirect()->back()->with('message', 'Sửa phân loại thành công');
        }
    }


    public function delete_attr_value($idProVal)
    {
        // Kiểm tra xem thuộc tính giá trị có đang được liên kết với bất kỳ sản phẩm nào không
        $isLinked = PropertyPro::where('idProVal', $idProVal)->exists();

        if ($isLinked) {
            // Nếu có sản phẩm liên kết, trả về thông báo lỗi và redirect người dùng
            return redirect()->back()->withErrors('Không thể xóa thuộc tính giá trị vì nó đang được sử dụng trong một hoặc nhiều sản phẩm.');
        }

        // Nếu không có sản phẩm liên kết, thực hiện xóa thuộc tính giá trị
        propertyvalue::where('idProVal', $idProVal)->delete();

        // Redirect người dùng trở lại trang trước đó với thông báo thành công
        return redirect()->back()->with('success', 'Đã được xóa thành công.');
    }
}
