<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'PRODUCTS';
    protected $primaryKey = 'PRODUCT_ID';
    public $timestamps = false;

    protected $fillable = [
        'PRODUCT_NAME','CATEGORY_ID','BRAND_ID',
        'SHORT_DESCRIPTION','DESCRIPTION','RATING',
        'IS_NEW_ARRIVAL','IS_HOT_SALE','IS_BEST_SELLER','CREATED_AT'
    ];

    public function category() { return $this->belongsTo(Category::class, 'CATEGORY_ID', 'CATEGORY_ID'); }
    public function brand()    { return $this->belongsTo(Brand::class, 'BRAND_ID', 'BRAND_ID'); }
    public function variants() { return $this->hasMany(ProductVariant::class, 'PRODUCT_ID', 'PRODUCT_ID'); }
    public function images()   { return $this->hasMany(ProductImage::class, 'PRODUCT_ID', 'PRODUCT_ID'); }
    public function tags()     { return $this->belongsToMany(Tag::class, 'PRODUCT_TAGS', 'PRODUCT_ID', 'TAG_ID'); }
    public function reviews()  { return $this->hasMany(ProductReview::class, 'PRODUCT_ID', 'PRODUCT_ID'); }

    public function thumbnail()
    {
        return $this->hasOne(ProductImage::class, 'PRODUCT_ID', 'PRODUCT_ID')->where('IS_THUMBNAIL', 1);
    }
}