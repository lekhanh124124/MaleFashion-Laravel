<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'BANNERS';
    protected $primaryKey = 'BANNER_ID';
    public $timestamps = false;

    protected $fillable = [
        'TITLE','SUBTITLE','IMAGE_URL','LINK_URL','CATEGORY_ID',
        'POSITION','DISPLAY_ORDER','IS_ACTIVE'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'CATEGORY_ID', 'CATEGORY_ID');
    }

    public function getShopUrlAttribute()
    {
        if ($this->CATEGORY_ID) {
            return route('shop', ['category' => $this->CATEGORY_ID]);
        }
        
        return route('shop');
    }
}