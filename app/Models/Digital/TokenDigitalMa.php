<?php

namespace App\Models\Digital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenDigitalMa extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_nms';
    protected $table='Master_Schema.token_digital_ma';
}
