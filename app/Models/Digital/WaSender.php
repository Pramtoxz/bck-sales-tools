<?php

namespace App\Models\Digital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaSender extends Model
{
    use HasFactory;

    protected $connection = 'pgsql_nms';
    protected $table = 'Master_Schema.wa_senders';

     protected $fillable = [
        'kode_dealer', 'label', 'no_hp',
        'api_token', 'sender_id', 'base_url', 'status'
    ];
    protected $casts = ['status' => 'boolean'];


}
