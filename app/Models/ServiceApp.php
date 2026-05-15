<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\MasterMenu;

class ServiceApp extends Model
{
    use HasFactory;
    protected $table = 'service_apps';
    protected $guarded=([
        'id'
    ]);
    
    public function master_menu()
    {
        return $this->hasMany(MasterMenu::class,'kd_service_apps','kd_service_apps')->where('active','t')->where('tipe_menu','all')->select("nama_menu","id","kd_service_apps");
    }
}
