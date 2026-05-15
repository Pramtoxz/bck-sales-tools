<?php

namespace App\Models\cuti;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasOne;

class CutiDetail extends Model
{
    use HasFactory;
    protected $table = 'cuti.tbl_izin_detail';
    protected $guarded=([
        'id'
    ]);
 
}
