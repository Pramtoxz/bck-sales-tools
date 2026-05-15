<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agama extends Model
{
    use HasFactory;
    protected $table='agama';

    public function agamaKaryawan()
    {
        return $this->belongsTo(Karyawan::class,'kd_agama','kd_agama')
        ->select('id','kd_agama','nama_agama');
    }
}
