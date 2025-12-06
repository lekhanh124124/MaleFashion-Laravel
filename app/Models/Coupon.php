<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'COUPONS';
    protected $primaryKey = 'COUPON_ID';
    public $timestamps = false;

    protected $fillable = ['COUPON_CODE','DISCOUNT_TYPE','DISCOUNT_VALUE','EXPIRY_DATE','IS_ACTIVE'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'COUPON_ID', 'COUPON_ID');
    }
}