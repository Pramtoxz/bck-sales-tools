<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use PDF;
use App\Models\User;
use App\Models\EventTraining;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('pages.lms.report.training');
        
        
    }

    public function download_history_jadwal(Request $r){
        $tanggal_awal = $r->tanggal_awal;
        $tanggal_akhir = $r->tanggal_akhir;
        $dataEventTraining = EventTraining::whereDate('tanggal_mulai','>=',$tanggal_awal)->whereDate('tanggal_akhir','<=',$tanggal_akhir)->select("kd_event_training","training.nama_training","tanggal_mulai","tanggal_akhir")
        ->leftjoin('lms.training','training.kd_training','event_training.kd_training')
        ->with('peserta_training.karyawan')->get();

        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y'),
            'data' => $dataEventTraining
        ];
              
        $pdf = PDF::loadView('pages.lms.report.cetak.history_jadwal', $data);
       
        return $pdf->download('history_jadwal.pdf');
    }
    

}
