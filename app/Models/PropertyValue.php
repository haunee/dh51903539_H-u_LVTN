<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyValue extends Model
{
   public $fillable=['idProperty','ProValName'];
   protected $primaryKey='idProVal';
   protected $table='propertyvalue';
}
