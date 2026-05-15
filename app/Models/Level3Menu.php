<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Level2Menu;

class Level3Menu extends Model
{
    use HasFactory;
    protected $table = 'level3_menu';

    public function level2menu()
	{
		return $this->belongsTo(Level2Menu::class, 'kd_level2_menu', 'kd_level2_menu')
        ->select('kd_level2_menu','link','nama_menu','kd_level1_menu','created_at');
	}
}
