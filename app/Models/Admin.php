<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
class Admin extends Model
{
   
    protected $fillable = [
        
        'AdminUser',
        'AdminPass',
        'NumberPhone',
        'email',
        'AdminName',
        'Address',
        'code',
        'Avatar'
    ];
    protected $primaryKey = 'idAdmin';  
    protected $table = 'admin';  

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
    }

}
