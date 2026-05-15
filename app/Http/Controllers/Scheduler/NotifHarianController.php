<?php

namespace App\Http\Controllers\Scheduler;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class NotifHarianController extends Controller
{
    public function handle()
    {
        $tanggal_sekarang = Carbon::now()->format('Y-m-d');
        
        $pesertatraining=User::join('public.karyawan','users.kd_karyawan','karyawan.kd_karyawan')
        ->join('lms.peserta_training','karyawan.kd_karyawan','peserta_training.kd_karyawan')
        ->join('lms.event_training','peserta_training.kd_event_training','event_training.kd_event_training')
        ->join('lms.training','event_training.kd_training','training.kd_training')
        ->where('peserta_training.status','!=','Selesai')
        ->whereDate('event_training.tanggal_mulai', '<=', $tanggal_sekarang)
        ->whereDate('event_training.tanggal_akhir', '>=', $tanggal_sekarang)
        ->select(
            'users.id',
            'karyawan.nama_lengkap',
            'users.email',
            'users.name',
            'peserta_training.kd_karyawan',
            'peserta_training.kd_event_training',
            'training.nama_training',
            'training.kd_training',
            'event_training.tanggal_mulai',
            'event_training.tanggal_akhir',
            DB::raw("event_training.tanggal_akhir - '$tanggal_sekarang' AS sisa_hari_menyelesaikan")
        )
        ->get();

        foreach ($pesertatraining as $user){
            Notification::send($user,new SchedulerNotifications($user));
        }
    }
    
}
