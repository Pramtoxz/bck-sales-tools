<?php

namespace App\Http\Controllers\Absensi;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\cuti\Cuti;
use App\Models\cuti\CutiDetail;
use App\Models\cuti\Absensi;
use App\Models\cuti\TanggalLibur;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\Karyawan;
use Carbon\Carbon;
use App\Models\Digital\WaMsgTmp;
use App\Imports\KaryawanImport;
use Maatwebsite\Excel\Facades\Excel;
use DateTime;
use PDO;
use App\Exports\ReportAbsensiExport;
use App\Models\PesertaTraining;

class AbsensiController extends Controller
{
    public function index(){ 
        return view('pages.absensi.absensi');
    }

    public function reportView(){
        return view('pages.absensi.report');
    }

    public function show(Request $r){
        $data = $this->generate($r);
        return DataTables::of($data)
        ->editColumn('waktu_kedatangan',function($q){
            return date('d-m-Y H:i:s',strtotime($q->waktu_kedatangan));
        })
        ->make(true);
    }

    public function generate($r){
        $bulan = $r->bulan;
        $tahun = $r->tahun;
        $is_admin=auth()->user()->is_admin;
        $user = auth()->user()->kd_karyawan;
        $data = Absensi::where("bulan",$bulan)->where("tahun",$tahun)
        ->join("public.karyawan","karyawan.id_absensi","absensi.id_absensi")
        ->select("absensi.*","karyawan.nama_lengkap")
        ->when($is_admin != "t",function($q) use($user){
            $q->where("karyawan.kd_karyawan",$user);
        })
        ->orderBy("absensi.id_absensi","ASC")->orderBy("absensi.waktu_kedatangan","ASC");
        return $data;
    }

