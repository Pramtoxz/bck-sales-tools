<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityTraining extends Model
{
    use HasFactory;
    protected $table = 'lms.history_activity_training';
    protected $guarded=[
        'id'
    ];
    
}
