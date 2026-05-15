<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaTest extends Model
{
    use HasFactory;
    protected $table='lms.peserta_test';

    public $timestamps = false;
   
}
