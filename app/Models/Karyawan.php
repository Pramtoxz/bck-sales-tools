<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Karyawan extends Model
{
    use HasFactory;
    protected $table = 'karyawan';
    protected $guarded=([
        'id'
    ]);

    // protected $casts = [
    //     'tanggal_bergabung'  => 'date:d-m-Y',
    // ];

    public function karyawanPendidikan():HasOne
    {
        return $this->hasOne(Pendidikan::class);
        
    }

    public function karyawanAgama():HasOne
    {
        return $this->hasOne(Agama::class);
        
    }

    public function historyjob()
    {
        return $this->hasMany(HistoryJob::class,'kd_karyawan','kd_karyawan');
    }

    public function history_job()
    {
        return $this->belongsTo(HistoryJob::class,'kd_karyawan','kd_karyawan')
        ->select('kd_karyawan','mulai_menjabat','resign');
    }

    public function departement():HasOne
    {
        return $this->hasOne(Departement::class,'kd_departement','kd_departement');
        
    }
 
    public function jabatan():HasOne
    {
        return $this->hasOne(Jabatan::class,'kd_jabatan','kd_jabatan');
        
    }

    public function pesertaTraining()
    {
        return $this->hasMany(PesertaTraining::class,'kd_karyawan','kd_karyawan');
    }  
}
