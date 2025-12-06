<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'ORDERS';
    protected $primaryKey = 'ORDER_ID';
    public $timestamps = false;

    protected $fillable = [
        'USER_ID','COUPON_ID','ORDER_DATE','STATUS','PAYMENT_METHOD',
        'SHIPPING_ADDRESS','BILLING_NAME','BILLING_PHONE','BILLING_EMAIL',
        'ORDER_NOTES','DISCOUNT_AMOUNT'
    ];

    public function user()    { return $this->belongsTo(User::class, 'USER_ID', 'USER_ID'); }
    public function coupon()  { return $this->belongsTo(Coupon::class, 'COUPON_ID', 'COUPON_ID'); }
    public function items()   { return $this->hasMany(OrderItem::class, 'ORDER_ID', 'ORDER_ID'); }
}