    public function import(Request $r){
        $bulan = $r->bulan;
        $tahun = $r->tahun;
        $rules = [
            'upload_file' => 'required|mimes:csv,xlsx,xls'
        ];
        $message = [
            "upload_file.required"=> "File Tidak Boleh Kosong",
        ];
        $validator = Validator::make($r->all(), $rules,$message);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),422);
        }
        // cek data telebih dahulu
        $jumlahData = Absensi::where("bulan",$bulan)->where("tahun",$tahun)->count();
        if($jumlahData > 0){
            return response()->json([
                "code"=>400,
                "status"=>"false",
                "message"=>"Data gagal import karna data sudah ada"
            ]);
        }

        try{  
            // Excel::import(new KaryawanImport(), $r->file('upload_file'));
            $timeBatas = DateTime::createFromFormat('H:i:s', '08:01:00');
            $collection = Excel::toCollection(new KaryawanImport, $r->file('upload_file'));
            $sheet1 = $collection[0];
            $dataInsert = [];
            foreach($sheet1 as $value){
                $waktu_kedatangan = $value[2];
                $explodeDateTime = explode(" ",$waktu_kedatangan);
                $date = $explodeDateTime[0];
                $time = $explodeDateTime[1];
                $datetime = DateTime::createFromFormat('n/j/Y', $date);
                $formattedDate = $datetime->format('Y-m-d');
                $datetime = DateTime::createFromFormat('G:i', $time);
                $formattedTime = $datetime->format('H:i:s');
                $timeDatang = DateTime::createFromFormat('H:i:s', $formattedTime);
                $datetimestring = $formattedDate." ".$formattedTime;
                $waktu_kedatangan = date("Y-m-d H:i:s",strtotime($datetimestring));
                $id = $value[0];
                $telat = $timeDatang > $timeBatas ? true : false ;
                $dataInsert[]= [
                    "id_absensi"=>$id,
                    "waktu_kedatangan"=>$waktu_kedatangan,
                    "created_at"=>date("Y-m-d H:i:s"),
                    "updated_at"=>date("Y-m-d H:i:s"),
                    "bulan"=>$bulan,
                    "tahun"=>$tahun,
                    "telat"=>$telat
                ];
            }

            Absensi::insert($dataInsert);
            
            DB::commit();
            return response()->json([
                "code"=>200,
                "status"=>"true",
                "message"=>"Sukses Import Data"
            ]);
        }catch(\Exception $ex){
            DB::rollBack();
            return response()->json([
                "code"=>400,
                "status"=>"false",
                "message"=>$ex->getMessage(),
                "data"=>[]
            ]);
        }
    }

    public function delete(Request $r){
        $bulan = $r->bulan;
        $tahun = $r->tahun;
        $delete = Absensi::where("bulan",$bulan)->where("tahun",$tahun)->delete();
        if($delete){
            return response()->json(true);
        }else{
            return response()->json(false);
        }
    }


    public function report(Request $r)
    {
        $bulan = $r->bulan;
        $tahun = $r->tahun;
        if($bulan == "1"){
            $tahunExtract = (int) $tahun - 1;
            $bulanStart = "12";
            $startDate = $tahunExtract."-".$bulanStart."-"."26";
        }else{
            $bulanStart = (int) $bulan - 1;
            $startDate = $tahun."-".$bulanStart."-"."26";
        }
        $finishDate = $tahun."-".$bulan."-"."25";
        $formatedDateFinishDate = date("Y-m-d",strtotime($finishDate));
        $getRangeDate = $this->getDateRangeExceptSundays($startDate,$finishDate);
        $tanggalDate = [];
        foreach($getRangeDate as $value){
            $tanggalDate[] = $value;
        }
        $dataLibur = TanggalLibur::whereDate("tanggal_libur",">=",$startDate)->whereDate("tanggal_libur","<=",$finishDate)->where("tahun",$tahun)->select('tanggal_libur')->get();
        // return $dataLibur;
        $tanggalLibur = [];
        foreach($dataLibur as $value){
            $tanggalLibur[] = $value->tanggal_libur;
        }
        $dateDif = array_diff($tanggalDate,$tanggalLibur);
        $jumlahHariKerja = count($dateDif);
        $dataAbsensi = Absensi::where('absensi.bulan',$bulan)->where("absensi.tahun",$tahun)->join("public.karyawan","karyawan.id_absensi","absensi.id_absensi")
        ->select('karyawan.kd_karyawan','absensi.telat', DB::raw('count(*) as total'))
        ->groupBy('karyawan.kd_karyawan','absensi.telat')
        ->get();
        // return $dataAbsensi;

        $dataCuti = CutiDetail::whereDate("tbl_izin_detail.tanggal_cuti",">=",$startDate)->whereDate("tbl_izin_detail.tanggal_cuti","<=",$finishDate)->join("cuti.tbl_izin","tbl_izin.id","tbl_izin_detail.id_tbl_izin")
        ->join("cuti.jenis_cuti","jenis_cuti.id","tbl_izin.id_jenis_cuti")
        ->where("tbl_izin.status_approval","Diterima")
        // ->where("jenis_cuti.tipe_izin","cuti")
        ->select("tbl_izin.kd_karyawan", DB::raw('count(*) as total'),"tipe_izin")
        ->groupBy('tbl_izin.kd_karyawan',"tipe_izin")
        ->get();

        // return $dataCuti;

        $dataKaryawan = Karyawan::select("kd_karyawan","nama_lengkap")->where('active','t')->orderBy("nama_lengkap","ASC")->get();
        $tmpKaryawan = [];
        foreach($dataKaryawan as $value){
            $tmpKaryawan[$value->kd_karyawan] = [
                "nama_karyawan"=>$value->nama_lengkap,
                "jumlah_hari_kerja"=>$jumlahHariKerja,
                "jumlah_cuti"=>0,
                "jumlah_telat"=>0,
                "jumlah_tepat_waktu"=>0,
                "jumlah_sakit"=>0,
                "jumlah_kehadiran"=>0,
                "jumlah_supervisi"=>0
            ];
        }

        // extract data absensi
        foreach($dataAbsensi as $value){
            if(array_key_exists($value->kd_karyawan,$tmpKaryawan)){
                $tmpKaryawan[$value->kd_karyawan]["jumlah_kehadiran"] += $value->total;
                if($value->telat){
                    $tmpKaryawan[$value->kd_karyawan]["jumlah_telat"] += $value->total;
                }else{
                    $tmpKaryawan[$value->kd_karyawan]["jumlah_tepat_waktu"] += $value->total;
                }
            }
        }

        foreach($dataCuti as $value){
            if(array_key_exists($value->kd_karyawan,$tmpKaryawan)){
                $tmpKaryawan[$value->kd_karyawan]["jumlah_kehadiran"] += $value->total;
                if($value->tipe_izin == "cuti"){
                    $tmpKaryawan[$value->kd_karyawan]["jumlah_cuti"] += $value->total;
                }else if($value->tipe_izin == "sakit"){
                    $tmpKaryawan[$value->kd_karyawan]["jumlah_sakit"] += $value->total;
                }else if($value->tipe_izin == "supervisi"){
                    $tmpKaryawan[$value->kd_karyawan]["jumlah_supervisi"] += $value->total;
                }
            }
        }
        $data = array_values($tmpKaryawan);
        $export = new ReportAbsensiExport($data,$formatedDateFinishDate);
        return Excel::download($export, 'Download Report Absensi.xlsx');
    }

    private function getDateRangeExceptSundays($startDate, $endDate)
    {
        $dates = collect();
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
    
        while ($start->lte($end)) {
            if (!$start->isSunday()) {
                $dates->push($start->copy()->toDateString());
            }
            $start->addDay();
        }
    
        return $dates;
    }

    public function indexcoe()
    {
        return view('pages.absensi.coemanager');
    }

    public function getdatacoe(Request $r)
{
    $tanggal_sekarang = date('Y-m-d');
    $year = date('Y');

    $departement = $r->departement;
    $karyawan = $r->karyawan;
    $tahun = $r->tahun;


    $user = auth()->user()->kd_karyawan;
    $is_admin=auth()->user()->is_admin =='t' ? 't' : null;

    $pengaprove=Karyawan::join('public.jabatan','jabatan.kd_jabatan','karyawan.kd_jabatan')
    ->select('jabatan.flag_approval','karyawan.kd_departement')
    ->where('karyawan.kd_karyawan', $user)
    ->first();
    // return $pengaprove;

    $dataCuti = Cuti::whereRaw("tbl_izin.tgl_cuti LIKE '%$year%'")
        ->where('tbl_izin.status_approval', 'Diterima')
        ->leftJoin('cuti.jenis_cuti', 'jenis_cuti.id', 'tbl_izin.id_jenis_cuti')
        ->leftJoin('public.karyawan', 'karyawan.kd_karyawan', 'tbl_izin.kd_karyawan')
        ->select(
            "jenis_cuti.jenis_cuti",
            "tbl_izin.tgl_cuti",
            "karyawan.nama_panggilan",
            "tbl_izin.perihal_cuti"
        )
        ->when($pengaprove->flag_approval=='t' && !$is_admin, function($query) use ($pengaprove){
            $query->where('tbl_izin.kd_departement',$pengaprove->kd_departement);
        })
        ->when($pengaprove->flag_approval==null && !$is_admin, function($query) use ($user){
            $query->where('tbl_izin.kd_karyawan',$user);
        })
        ->when($departement, function($query) use ($departement){
            $query->where('tbl_izin.kd_departement',$departement);
        })
        ->when($karyawan, function($query) use ($karyawan){
            $query->where('tbl_izin.kd_karyawan',$karyawan);
        })
        // ->when($tahun, function($query) use ($tahun){   
        //     $query->whereRaw("tbl_izin.tgl_cuti LIKE '%$tahun%'");
        // })
        ->get();

    $collectCuti = collect($dataCuti)->flatMap(function ($q) use ($tanggal_sekarang) {
        $tanggal_array = explode(", ", $q->tgl_cuti);

        return collect($tanggal_array)->map(function ($tanggal) use ($q, $tanggal_sekarang) {
            $tgl = date('Y-m-d', strtotime($tanggal));
            // $className = $tanggal_sekarang > $tgl ? "bg-danger" : "bg-success";
            $className ="bg-info";

            return [
                // "title" => "Cuti: {$q->nama_panggilan} ({$q->jenis_cuti})",
                "title" => "{$q->nama_panggilan} ({$q->jenis_cuti})",
                "start" => $tgl,
                "className" => $className,
                "type" => "Cuti",
                "description" => $q->perihal_cuti
            ];
        });
    });

    $dataTraining = PesertaTraining::leftJoin('lms.event_training', 'event_training.kd_event_training', 'peserta_training.kd_event_training')
        ->leftJoin('lms.training', 'training.kd_training', 'event_training.kd_training')
        ->leftJoin('public.karyawan', 'karyawan.kd_karyawan', 'peserta_training.kd_karyawan')
        ->leftJoin('public.departement', 'karyawan.kd_departement', 'departement.kd_departement')
        ->select(
            "training.nama_training",
            "event_training.tanggal_mulai",
            "event_training.tanggal_akhir",
            "karyawan.nama_panggilan",
            "peserta_training.status"
        )
        ->when($pengaprove->flag_approval=='t' && !$is_admin, function($query) use ($pengaprove){
            $query->where('departement.kd_departement',$pengaprove->kd_departement);
        })
        ->when($pengaprove->flag_approval==null && !$is_admin, function($query) use ($user){
            $query->where('peserta_training.kd_karyawan',$user);
        })
        ->when($departement, function($query) use ($departement){
            $query->where('departement.kd_departement',$departement);
        })
        ->when($karyawan, function($query) use ($karyawan){
            $query->where('peserta_training.kd_karyawan',$karyawan);
        })
        // ->when($tahun, function ($query) use ($tahun) {
        //     $query->whereYear('event_training.tanggal_mulai', $tahun)
        //           ->whereYear('event_training.tanggal_akhir', $tahun);
        // })
        ->get();
        // return $dataTraining;

    $collectTraining = collect($dataTraining)->map(function ($q) use ($tanggal_sekarang) {
        // $tanggal_mulai = date('Y-m-d', strtotime($q->tanggal_mulai));
        // $tanggal_akhir = date('Y-m-d', strtotime($q->tanggal_akhir));

        // if (date('d', strtotime($tanggal_mulai)) == '31') {
        //     $tanggal_mulai = date('Y-m-d', strtotime('-1 day', strtotime($tanggal_mulai)));
        // }
        // if (date('d', strtotime($tanggal_akhir)) == '31') {
        //     $tanggal_akhir = date('Y-m-d', strtotime('-1 day', strtotime($tanggal_akhir)));
        // }

        $className = "bg-success";
        // if ($tanggal_sekarang > $tanggal_akhir) {
        //     $className = "bg-danger";
        // }
        // if ($q->status == "Selesai") {
        //     $className = "bg-primary";
        // }

        return [
            "title" => "{$q->nama_panggilan} ({$q->nama_training})",
            "start" => $q->tanggal_mulai,
            // "end" => date('Y-m-d', strtotime($tanggal_akhir . ' +1 day')),
            "end" => $q->tanggal_akhir,
            "className" => $className,
            "type" => "Training",
            "description" => $q->nama_training
        ];
    });

    $merge_data = $collectCuti->merge($collectTraining)->values();

    return response()->json($merge_data);
}

