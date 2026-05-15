<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;
    protected $table='lms.bank_soal';
    protected $guarded=[
        'id'
    ];

    // public function training(){
    //     return $this->belongsTo(Training::class,'kd_training','kd_training');
    // }


}
