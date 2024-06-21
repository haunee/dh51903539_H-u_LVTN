<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
   public $fillable=['idAttribute','AttriValName'];
   protected $primaryKey='idAttriValue';
   protected $table='attributevalue';
}
