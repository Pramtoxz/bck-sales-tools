<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JenisTraining;
use App\Models\EventTraining;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\FeedbackTraining;
use Illuminate\Support\Facades\DB;

class Training extends Model
{
    use HasFactory;
    protected $table='lms.training';
    protected $guarded=[
        'id'
    ];
    public function jenis_training(){
        return $this->belongsTo(JenisTraining::class,'kd_jenis_training','kd_jenis_training')
        ->select('id','kd_jenis_training','nama_jenis');
    }
    public function eventTraining()
    {
        return $this->hasMany(EventTraining::class,'kd_training','kd_training')
        ->select('event_training.kd_event_training','event_training.kd_training',DB::raw('COUNT("peserta_training"."kd_karyawan") as jml_karyawan'))
        ->join('lms.peserta_training','event_training.kd_event_training','=','peserta_training.kd_event_training')
        ->groupBy('event_training.tanggal_akhir','event_training.kd_event_training','event_training.kd_training')
        ->orderBy('event_training.tanggal_akhir','asc');
    }
    public function materi(){
        return $this->hasMany(Materi::class,'kd_training','kd_training');
    }
    public function feedbackTraining(){
        return $this->hasMany(FeedbackTraining::class,'kd_training','kd_training');
    }
    public function pesertaEventTraining(){
        return $this->hasMany(EventTraining::class,'kd_training','kd_training')
        ->select('event_training.kd_training','peserta_training.id','peserta_training.kd_karyawan')
        ->join('lms.peserta_training','event_training.kd_event_training','=','peserta_training.kd_event_training');
    }

    public function trainingEvent()
    {
        return $this->hasMany(EventTraining::class,'kd_training','kd_training');
    }
}
