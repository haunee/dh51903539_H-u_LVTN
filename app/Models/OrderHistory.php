<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    public $timestamp = false;
    protected $fillable = ['idOrder','AdminName','Status','created_at'];
    protected $primaryKey = ['idOrder'];
    protected $table = 'orderhistory';
    public $incrementing = false;
}
