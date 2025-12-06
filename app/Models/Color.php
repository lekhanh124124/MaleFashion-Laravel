<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $table = 'COLORS';
    protected $primaryKey = 'COLOR_ID';
    public $timestamps = false;

    protected $fillable = ['COLOR_NAME', 'COLOR_CODE'];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'COLOR_ID', 'COLOR_ID');
    }
}