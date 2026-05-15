<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class EventTraining extends Model
{
    use HasFactory;
    protected $table='lms.event_training';
    protected $guarded=[
        'id'
    ];


    public function training()
    {
        return $this->belongsTo(Training::class,'kd_training','kd_training');
    }

    public function peserta_training()
    {
        return $this->hasMany(PesertaTraining::class,'kd_event_training','kd_event_training')->select('kd_event_training','kd_karyawan','nilai_pre_test','nilai_post_test');
    }

    public function peserta_training_objective()
    {
        return $this->hasMany(PesertaTraining::class,'kd_event_training','kd_event_training')->select('kd_event_training','peserta_training.kd_karyawan','nilai_pre_test','nilai_post_test')
        ->with('eventTrainingPreTest','eventTrainingPostTest','jawabanPreTest');
    }
}
