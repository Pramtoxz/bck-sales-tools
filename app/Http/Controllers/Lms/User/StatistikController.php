<?php

namespace App\Http\Controllers\Lms\User;

use App\Http\Controllers\Controller;
use App\Models\PesertaTraining;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    public function index()
    {
        return view('pages.lms.statistik.index');
    }
    public function get()
    {
        $tahunSekarang=date('Y');
        $startYear=2023;
        $year=range($startYear,$tahunSekarang);
        return response()->json($year);
    }

    public function getMonth()
    {
        $arrayBulan = [
            ["bulan"=>"Januari","index"=>"1"],
            ["bulan"=>"Februari","index"=>"2"],
            ["bulan"=>"Maret","index"=>"3"],
            ["bulan"=>"April","index"=>"4"],
            ["bulan"=>"Mei","index"=>"5"],
            ["bulan"=>"Juni","index"=>"6"],
            ["bulan"=>"Juli","index"=>"7"],
            ["bulan"=>"Agustus","index"=>"8"],
            ["bulan"=>"September","index"=>"9"],
            ["bulan"=>"Oktober","index"=>"10"],
            ["bulan"=>"November","index"=>"11"],
            ["bulan"=>"Desember","index"=>"12"],
        ];
        return response()->json($arrayBulan);
    }
    public function all(Request $r)
    {  
        $user = auth()->user()->kd_karyawan;
        $tahun = $r->tahun;
        $karyawan=$r->karyawan;
        if($karyawan){
            $user=$karyawan;
        }
        

        $result = PesertaTraining::selectRaw("
                COUNT(*) AS totaltrainings,
                SUM(CASE WHEN peserta_training.status = 'Selesai' THEN 1 ELSE 0 END) AS trainingselesai,
                SUM(CASE WHEN peserta_training.status IN ('Belum Mulai', 'Proses') AND event_training.tanggal_akhir >= NOW() THEN 1 ELSE 0 END) AS trainingberlangsung,
                SUM(CASE WHEN peserta_training.status != 'Selesai' AND event_training.tanggal_akhir < NOW() THEN 1 ELSE 0 END) AS trainingtakselesai,
                ROUND(AVG(nilai_pre_test), 2) AS avgpretest,
                ROUND(AVG(nilai_post_test), 2) AS avgposttest,
                MIN(nilai_pre_test) AS minpretest,
                MAX(nilai_pre_test) AS maxpretest,
                MIN(nilai_post_test) AS minposttest,
                MAX(nilai_post_test) AS maxposttest,
                AVG(EXTRACT(epoch FROM (peserta_training.tanggal_selesai - peserta_training.tanggal_mulai)) / 3600) AS datediff,
                MIN(EXTRACT(epoch FROM (peserta_training.tanggal_selesai - peserta_training.tanggal_mulai)) / 3600) AS min_jam_peserta,
                MAX(EXTRACT(epoch FROM (peserta_training.tanggal_selesai - peserta_training.tanggal_mulai)) / 3600) AS max_jam_peserta
                ")
                ->join('lms.event_training', 'peserta_training.kd_event_training', '=', 'lms.event_training.kd_event_training')
                ->where('peserta_training.kd_karyawan', $user)
                ->when($tahun != "",function($q) use($tahun){
                    $q->whereYear('event_training.tanggal_mulai', $tahun);
                })
                ->first();
        
            // (SELECT MIN(EXTRACT(epoch FROM (CAST(tanggal_akhir AS timestamp) - CAST(tanggal_mulai AS timestamp))) / 3600)
            // FROM lms.event_training) AS min_jam,
            // (SELECT MAX(EXTRACT(epoch FROM (CAST(tanggal_akhir AS timestamp) - CAST(tanggal_mulai AS timestamp))) / 3600)
            // FROM lms.event_training) max_jam

        $trainingScores = PesertaTraining::where('peserta_training.kd_karyawan', $user)
                ->join('lms.event_training', 'peserta_training.kd_event_training', '=', 'event_training.kd_event_training')
                ->when($tahun != "",function($q) use($tahun){
                    $q->whereYear('event_training.tanggal_mulai', $tahun);
                })
                ->leftjoin('lms.training','event_training.kd_training','=','training.kd_training')
                ->select('training.nama_training', 'peserta_training.nilai_pre_test', 'peserta_training.nilai_post_test')
                ->get();

        $data = [
            'totaltrainings' => $result->totaltrainings ?? 0,
            'trainingselesai' => $result->trainingselesai ?? 0,
            'trainingberlangsung' => $result->trainingberlangsung ?? 0,
            'trainingtakselesai' => $result->trainingtakselesai ?? 0,
            'avgpretest' => round($result->avgpretest ?? 0),
            'avgposttest' => round($result->avgposttest ?? 0),
            'minpretest' => $result->minpretest ?? 0,
            'maxpretest' => $result->maxpretest ?? 0,
            'minposttest' => $result->minposttest ?? 0,
            'maxposttest' => $result->maxposttest ?? 0,
            'avgtraining' => round($result->datediff ?? 0),
            'mintraining' => round($result->min_jam_peserta ?? 0),
            'maxtraining' => round($result->max_jam_peserta ?? 0),
            'trainingScores' => $trainingScores
        ];
        return response()->json($data);
    }
    public function userstatistik(){
        return view('pages.lms.adminStatistik.index');
    }
    public function karyawanget()
    {   
        $karyawan = DB::table('karyawan')
        ->leftJoin('public.jabatan','karyawan.kd_jabatan','jabatan.kd_jabatan')
        ->select('karyawan.kd_karyawan as id',DB::raw("(karyawan.nama_lengkap || ' - ' || jabatan.nama_jabatan) as text"))
        ->where('karyawan.active','t')
        ->get();
        return response()->json($karyawan);
    }
}
