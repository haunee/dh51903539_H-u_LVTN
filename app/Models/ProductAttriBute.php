<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//QUẢN LÝ PHÂN LOẠI CỦA 1 SẢN PHẨM
class ProductAttriBute extends Model
{
    public $timestamp = false;
    public $incrementing = false;
    protected $fillable = ['idProduct','idAttriValue','Quantity'];
    protected $primaryKey = ['idProAttr'];
    protected $table = 'product_attribute';
}
