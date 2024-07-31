<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttributeValue;

use App\Models\Attribute;


//PHÂN LOẠI
class AttributeValueController extends Controller
{
    public function manage_attri_value()
    {
        $list_attr_value = AttributeValue::join('attribute','attribute.idAttribute','=','attributevalue.idAttribute')->get();
        $count_attr_value = AttributeValue::count();
        return view("admin.attribute-value.manage_attri_value")->with(compact('list_attr_value', 'count_attr_value'));
    }

    public function add_attri_value()
    {
        $list_attribute = Attribute::get();
        return view("admin.attribute-value.add_attri_value")->with(compact('list_attribute'));
    }

    public function submit_add_attrival(Request $request)
    {

        $data = $request->all();
        $attr_value = new AttributeValue();

        $select_attr_value = AttributeValue::where('idAttribute', $data['idAttribute'])
            ->where('AttriValName', $data['AttriValName'])->first();

        if ($select_attr_value) {
            return redirect()->back()->with('error', 'Tên phân loại này đã tồn tại');
        } else {
            $attr_value->AttriValName = $data['AttriValName'];
            $attr_value->idAttribute = $data['idAttribute'];
            $attr_value->save();
            return redirect()->back()->with('message', 'Thêm phân loại thành công');
        }
    }

    

    // Chuyển đến trang sửa phân loại
    public function edit_at_value($idAttriValue)
    {

        $list_attribute = Attribute::get();
        $select_attr_value = AttributeValue::join('attribute', 'attribute.idAttribute', '=', 'attributevalue.idAttribute')
            ->where('idAttriValue', $idAttriValue)->first();
        return view("admin.attribute-value.edit_attri_value")->with(compact('select_attr_value', 'list_attribute'));
    }


    public function submit_edit_attri_value(Request $request, $idAttribute){
        $data = $request->all();
        $attr_value = AttributeValue::find($idAttribute);
        
         // Kiểm tra xem Tên phân loại có chứa số âm hay không
        if (is_numeric($data['AttriValName']) && $data['AttriValName'] < 0) {
            return redirect()->back()->with('error', ' Phân loại không được chứa số âm');
        }

        $select_attr_value = AttributeValue::where('AttriValName', $data['AttriValName'])
        ->where('idAttribute','<>' ,$data['idAttribute'])->first();//loại trừ id hiện tại ra

        if($select_attr_value){
            return redirect()->back()->with('error', 'Tên phân loại này đã tồn tại');
        }else{
            $attr_value->AttriValName = $data['AttriValName'];
            $attr_value->idAttribute = $data['idAttribute'];
            $attr_value->save();
            return redirect()->back()->with('message', 'Sửa phân loại thành công');
        }
    }

    public function delete_attr_value($idAttriValue){
        AttributeValue::where('idAttriValue', $idAttriValue)->delete();
        return redirect()->back();
    }
}
