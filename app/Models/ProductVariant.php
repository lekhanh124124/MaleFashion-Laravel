<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'PRODUCT_VARIANTS';
    protected $primaryKey = 'VARIANT_ID';
    public $timestamps = false;

    protected $fillable = ['PRODUCT_ID','SIZE_ID','COLOR_ID','SKU','PRICE','STOCK'];

    public function product() { return $this->belongsTo(Product::class, 'PRODUCT_ID', 'PRODUCT_ID'); }
    public function size()    { return $this->belongsTo(Size::class, 'SIZE_ID', 'SIZE_ID'); }
    public function color()   { return $this->belongsTo(Color::class, 'COLOR_ID', 'COLOR_ID'); }
}