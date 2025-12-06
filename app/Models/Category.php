<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'CATEGORIES'; 

    protected $primaryKey = 'CATEGORY_ID';

    public $timestamps = false;

    protected $fillable = [
        'CATEGORY_NAME',
        'PARENT_ID'
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'PARENT_ID', 'CATEGORY_ID');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'PARENT_ID', 'CATEGORY_ID');
    }
    
    public function products()
    {
        return $this->hasMany(Product::class, 'CATEGORY_ID', 'CATEGORY_ID');
    }
}