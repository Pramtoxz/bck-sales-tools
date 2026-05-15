<?php

namespace App\Models\Digital;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WaWebhook extends Model
{
    use HasFactory;
    
    protected $connection = 'pgsql_nms';
    protected $table = 'Master_Schema.wa_webhook';

    protected $fillable = [
        'no_hp',
        'nama',
        'message',
        'unique_id',
        'no_hp_aptana',
        'fk_dealer',
        'incoming_at',
        'type',
    ];
}