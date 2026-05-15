<?php

namespace App\Models\proposal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Proposal extends Model
{
    use HasFactory;
    protected $table = 'proposal.header_proposal';
    protected $guarded=([
        'id'
    ]);
 
}
