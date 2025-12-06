<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'PRODUCT_IMAGES';
    protected $primaryKey = 'IMAGE_ID';
    public $timestamps = false;

    protected $fillable = ['PRODUCT_ID','IMAGE_URL','IS_THUMBNAIL'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'PRODUCT_ID', 'PRODUCT_ID');
    }
}