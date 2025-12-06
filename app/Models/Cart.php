<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'CART';
    protected $primaryKey = 'CART_ID';
    public $timestamps = false;

    protected $fillable = ['USER_ID','CREATED_AT'];

    public function user()  { return $this->belongsTo(User::class, 'USER_ID', 'USER_ID'); }
    public function items() { return $this->hasMany(CartItem::class, 'CART_ID', 'CART_ID'); }
}