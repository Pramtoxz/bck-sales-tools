<?php

namespace App\Models\cuti;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasOne;

class JenisCuti extends Model
{
    use HasFactory;
    protected $table = 'cuti.jenis_cuti';
    protected $guarded=([
        'id'
    ]);
 
}
