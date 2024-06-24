<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['idProduct','ImageName'];
    protected $primaryKey = 'idImage';
    protected $table = 'productimage';
}
