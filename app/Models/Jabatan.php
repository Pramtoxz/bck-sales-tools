<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Departement;

class Jabatan extends Model
{
    use HasFactory;
    protected $table='jabatan';
    protected $fillable=[
        'kd_departement',
        'kd_jabatan',
        'nama_jabatan',
        'active'
    ];
    public $timestamps = false;
    
    public function departement()
    {
        return $this->belongsTo(Departement::class,'kd_departement','kd_departement')
        ->select('id','kd_departement','deskripsi');
    }
    // return $this->belongsTo(Departement::class)->withDefault(['id' => '', 'nama_departement' => '']);
    // public function departement()
    // {
    //     return $this->belongsTo(Departement::class,'kd_departement','kd_departement')
    //     ->select('kd_departement','deskripsi');
    // }

}
