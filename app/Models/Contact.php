<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'CONTACTS';
    protected $primaryKey = 'CONTACT_ID';

    public $timestamps = false;

    protected $fillable = [
        'USER_ID', 'NAME', 'EMAIL', 'MESSAGE', 'STATUS', 'CREATED_AT'
    ];

    protected $dates = ['CREATED_AT'];

    public function user()
    {
        return $this->belongsTo(User::class, 'USER_ID', 'USER_ID');
    }
}