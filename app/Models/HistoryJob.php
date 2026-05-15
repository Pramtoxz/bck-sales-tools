<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryJob extends Model
{
    use HasFactory;
    protected $table='history_job';
    protected $guarded=[
        'id'
    ];
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class,'kd_karyawan','kd_karyawan');
    }
}
