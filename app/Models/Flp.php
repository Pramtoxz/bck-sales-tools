<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flp extends Model
{
    use HasFactory;

    protected $connection = 'pgsql_nms';
    protected $table = 'flp';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_flp',
        'nama',
        'token',
        'user_id',
        'last_login',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function devices()
    {
        return $this->hasMany(FlpDevice::class, 'id_flp', 'id_flp');
    }
}
