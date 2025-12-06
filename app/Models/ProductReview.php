<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $table = 'PRODUCT_REVIEWS';
    protected $primaryKey = 'REVIEW_ID';
    public $timestamps = false;

    protected $fillable = ['PRODUCT_ID','USER_ID','RATING','COMMENT','REVIEW_NAME','REVIEW_EMAIL','CREATED_AT'];

    public function product() { return $this->belongsTo(Product::class, 'PRODUCT_ID', 'PRODUCT_ID'); }
    public function user()    { return $this->belongsTo(User::class, 'USER_ID', 'USER_ID'); }
}