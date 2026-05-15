<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaTraining extends Model
{
    use HasFactory;
    protected $table='lms.peserta_training';

    // public function score_training(){
    // return $this->hasMany
    // }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class,'kd_karyawan','kd_karyawan')->select("kd_karyawan","nama_lengkap");
    }

    public function eventTraining(){
        return $this->belongsTo(EventTraining::class,'kd_event_training','kd_event_training');
    }

    public function eventTrainingPreTest(){
        return $this->belongsTo(EventTraining::class,'kd_event_training','kd_event_training')->select("kd_event_training","kode_soal",\DB::raw('count(*) as jumlah'))
        ->leftjoin("lms.bank_soal","bank_soal.kode_soal","event_training.kode_soal_pre_test")->groupBy('kd_event_training','kode_soal_pre_test','kode_soal');
    }

    public function eventTrainingPostTest(){
        return $this->belongsTo(EventTraining::class,'kd_event_training','kd_event_training')->select("kd_event_training","kode_soal",\DB::raw('count(*) as jumlah'))
        ->leftjoin("lms.bank_soal","bank_soal.kode_soal","event_training.kode_soal_post_test")->groupBy('kd_event_training','kode_soal_post_test','kode_soal');
    }

    public function jawabanPreTest(){
        $user = auth()->user()->kd_karyawan;
        return $this->hasMany(JawabanSoal::class,'kd_event_training','kd_event_training')->where('tipe_test','pre-test')->where('kd_karyawan',$user)->groupBy('status','kd_event_training')->select(\DB::raw('count(*) as jumlah'),'status','kd_event_training');
    }

    public function jawabanPostTest(){
        $user = auth()->user()->kd_karyawan;
        return $this->hasMany(JawabanSoal::class,'kd_event_training','kd_event_training')->where('tipe_test','post-test')->where('kd_karyawan',$user)->groupBy('status','kd_event_training')->select(\DB::raw('count(*) as jumlah'),'status','kd_event_training');
    }
}