public function getdatacoe2(Request $r)
{
    $tanggal_sekarang = date('Y-m-d');
    $year = date('Y');

    $departement = $r->departement;
    $karyawan = $r->karyawan;
    $tahun = $r->tahun;


    $user = auth()->user()->kd_karyawan;
    $is_admin=auth()->user()->is_admin =='t' ? 't' : null;

    $pengaprove=Karyawan::join('public.jabatan','jabatan.kd_jabatan','karyawan.kd_jabatan')
    ->select('jabatan.flag_approval','karyawan.kd_departement')
    ->where('karyawan.kd_karyawan', $user)
    ->first();

    $dataCuti = Cuti::whereRaw("tbl_izin.tgl_cuti LIKE '%$year%'")
        ->where('tbl_izin.status_approval', 'Diterima')
        ->leftJoin('cuti.jenis_cuti', 'jenis_cuti.id', 'tbl_izin.id_jenis_cuti')
        ->leftJoin('public.karyawan', 'karyawan.kd_karyawan', 'tbl_izin.kd_karyawan')
        ->select(
            "jenis_cuti.jenis_cuti",
            "tbl_izin.tgl_cuti",
            "karyawan.nama_panggilan",
            "tbl_izin.perihal_cuti"
        )
        ->when($pengaprove->flag_approval=='t' && !$is_admin, function($query) use ($pengaprove){
            $query->where('tbl_izin.kd_departement',$pengaprove->kd_departement);
        })
        ->when($pengaprove->flag_approval==null && !$is_admin, function($query) use ($user){
            $query->where('tbl_izin.kd_karyawan',$user);
        })
        ->when($departement, function($query) use ($departement){
            $query->where('tbl_izin.kd_departement',$departement);
        })
        ->when($karyawan, function($query) use ($karyawan){
            $query->where('tbl_izin.kd_karyawan',$karyawan);
        })
        ->when($tahun, function($query) use ($tahun){   
            $query->whereRaw("tbl_izin.tgl_cuti LIKE '%$tahun%'");
        })
        ->get();

    $collectCuti = collect($dataCuti)->flatMap(function ($q) use ($tanggal_sekarang) {
        $tanggal_array = explode(", ", $q->tgl_cuti);

        return collect($tanggal_array)->map(function ($tanggal) use ($q, $tanggal_sekarang) {
            $tgl = date('Y-m-d', strtotime($tanggal));
            $className ="bg-info";

            return [
                "title" => "{$q->nama_panggilan} ({$q->jenis_cuti})",
                "start" => $tgl,
                "className" => $className,
                "type" => "Cuti",
                "description" => $q->perihal_cuti
            ];
        });
    });

    $dataTraining = PesertaTraining::leftJoin('lms.event_training', 'event_training.kd_event_training', 'peserta_training.kd_event_training')
        ->leftJoin('lms.training', 'training.kd_training', 'event_training.kd_training')
        ->leftJoin('public.karyawan', 'karyawan.kd_karyawan', 'peserta_training.kd_karyawan')
        ->leftJoin('public.departement', 'karyawan.kd_departement', 'departement.kd_departement')
        ->select(
            "training.nama_training",
            "event_training.tanggal_mulai",
            "event_training.tanggal_akhir",
            "karyawan.nama_panggilan",
            "peserta_training.status"
        )
        ->when($pengaprove->flag_approval=='t' && !$is_admin, function($query) use ($pengaprove){
            $query->where('departement.kd_departement',$pengaprove->kd_departement);
        })
        ->when($pengaprove->flag_approval==null && !$is_admin, function($query) use ($user){
            $query->where('peserta_training.kd_karyawan',$user);
        })
        ->when($departement, function($query) use ($departement){
            $query->where('departement.kd_departement',$departement);
        })
        ->when($karyawan, function($query) use ($karyawan){
            $query->where('peserta_training.kd_karyawan',$karyawan);
        })
        ->when($tahun, function ($query) use ($tahun) {
            $query->whereYear('event_training.tanggal_mulai', $tahun)
                  ->whereYear('event_training.tanggal_akhir', $tahun);
        })
        ->get();

    $collectTraining = collect($dataTraining)->map(function ($q) use ($tanggal_sekarang) {

        $className = "bg-success";

        return [
            "title" => "{$q->nama_panggilan} ({$q->nama_training})",
            "start" => $q->tanggal_mulai,
            "end" => $q->tanggal_akhir,
            "className" => $className,
            "type" => "Training",
            "description" => $q->nama_training
        ];
    });

    $merge_data = $collectCuti->merge($collectTraining)->values();

    return response()->json($merge_data);
}
}
