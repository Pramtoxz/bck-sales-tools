<?php

namespace App\Models\cuti;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasOne;

class Cuti extends Model
{
    use HasFactory;
    protected $table = 'cuti.tbl_izin';
    protected $guarded=([
        'id'
    ]);

    public function karyawan()
    {
        return $this->belongsTo(\App\Models\Karyawan::class,'kd_karyawan','kd_karyawan')
        ->select('kd_karyawan','nama_lengkap');
    }

    public function detail_cuti()
    {
        return $this->hasMany(\App\Models\cuti\CutiDetail::class,'id_tbl_izin','id');
    }

}
