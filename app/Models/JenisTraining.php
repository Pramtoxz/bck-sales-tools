<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Training;

class JenisTraining extends Model
{
    use HasFactory;
    protected $table='lms.jenis_training';
    protected $guarded=[
        'id'
    ];
    public function training(){
        return $this->hasMany(Training::class,'kd_jenis_training','kd_jenis_training');
    }
}
