<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public $timestamp = false;
    protected $fillable = ['BrandName'];
    protected $primaryKey = 'idBrand';
    protected $table = 'brand';
}
