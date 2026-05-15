<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Level1Menu;

class Level2Menu extends Model
{
    use HasFactory;
    protected $table = 'level2_menu';

    public function level1menu()
	{
		return $this->belongsTo(Level1Menu::class, 'kd_level1_menu', 'kd_level1_menu')
        ->select('kd_level1_menu','link','icon','nama_menu','created_at');
	}
}
