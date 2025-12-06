<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'WISHLIST';
    protected $primaryKey = 'WISHLIST_ID';
    public $timestamps = false;

    protected $fillable = ['USER_ID','PRODUCT_ID'];

    public function user()    { return $this->belongsTo(User::class, 'USER_ID', 'USER_ID'); }
    public function product() { return $this->belongsTo(Product::class, 'PRODUCT_ID', 'PRODUCT_ID'); }
}