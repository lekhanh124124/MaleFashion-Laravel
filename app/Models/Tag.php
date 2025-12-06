<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'TAGS';
    protected $primaryKey = 'TAG_ID';
    public $timestamps = false;

    protected $fillable = ['TAG_NAME'];

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'PRODUCT_TAGS',
            'TAG_ID',
            'PRODUCT_ID'
        );
    }
}