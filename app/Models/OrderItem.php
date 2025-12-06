<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'ORDER_ITEMS';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null; // composite key

    protected $fillable = ['ORDER_ID','VARIANT_ID','QUANTITY','UNIT_PRICE'];

    public function order()   { return $this->belongsTo(Order::class, 'ORDER_ID', 'ORDER_ID'); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'VARIANT_ID', 'VARIANT_ID'); }
}