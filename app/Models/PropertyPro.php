<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//QUẢN LÝ PHÂN LOẠI CỦA 1 SẢN PHẨM
class PropertyPro extends Model
{
    public $timestamp = false;
    public $incrementing = false;
    protected $fillable = ['idProduct','idProVal','Quantity'];
    protected $primaryKey = ['idProperPro'];
    protected $table = 'property_product';
}
