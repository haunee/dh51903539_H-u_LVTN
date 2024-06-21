<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
   
    protected $fillable = [
        
        'AdminUser',
        'AdminPass',
        'NumberPhone',
        'Email',
        'AdminName',
        'Address',
        'Avatar'
    ];
    protected $primaryKey = 'idAdmin';  
    protected $table = 'admin';  

}
