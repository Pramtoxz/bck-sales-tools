<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flp extends Model
{
    protected $connection = 'pgsql_nms';
    protected $table = 'flp';
    
    protected $fillable = [
        'id_flp',
        'nama',
        'token',
        'chat_id'
    ];
    
    protected $hidden = [
        'token'
    ];
}
