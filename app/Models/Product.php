<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   protected $fillable= [
    'idCategory',
    'idBrand',
    'QuantityTotal',
    'ProductName',
    'Price',
    'DesProduct',
    'ShortDes',

    ];

    protected $primaryKey = 'idProduct';
    protected $table = 'product';

    public function billinfo()
    {
        return $this->hasMany(BillInfo::class, 'idProduct', 'idProduct');
    }
}
