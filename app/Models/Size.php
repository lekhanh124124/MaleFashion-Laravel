<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'SIZES';
    protected $primaryKey = 'SIZE_ID';
    public $timestamps = false;

    protected $fillable = ['SIZE_NAME'];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'SIZE_ID', 'SIZE_ID');
    }
}