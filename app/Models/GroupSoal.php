<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSoal extends Model
{
    use HasFactory;
    protected $table='lms.group_soal';
    protected $guarded=[
        'id'
    ];

    public function soalPreTest(){
        return $this->hasMany(EventTraining::class,'kode_soal_pre_test','kode_soal');
    }

    public function soalPostTest(){
        return $this->hasMany(EventTraining::class,'kode_soal_post_test','kode_soal');
    }

    public function soalPreview(){
        return $this->hasMany(Soal::class,'kode_soal','kode_soal')->orderBy("no_soal","ASC");
    }


}
