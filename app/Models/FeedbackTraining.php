<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackTraining extends Model
{
    use HasFactory;
    protected $table='lms.feedback_training';
    protected $guarded=[
        'id'
    ];
}
