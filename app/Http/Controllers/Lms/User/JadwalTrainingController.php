<?php

namespace App\Http\Controllers\Lms\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PesertaTraining;
use App\Helper\Menu;
class JadwalTrainingController extends Controller
{
    public function index()
    {
        return view('pages.lms.training.jadwal');
    }

    public function show()
    {
        $kd_karyawan = Auth::user()->kd_karyawan;
        $isAdmin = Auth::user()->is_admin;
        $year = date('Y');
        $tanggal_awal = $year . "-" . "01" . "-" . "01";
        $tanggal_akhir = $year . "-" . "12" . "-" . "31";
        $tanggal_sekarang = date('Y-m-d');
        $data = PesertaTraining::when($isAdmin == "f",function($q)use($kd_karyawan){
                $q->where('peserta_training.kd_karyawan', $kd_karyawan);
            })
            // ->whereYear('event_training.tanggal_mulai',$year)
            ->where('event_training.tanggal_mulai', '>=', $tanggal_awal)
            ->where('event_training.tanggal_mulai', '<=', $tanggal_akhir)
            ->leftjoin('lms.event_training', 'event_training.kd_event_training', 'peserta_training.kd_event_training')
            ->leftjoin('lms.training', 'training.kd_training', 'event_training.kd_training')
            ->leftjoin('public.karyawan', 'karyawan.kd_karyawan', 'peserta_training.kd_karyawan')
            ->select("training.nama_training", "event_training.tanggal_mulai", "event_training.tanggal_akhir", "karyawan.nama_panggilan","peserta_training.status")
            ->get();
        $dataCollect = collect($data)->map(function ($q) use ($tanggal_sekarang) {
            $tanggalMulaiModif = date('d',strtotime($q->tanggal_mulai));
            $tanggalAkhirModif = date('d',strtotime($q->tanggal_akhir));
            $bulanMulai = $q->tanggal_mulai;
            if($tanggalMulaiModif == "31"){
                $bulanMulai = date('Y-m-d',strtotime("-1 days",strtotime($q->tanggal_mulai)));
            }
            $bulanAkhir = $q->tanggal_akhir;
            if($tanggalAkhirModif == "31"){
                $bulanAkhir = date('Y-m-d',strtotime("-1 days",strtotime($q->tanggal_akhir)));
            }
            $className = "bg-success";
            if ($tanggal_sekarang > $q->tanggal_akhir) {
                $className = "bg-danger";
            }

            if($q->status == "Selesai"){
                $className = "bg-primary";
            }

            $awal = [
                "year" => date('Y', strtotime($q->tanggal_mulai)),
                "month" => date('n', strtotime("-1 months", strtotime($bulanMulai))),
                "day" => date('d', strtotime($q->tanggal_mulai))
            ];
            $akhir = [
                "year" => date('Y', strtotime($q->tanggal_akhir)),
                "month" => date('n', strtotime("-1 months", strtotime($bulanAkhir))),
                "day" => date('d', strtotime($q->tanggal_akhir)) + 1
            ];
            return [
                "nama_training" => $q->nama_training,
                "awal" => $awal,
                "akhir" => $akhir,
                "tanggal_mulai"=>$q->tanggal_mulai,
                "tanggal_akhir"=>$q->tanggal_akhir,
                "nama_karyawan" => $q->nama_panggilan,
                "className" => $className
            ];
        });
        return response()->json($dataCollect);
    }
}
