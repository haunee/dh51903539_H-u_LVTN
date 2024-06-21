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
    'DesProduct',
    'ShortDes',
    'Price',
    'Sold',
    ];

    protected $primaryKey = 'idProduct';
    protected $table = 'product';
}
