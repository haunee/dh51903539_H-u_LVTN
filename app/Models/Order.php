<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
class Order extends Model
{
    public $timestamp = false;
    protected $fillable = ['idCustomer','Address','Payment','Voucher','idVoucher','PhoneNumber','CustomerName','ReceiveDate','created_at','Status','TotalBill'];
    protected $primaryKey = 'idOrder';
    protected $table = 'order';

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
    }
}
