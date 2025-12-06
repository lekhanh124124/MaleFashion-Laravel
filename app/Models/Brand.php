<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'BRANDS';
    protected $primaryKey = 'BRAND_ID';
    public $timestamps = false;

    protected $fillable = ['BRAND_NAME'];

    public function products()
    {
        return $this->hasMany(Product::class, 'BRAND_ID', 'BRAND_ID');
    }
}