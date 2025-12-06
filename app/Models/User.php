<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'USERS';
    protected $primaryKey = 'USER_ID';
    public $timestamps = false;

    protected $fillable = [
        'FIRST_NAME','LAST_NAME','EMAIL','PHONE','PASSWORD',
        'ADDRESS','ROLE','STATUS'
    ];
    protected $hidden = ['PASSWORD'];

    // BẮT BUỘC: Khai báo hàm này để Auth biết cột mật khẩu tên là gì
    public function getAuthPassword() {
        return $this->PASSWORD;
    }

    // Accessor cho full_name (dùng trong header: Auth::user()->full_name)
    public function getFullNameAttribute() {
        return $this->FIRST_NAME . ' ' . $this->LAST_NAME;
    }

    public function scopeCustomers($q){
        return $q->whereNotIn('ROLE',['admin','staff']);
    }
}