<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'CART_ITEMS';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null; // composite key

    protected $fillable = ['CART_ID','VARIANT_ID','QUANTITY','UNIT_PRICE'];

    public function cart()    { return $this->belongsTo(Cart::class, 'CART_ID', 'CART_ID'); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'VARIANT_ID', 'VARIANT_ID'); }
}