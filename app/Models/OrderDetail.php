<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    public $timestamp = false;
    protected $fillable = ['idOrder','idProduct','AttributeProduct','Price','QuantityBuy','idProAttr'];
    protected $table = 'orderdetail';
    public $incrementing = false;
}
