<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'pgsql_nms';
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'kd_kariawan',
        'level',
        'no_hp',
        'fk_dealer',
        'it',
        'wing_dealer',
        'fk_provinsi',
        'flg_md_d',
        'flag_access_employee',
        'is_active',
        'user_pos',
        'is_kacab',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'password_update_at' => 'datetime',
            'blocked_at' => 'datetime',
            'banned_until' => 'boolean',
            'is_kacab' => 'boolean',
            'password' => 'hashed',
        ];
    }
}
