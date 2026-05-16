<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flp extends Model
{
    use HasFactory;

    protected $connection = 'pgsql_nms';
    protected $table = 'H1_DOS.tblflp';
    protected $primaryKey = 'id';

    protected $fillable = [
        'no_id',
        'nama',
        'kd_dlr',
        'jabatan',
        'target',
        'kode_jabatan',
        'id_level',
        'team',
        'bulan',
        'tahun',
    ];

    protected $casts = [
        'target' => 'integer',
        'id_level' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'no_id', 'kd_kariawan');
    }

    public function devices()
    {
        return $this->hasMany(FlpDevice::class, 'id_flp', 'no_id');
    }
}
