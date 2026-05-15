<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;
    protected $table='lms.materi';
    protected $guarded=[
        'id'
    ];

    public function training(){
        return $this->belongsTo(Training::class,'kd_training','kd_training');
    }
    public function feedbackTraining(){
        $user = auth()->user()->kd_karyawan;
        return $this->hasMany(FeedbackTraining::class,'kd_training','kd_training')
        ->select('kd_training','kd_karyawan','catatan');
    }
    public function historyActivityTraining(){
        return $this->belongsTo(ActivityTraining::class,'id','id_materi');
    }
}
