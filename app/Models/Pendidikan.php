<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    use HasFactory;
    protected $table='pendidikan';

    public function pendidikanKaryawan()
    {
        return $this->belongsTo(Karyawan::class,'kd_pendidikan','kd_pendidikan')
        ->select('id','kd_pendidikan','nm_pendidikan');
    }
}
