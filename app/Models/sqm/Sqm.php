<?php

namespace App\Models\sqm;

use App\Models\Digital\WaMsgTmp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sqm extends Model
{
    use HasFactory;

    protected $connection = 'pgsql_nms';
    protected $table = 'HC3.tbl_sqm';

    protected $primaryKey = 'id_unique';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_unique',
        'id_transaksi',
        'rating',
        'mot',
        'nps',
        'text',
        'dihubungi',
        'channel',
        'status',
        'tgl_transaksi',
        'fk_dealer',
        'reminder_sent_at'

    ];

    protected $casts = [
        'id'            => 'integer',
        'rating'        => 'integer',
        'nps'           => 'integer',
        'dihubungi'     => 'boolean',
        'tgl_transaksi' => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];
    public function waMsgTmp()
    {
        return $this->hasOne(WaMsgTmp::class, 'sqm_id', 'id_unique');
    }
}
