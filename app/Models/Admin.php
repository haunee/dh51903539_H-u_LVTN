<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
   
    protected $fillable = [
        'AdminName',
        'AdminUser',
        'AdminPass',
        'Position',
        'Address',
        'NumberPhone',
        'Email',
        'Avatar'
    ];
    protected $primaryKey = 'idAdmin';  // Khóa chính của bảng
    protected $table = 'admin';  // Tên bảng

}
