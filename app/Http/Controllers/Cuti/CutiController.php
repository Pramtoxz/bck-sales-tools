<?php

namespace App\Http\Controllers\cuti;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\cuti\Cuti;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\Karyawan;
use Carbon\Carbon;
use App\Models\Digital\WaMsgTmp;
// use App\Models\User;

class CutiController extends Controller
{
    public function index(){ 
        return view('pages.cuti.cuti');
    }

    public function getsisacuti(Request $r){
        $user = auth()->user()->kd_karyawan;
        $tahunSekarang=date('Y');

        $sisa_cuti_tahunan = Cuti::rightjoin('cuti.jenis_cuti', 'jenis_cuti.id', '=', DB::raw('1'))
        ->where('tbl_izin.kd_karyawan', $user)
        ->where('tbl_izin.status_approval', 'Diterima')
        ->where('tbl_izin.id_jenis_cuti', '1')
        ->whereYear('tbl_izin.created_at', $tahunSekarang)
        ->groupBy('jenis_cuti.kuota_cuti')
        ->selectRaw('jenis_cuti.kuota_cuti - COALESCE(SUM(tbl_izin.jumlah_cuti), 0) as sisa_cuti_tahunan')
        ->value('sisa_cuti_tahunan');

        return response()->json($sisa_cuti_tahunan ?? '10');

    }

    public function get(Request $r){
        $departement=$r->departement;
        $karyawan=$r->karyawan;

        $tahun=$r->tahun ?? date('Y');
        
        $user = auth()->user()->kd_karyawan;
        $is_admin=auth()->user()->is_admin =='t' ? 't' : null;

        $pengaprove=Karyawan::join('public.jabatan','jabatan.kd_jabatan','karyawan.kd_jabatan')
        ->select('jabatan.flag_approval','karyawan.kd_departement')
        ->where('karyawan.kd_karyawan', $user)
        ->first();

        $data=Cuti::select('jenis_cuti.jenis_cuti','karyawan.nama_lengkap','departement.deskripsi',
        'tbl_izin.id','tbl_izin.tgl_cuti','tbl_izin.jumlah_cuti','tbl_izin.alasan','tbl_izin.file',
        'tbl_izin.status_approval','tbl_izin.kd_karyawan','tbl_izin.created_at')
        ->whereYear('tbl_izin.created_at', $tahun)
        ->when($is_admin, function($query) use ($departement, $karyawan) {
            if($departement){
                $query->where('tbl_izin.kd_departement', $departement);
            }
            if ($karyawan) {
                $query->where('tbl_izin.kd_karyawan', $karyawan);
            }
        })

        ->when(!$is_admin, function($query) use ($pengaprove,$user) {

            if($pengaprove->flag_approval){
                return $query->where('tbl_izin.kd_departement', $pengaprove->kd_departement);
            }else{
                return $query->where('tbl_izin.kd_karyawan', $user); 
            }
        })
        ->leftjoin('public.karyawan','karyawan.kd_karyawan','tbl_izin.kd_karyawan')
        ->leftjoin('public.departement','departement.kd_departement','tbl_izin.kd_departement')
        ->leftjoin('cuti.jenis_cuti','jenis_cuti.id','tbl_izin.id_jenis_cuti')
        ->orderBy('tbl_izin.created_at','desc');
        // ->get();
        return DataTables::of($data)
        ->addColumn('aksi',function($q)use ($pengaprove,$user,$is_admin){
            $detail = '<button onClick="detail(' . "'$q->id'" . ')"  class="btn btn-primary btn-sm approve waves-effect waves-light" title="approval">
            <i class="fa fa-eye" title="Detail"></i>
            </button>';

            $Aprroval = '';
            $rejected='';
            if ($pengaprove->flag_approval =='t') {
                $Aprroval = '';
                
                if($q->status_approval=='Pending'){
                    $Aprroval = '<button onClick="approval(' . "'$q->id'" . ')" class="btn btn-success btn-sm approve waves-effect waves-light" title="approval">
                    <i class="fa fa-check" title="Approve"></i>
                    </button>';
                    $rejected = '<button onClick="reject(' . "'$q->id'" . ')" class="btn btn-danger btn-sm reject waves-effect waves-light" title="Reject">
                    <i class="fas fa-times" title="Reject"></i>
                    </button>';
                }
                // if($q->status_approval=='Pending'){
                  
                // }
             
            }

            // $editAction ='';
            // $hapusAction='';
            if($q->status_approval=='Pending' && !$is_admin){

                $editAction ='';
                $hapusAction='';

                if($user == $q->kd_karyawan){
                    $editAction = '<button onClick="editData(' . "'$q->id'" . ')"  class="btn btn-warning btn-sm delete waves-effect waves-light" title="edit" data-bs-toggle="modal" data-bs-target="#containerModal">
                    <i class="fas fa-pencil-alt" title="edit"></i>
                    </button>';
                    $hapusAction = '<button onClick="deleteData(' . "'$q->id'" . ')"  class="btn btn-danger btn-sm delete waves-effect waves-light" title="Delete">
                    <i class="fas fa-trash" title="Delete"></i>
                    </button>';
                }
               
            }
            else if($is_admin){
                $editAction = '<button onClick="editData(' . "'$q->id'" . ')"  class="btn btn-warning btn-sm edit waves-effect waves-light" title="edit" data-bs-toggle="modal" data-bs-target="#containerModal">
                <i class="fas fa-pencil-alt" title="edit"></i>
                </button>';
                // $editAction ='';
                $hapusAction = '<button onClick="deleteData(' . "'$q->id'" . ')"  class="btn btn-danger btn-sm delete waves-effect waves-light" title="Delete">
                <i class="fas fa-trash" title="Delete"></i>
                </button>';
            }else{
                $editAction ='';
                $hapusAction='';
            }

            $action = '<span>'.$detail." ".$Aprroval." ".$rejected." " .$editAction." ". $hapusAction.'</span>' ;
            return $action;
        })
        ->editColumn('jumlah_cuti',function($q){
                return $q->jumlah_cuti. ' Hari';
        })
        ->editColumn('file',function($q){
            if($q->file){
           
                // $suratcuti = '<button onClick="downloadSurat(' . "'$q->file'" . ')"  class="btn btn-success btn-sm delete waves-effect waves-light" title="Download">
                // <i class="fas fa-download" title="Download"></i>
                //  </button>';
                //  $action = '<span>'.$suratcuti.'</span>';
                // return $action;
                $path = "storage/Cuti/".$q->file;
                $btnPreview = '<a onClick="preview('."'$path'" .')" href="#"><i class="fas fa-link" title="Preview"></i> Preview</a>';
                $action = '<span>'.$btnPreview.'</span>';
                return $action;
            }else{
                return '-';
            }
        })
        ->editColumn('created_at',function($q){
            return date('d-m-Y H:i:s',strtotime($q->created_at));
        })

        ->rawColumns(['aksi','file'])
        ->make(true);
    }
    public function getJenisCuti()
    {
        $jenis = DB::table('cuti.jenis_cuti')->select(['id','jenis_cuti as text'])->get();
        return response()->json($jenis); 
    }
    public function SimpanCuti(Request $r){
        $kd_karyawan = Auth::user()->kd_karyawan;
        $kd_departement = Karyawan::where('kd_karyawan',$kd_karyawan)->select("kd_departement","nama_lengkap","tanggal_bergabung")->first();

        $tanggal_masuk = Carbon::parse($kd_departement->tanggal_bergabung);
        $tanggal_saat_ini = Carbon::now();

        if ($tanggal_masuk->diffInYears($tanggal_saat_ini) < 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengajuan cuti hanya bisa dilakukan setelah melewati masa kerja minimal 1 tahun.'
            ]);
        }

        $status_approval='Pending';

        $jumlah_cuti=null;
        $validasicutitahunan=true;
        $validasicutihamil=true;
        $validasicutinikah=true;
        $cek=0;

        $tanggal_cuti=$r->tanggal_cuti ?? null;
        $tanggal_cuti_melahirkan = $r->tanggal_cuti_range;

        if($tanggal_cuti){
            $tanggalcuti = explode(", ", $tanggal_cuti);
            $jumlah_cuti = count($tanggalcuti);
            if($r->id_cuti=='3'){
                $validasicutinikah= $jumlah_cuti > 3 ? false :true; 
            }else if($r->id_cuti=='1'){
                $validasicutitahunan= $jumlah_cuti > 5 ? false :true;
            }
            $cek = $this->hitungSisaCuti($kd_karyawan, $tanggalcuti);
        }  

        if($tanggal_cuti_melahirkan){
            [$awal, $akhir] = explode(' to ', $tanggal_cuti_melahirkan);

            $tanggal_mulai = Carbon::createFromFormat('d-m-Y', trim($awal));
            $tanggal_akhir = Carbon::createFromFormat('d-m-Y', trim($akhir));
        
            $arrayCuti = [];
            while ($tanggal_mulai->lte($tanggal_akhir)) {
                $arrayCuti[] = $tanggal_mulai->format('d-m-Y');
                $tanggal_mulai->addDay();
            }
            $jumlah_cuti = count($arrayCuti);
            $validasicutihamil= $jumlah_cuti > 60 ? false :true ;
        }
        // return $cek;
 
        if ($cek <= 10 && $validasicutitahunan && $validasicutinikah && $validasicutihamil) {
            try {
                $rules = [
                    // 'alasan'=>'required',
                    'perihal_cuti'=>'required',
                    'id_cuti'=>'required'
                ];
    
                if($r->id_cuti == "1" || $r->id_cuti == "3" ){
                    $rules['tanggal_cuti'] = 'required';    
                }else if($r->id_cuti == "4"){
                    $rules['tanggal_cuti'] = 'required';
                    $rules['upload_surat'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240';
                }else{
                    $rules['tanggal_cuti_range'] = 'required';
                }
    
                $message = [
                    // "alasan.required"=>"Alasan Wajib diisi",
                    "perihal_cuti.required"=>"Perihal Cuti Wajib Diisi",
                    "id_cuti.required"=>"Jenis Cuti Wajib Diisi",
                    "upload_surat.required"=>"Surat Sakit Wajib Di Upload",
                    "upload_surat.max"=>"Foto Max Size Upload 10 MB.",
                    "upload_surat.mimes"=>"Foto diupload dengan extension .jpeg .png .jpg .gif .svg",
                    "tanggal_cuti.required"=>"Tanggal Cuti Wajib Diisi",
                    "tanggal_cuti_range.required"=>"Tanggal Cuti Wajib Diisi",
                ];
                $validator = Validator::make($r->all(), $rules,$message);
                if ($validator->fails()) {
                    return response()->json($validator->errors()->first(),422);
                }
                if($r->tipe_submit == "add"){
                    $fileName=null;
                    if($r->hasFile('upload_surat')){
                        $files=$r->file('upload_surat');
                        $fileName=date('YmdHis'). "." .$files->getClientOriginalExtension();
                        $path = Storage::put('Cuti'.'/'.$fileName,file_get_contents($files));
                        if(!$path){
                            $fileName = null;
                        }
                        $status_approval='Diterima';
                    }

                    $save = Cuti::insert([
                        "kd_karyawan"=>$kd_karyawan,
                        "kd_departement"=>$kd_departement->kd_departement,
                        "id_jenis_cuti"=>$r->id_cuti,
                        "alasan"=>$r->alasan ?? null,
                        "tgl_cuti"=>$tanggal_cuti ? $tanggal_cuti :$tanggal_cuti_melahirkan,
                        "status_approval"=>$status_approval,
                        "created_at"=>date('Y-m-d H:i:s'),
                        "updated_at"=>date('Y-m-d H:i:s'),
                        "jumlah_cuti"=>$jumlah_cuti,
                        "perihal_cuti"=>$r->perihal_cuti,
                        "file"=>$fileName,
                        // "tgl_cuti_original"=>$stringArrayCuti,
                    ]);
                    

                    $jumlahcuti_hari=$jumlah_cuti .' Hari';
                    $tanggal = date("d-m-Y H:i:s");

                    if($r->id_cuti=='4'){

$pesan = 
"Salam Satu HATI

Info Izin Sakit
Nama Karyawan : %s
Tanggal Izin Sakit : %s
Jumlah Hari Sakit : %s

Informasi Lebih Lanjut bisa Kunjungi Link Berikut
https://digiment.menara-agung.com
                                    
Terima kasih";

        
        $formatPesan = sprintf($pesan,$kd_departement->nama_lengkap,$tanggal_cuti,$jumlahcuti_hari);

        $dataKaryawan = Karyawan::join('public.users','users.kd_karyawan','karyawan.kd_karyawan')
        ->Where('users.is_admin', 't')
        ->select("karyawan.no_hp")
        ->get();

        $kirimpesan = [];

        foreach ($dataKaryawan as $karyawan) {
            if ($karyawan->no_hp != "" && $karyawan->no_hp != null) {
                $kirimpesan[] = [
                    "no_hp"=>$karyawan->no_hp,
                    "kode_dealer"=>"C10",
                    "module"=>null,
                    "created_at"=>$tanggal,
                    "updated_at"=>$tanggal,
                    "jenis_msg"=>"Text",
                    "message"=>$formatPesan,
                    "is_proses"=>true,
                    "status"=>"9",
                    "keterangan"=>"Notifikasi Izin Sakit"
                ];
            }
        }

        if (count($kirimpesan) > 0) {
            WaMsgTmp::insert($kirimpesan);
        }

            }
            else{

$pesan = 
"Salam Satu HATI

Info Cuti Masuk
Nama Karyawan : %s
Tanggal Pengajuan : %s
Jumlah Cuti : %s

Mohon Untuk Dapat Dilakukan Peninjauan Cuti Pada Web Digiment
https://digiment.menara-agung.com
                                    
Terima kasih";

            $formatPesan = sprintf($pesan,$kd_departement->nama_lengkap,$tanggal,$jumlahcuti_hari);

            $dataKaryawan = Karyawan::join('public.jabatan','jabatan.kd_jabatan','karyawan.kd_jabatan')
            ->where('jabatan.flag_approval','t')
            ->where('jabatan.kd_departement',$kd_departement->kd_departement)
            ->select("karyawan.no_hp")
            ->first();

            if($dataKaryawan->no_hp != "" && $dataKaryawan->no_hp != null){
                $insert = WaMsgTmp::insert([
                    "no_hp"=>$dataKaryawan->no_hp,
                    "kode_dealer"=>"C10",
                    "module"=>null,
                    "created_at"=>$tanggal,
                    "updated_at"=>$tanggal,
                    "jenis_msg"=>"Text",
                    "message"=>$formatPesan,
                    "is_proses"=>true,
                    "status"=>"9",
                    "keterangan"=>"Notifikasi Cuti"
                ]);
            }
            }         

                }else{
                    $cuti = Cuti::find($r->id_data);
                    $cuti->id_jenis_cuti = $r->id_cuti;
                    $cuti->alasan = $r->alasan ?? null;
                    $cuti->tgl_cuti = $tanggal_cuti ? $tanggal_cuti :$tanggal_cuti_melahirkan;
                    $cuti->jumlah_cuti =$jumlah_cuti;
                    $cuti->perihal_cuti = $r->perihal_cuti;
                    $cuti->updated_at = date('Y-m-d H:i:s');
                    // $cuti->tgl_cuti_original = $stringArrayCuti;
                    if($r->hasFile('upload_surat')){
                        $foto_lama = $cuti->file;
                        $files=$r->file('upload_surat');
                        $fileName=date('YmdHis'). "." .$files->getClientOriginalExtension();
                        $path = Storage::put('Cuti'.'/'.$fileName,file_get_contents($files));
                        if($path){
                            $cuti->file = $fileName;
                            if($foto_lama != null){
                                // hapus foto setelah edit
                                Storage::delete('Cuti'."/".$foto_lama);
                            }
                        }
                    }
                    $save = $cuti->save();
                }
                if($save){
                    return response()->json([
                        "code"=>200,
                        "status"=>"true",
                        "message"=>"Sukses",
                        "id_cuti"=>$r->id_cuti
                    ]);
                }else{
                    return response()->json([
                        "code"=>400,
                        "status"=>"false",
                        "message"=>"Failed",
                    ]);
                }
            } catch (\Exception $th) {
                return response()->json([$th->getMessage()],500);
            }
        }else if(!$validasicutihamil){
            return response()->json([
                'status' => 'gagal',
                'message' => 'Gagal Ambil Cuti,Maks Ambil Cuti Melahirkan adalah 60 hari.'
            ]);  
        }
        else if(!$validasicutinikah){
            return response()->json([
                'status' => 'gagal',
                'message' => 'Gagal Ambil Cuti,Maks Ambil Cuti Nikah adalah 3 hari.'
            ]);  
        }
        
        else{
            return response()->json([
                'status' => 'gagal',
                'message' => 'Gagal Ambil Cuti,Maks Ambil Cuti per pengajuan adalah 5, dan tidak melebihi batas kuota sisa Cuti.'
            ]);
        }        
    }

    public function simpanpotongcuti(Request $r)
    {
        $kd_karyawan = $r->kd_karyawan;
        // $tanggalcuti=$r->tanggalpotong;
        $tanggalcuti = explode(", ", $r->tanggalpotong);
        $jumlah_cuti = count($tanggalcuti);
        $kd_departement = Karyawan::where('kd_karyawan',$kd_karyawan)->select("kd_departement")->first();
        $status_approval='Diterima';
        $pengaprove = Auth::user()->name;
  
        $cek = $this->hitungSisaCuti($kd_karyawan, $tanggalcuti);
        // return $cek;

        if ($cek <= 10) {
            try {
                $rules = [
                    'kd_karyawan'=>'required',
                    'alasan_potong'=>'required',
                    'tanggalpotong'=>'required',
                ];
        
                $message = [
                    "kd_karyawan.required"=>"Nama Karyawan Wajib diisi",
                    "alasan_potong.required"=>"Alasan Potong Cuti Wajib Diisi",
                    "tanggalpotong.required"=>"Tanggal Potong Cuti Wajib Diisi",
                ];

                $validator = Validator::make($r->all(), $rules,$message);
                if ($validator->fails()) {
                    return response()->json($validator->errors()->first(),422);
                }

                    $save = Cuti::insert([
                        "kd_karyawan"=>$kd_karyawan,
                        "kd_departement"=>$kd_departement->kd_departement,
                        "id_jenis_cuti"=>'1',
                        "alasan"=>$r->alasan_potong,
                        "tgl_cuti"=>$r->tanggalpotong,
                        "status_approval"=>$status_approval,
                        "created_at"=>date('Y-m-d H:i:s'),
                        "updated_at"=>date('Y-m-d H:i:s'),
                        "jumlah_cuti"=>$jumlah_cuti,
                        "perihal_cuti"=>'Potong Cuti',
                        "nama_approval"=>$pengaprove,
                        "tgl_approval"=>date('Y-m-d H:i:s'),
    
                    ]);
                
                if($save){
                    return response()->json([
                        "code"=>200,
                        "status"=>"true",
                        "message"=>"Sukses",
                    ]);
                }else{
                    return response()->json([
                        "code"=>400,
                        "status"=>"false",
                        "message"=>"Failed",
                    ]);
                }
            } catch (\Exception $th) {
                return response()->json([$th->getMessage()],500);
            }
        }else{
            return response()->json([
                'status' => 'gagal',
                'message' => 'Gagal Potong Cuti, yang bersangkutan sudah melebihi batas kuota pengambilan Cuti.'
            ]);
        }   
    }

    public function getCutiDetail(Request $r)
    {   
        $id_data=$r->id;
        $data=Cuti::select('jenis_cuti.jenis_cuti','karyawan.nama_lengkap',
        'departement.deskripsi',
        DB::raw("TO_CHAR(tbl_izin.created_at, 'DD-MM-YYYY HH24:MI:SS')"),
        // 'tbl_izin.created_at',
        'tbl_izin.tgl_cuti',
        // 'tbl_izin.jumlah_cuti',
        DB::raw("CONCAT(tbl_izin.jumlah_cuti, ' Hari') AS jumlah_cuti"),
        'tbl_izin.alasan',
        'tbl_izin.perihal_cuti',
        'tbl_izin.status_approval',
        DB::raw("COALESCE(tbl_izin.nama_approval, '-') AS nama_approval"),
        DB::raw("COALESCE(tbl_izin.tgl_approval, null) AS tgl_approval"),
        // 'tbl_izin.tgl_approval',
        'tbl_izin.file',
        'tbl_izin.id')
        ->where('tbl_izin.id', $id_data)
        ->leftjoin('public.karyawan','karyawan.kd_karyawan','tbl_izin.kd_karyawan')
        ->leftjoin('public.departement','departement.kd_departement','tbl_izin.kd_departement')
        ->leftjoin('cuti.jenis_cuti','jenis_cuti.id','tbl_izin.id_jenis_cuti')
        ->get();

        return response()->json($data);
    }

    public function editCuti(Request $r)
    {
        $id_data=$r->id;
        $data=Cuti::select(
        'tbl_izin.id_jenis_cuti',
        'tbl_izin.tgl_cuti',
        'tbl_izin.alasan',
        'tbl_izin.perihal_cuti',
        'tbl_izin.file',
        'tbl_izin.tgl_cuti_original'
        )
        ->where('tbl_izin.id', $id_data)
        ->first();

        return response()->json($data);
    }

    function DeleteCuti(Request $r)
    {
        try {
            DB::beginTransaction();
            $id = $r->id;
            $delete = Cuti::find($id);

            $foto_cuti = $delete->file;
            if($foto_cuti){
                Storage::delete('Cuti'."/".$foto_cuti);
            }

            $deleteCuti =$delete->update([
                "status_approval"=>'Dibatalkan',
                "file"=>null
            ]);

            // $deleteCuti = $delete->delete();
     
            if($deleteCuti){
                DB::commit();
                return response()->json([
                    "code"=>200,
                    "status"=>"true",
                    "message"=>"Sukses Batalkan Cuti",
                ]);
            }else{
                DB::rollBack();
                return response()->json([
                    "code"=>400,
                    "status"=>"false",
                    "message"=>"Gagal Batalkan Cuti",
                ]);
            }
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([$th->getMessage()],500);
        }
    }


    private function hitungSisaCuti($kd_karyawan, $tanggalcuti)
    {
       
        $cuti_sudah_ambil = Cuti::where('kd_karyawan', $kd_karyawan)
        ->where('id_jenis_cuti','1')
        ->where('status_approval','Diterima')
        ->sum('jumlah_cuti'); 

        $cuti_baru=count($tanggalcuti);

        $total_setelah_pengajuan = $cuti_sudah_ambil + $cuti_baru;

        return $total_setelah_pengajuan;
    }

    public function getCutiSaldo(Request $r)
    {
        $user = auth()->user()->kd_karyawan;
        $tahunSekarang=date('Y');

        $cuti_sudah_ambil = Cuti::where('kd_karyawan', $user)
        ->where('status_approval','Diterima')
        ->whereYear('created_at', $tahunSekarang)
        ->sum('jumlah_cuti'); 
        
        return response()->json($data);
    }


    public function RejectCuti(Request $r)
    {
        $kd_karyawan = Auth::user()->name;
        $tanggal_sekarang = date('Y-m-d H:i:s');

        // $dataanggota=Cuti::find($r->id);
        $dataanggota=Cuti::select('tbl_izin.kd_karyawan','tbl_izin.jumlah_cuti','karyawan.nama_lengkap')
        ->join('public.karyawan','karyawan.kd_karyawan','tbl_izin.kd_karyawan')
        ->where('tbl_izin.id',$r->id)
        ->first();

        $anggota=$dataanggota->kd_karyawan;
        $nama_lengkap=$dataanggota->nama_lengkap;
        $jumlah_cuti=$dataanggota->jumlah_cuti . ' Hari';

        try {
            DB::beginTransaction();
            // $id = $r->id;
            // $save = $dataanggota->update([
            //     "status_approval"=>'Ditolak',
            //     "nama_approval"=>$kd_karyawan,
            //     "tgl_approval"=>$tanggal_sekarang
            // ]);
            $save = Cuti::where('id',$r->id)->update([
                "status_approval"=>'Ditolak',
                "nama_approval"=>$kd_karyawan,
                "tgl_approval"=>$tanggal_sekarang
            ]);

            $pesan = 
"Salam Satu HATI
Info Pengajuan Cuti

Nama Karyawan : %s
Jumlah Cuti : %s
Status Approval : %s
DiTolak Oleh : %s
Tanggal : %s
                                    
Terima kasih";

                    $tanggal = date("d-m-Y H:i:s");
                    $formatPesan = sprintf($pesan,$nama_lengkap,$jumlah_cuti,'Ditolak',$kd_karyawan,$tanggal);

                    $dataKaryawan = Karyawan::where('karyawan.kd_karyawan',$anggota)
                    ->select("karyawan.no_hp")
                    ->first();
                  
                    if($dataKaryawan->no_hp != "" && $dataKaryawan->no_hp != null){
                        $insert = WaMsgTmp::insert([
                            "no_hp"=>$dataKaryawan->no_hp,
                            "kode_dealer"=>"C10",
                            "module"=>null,
                            "created_at"=>$tanggal,
                            "updated_at"=>$tanggal,
                            "jenis_msg"=>"Text",
                            "message"=>$formatPesan,
                            "is_proses"=>true,
                            "status"=>"9",
                            "keterangan"=>"Notifikasi Cuti"
                        ]);
                    }


            if($save){
                DB::commit();
                return response()->json([
                    "code"=>200,
                    "status"=>"true",
                    "message"=>"Sukses Reject Cuti",
                ]);
            }else{
                DB::rollBack();
                return response()->json([
                    "code"=>400,
                    "status"=>"false",
                    "message"=>"Gagal Reject Cuti",
                ]);
            }
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([$th->getMessage()],500);
        }

    }

    public function ApproveCuti(Request $r)
    {
        // return $r->all();
        $kd_karyawan = Auth::user()->name;
        $tanggal_sekarang = date('Y-m-d H:i:s');
        $cek=0;

        // $dataanggota=Cuti::find($r->id);
        $dataanggota=Cuti::select('tbl_izin.kd_karyawan','tbl_izin.id_jenis_cuti','tbl_izin.tgl_cuti','tbl_izin.jumlah_cuti','karyawan.nama_lengkap')
        ->join('public.karyawan','karyawan.kd_karyawan','tbl_izin.kd_karyawan')
        ->where('tbl_izin.id',$r->id)
        ->first();

        $anggota=$dataanggota->kd_karyawan;
        $nama_lengkap=$dataanggota->nama_lengkap;
        $jumlah_cuti=$dataanggota->jumlah_cuti . ' Hari';

        $tanggalcuti = explode(", ", $dataanggota->tgl_cuti);
        // return $dataanggota->kd_karyawan;
       
        if($dataanggota->id_jenis_cuti=='1'){
            $cek = $this->hitungSisaCuti($anggota, $tanggalcuti);
        }
        // return $cek;

        if ($cek <= 10) {
        try {
            DB::beginTransaction();
            $id = $r->id;
            // $save = $dataanggota->update([
            //     "status_approval"=>'Diterima',
            //     "nama_approval"=>$kd_karyawan,
            //     "tgl_approval"=>$tanggal_sekarang
            // ]);
            $save = Cuti::where('id',$r->id)->update([
                "status_approval"=>'Diterima',
                "nama_approval"=>$kd_karyawan,
                "tgl_approval"=>$tanggal_sekarang
            ]);

            $pesan = 
"Salam Satu HATI
Info Pengajuan Cuti

Nama Karyawan : %s
Jumlah Cuti : %s
Status Approval : %s
Disetujui Oleh : %s
Tanggal Approval : %s
                                    
Terima kasih";

                    $tanggal = date("d-m-Y H:i:s");
                    $formatPesan = sprintf($pesan,$nama_lengkap,$jumlah_cuti,'Diterima',$kd_karyawan,$tanggal);

                    $dataKaryawan = Karyawan::join('public.users','users.kd_karyawan','karyawan.kd_karyawan')
                    ->where(function($query) use ($anggota) {
                        $query->where('karyawan.kd_karyawan', $anggota)
                            ->orWhere('users.is_admin', 't');
                    })
                    ->select("karyawan.no_hp")
                    ->get();

                    // return $dataKaryawan;
                  
                    // if($dataKaryawan->no_hp != "" && $dataKaryawan->no_hp != null){
                    //     $insert = WaMsgTmp::insert([
                    //         "no_hp"=>$dataKaryawan->no_hp,
                    //         "kode_dealer"=>"C10",
                    //         "module"=>null,
                    //         "created_at"=>$tanggal,
                    //         "updated_at"=>$tanggal,
                    //         "jenis_msg"=>"Text",
                    //         "message"=>$formatPesan,
                    //         "is_proses"=>true,
                    //         "status"=>"9",
                    //         "keterangan"=>"Notifikasi Cuti"
                    //     ]);
                    // }

                    $kirimpesan = [];

                    foreach ($dataKaryawan as $karyawan) {
                        if ($karyawan->no_hp != "" && $karyawan->no_hp != null) {
                            $kirimpesan[] = [
                            "no_hp" => $karyawan->no_hp,
                            "kode_dealer"=>"C10",
                            "module"=>null,
                            "created_at"=>$tanggal,
                            "updated_at"=>$tanggal,
                            "jenis_msg"=>"Text",
                            "message"=>$formatPesan,
                            "is_proses"=>true,
                            "status"=>"9",
                            "keterangan"=>"Notifikasi Cuti"
                            ];
                        }
                    }

                    if (count($kirimpesan) > 0) {
                        WaMsgTmp::insert($kirimpesan);
                    }



            if($save){
                DB::commit();
                return response()->json([
                    "code"=>200,
                    "status"=>"true",
                    "message"=>"Sukses Approval Cuti",
                ]);
            }else{
                DB::rollBack();
                return response()->json([
                    "code"=>400,
                    "status"=>"false",
                    "message"=>"Gagal Approval Cuti",
                ]);
            }
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([$th->getMessage()],500);
        }
    }else{
        return response()->json([
            'status' => 'gagal',
            'message' => 'Gagal Approve Cuti, yang bersangkutan sudah melebihi batas kuota pengambilan Cuti.'
        ]);
    } 

    }

    public function AmbilKaryawan(Request $r)
    {
        $kd_departement = $r->kd_departement;

        $karyawan = DB::table('karyawan')->select(['kd_karyawan as id','nama_lengkap as text'])
        // ->where('kd_departement', $kd_departement)
        ->when($kd_departement, function($query) use ($kd_departement) {
                $query->where('kd_departement', $kd_departement);
        })
        ->get();

        return response()->json($karyawan);
    }

    public function kalendercuti()
    {
        return view('pages.cuti.kalendercuti');
    }

    public function jadwalcuti(Request $r)
    {
        $departement=$r->departement;
        $karyawan=$r->karyawan;
        // $tahun=$r->tahun ?? date('Y');

        // return $departement;
        $kd_karyawan = Auth::user()->kd_karyawan;
        $isAdmin = Auth::user()->is_admin;
        $year = date('Y');
        $tanggal_sekarang = date('Y-m-d');

        $pengaprove=Karyawan::join('public.jabatan','jabatan.kd_jabatan','karyawan.kd_jabatan')
        ->select('jabatan.flag_approval','karyawan.kd_departement')
        ->where('karyawan.kd_karyawan', $kd_karyawan)
        ->first();
    
        $data = Cuti::when($isAdmin == "f", function ($q) use ($pengaprove,$kd_karyawan) {
                // $q->where('tbl_izin.kd_karyawan', $kd_karyawan);
                if($pengaprove->flag_approval){
                    return $q->where('tbl_izin.kd_departement', $pengaprove->kd_departement);
                }else{
                    return $q->where('tbl_izin.kd_karyawan', $kd_karyawan); 
                }
            })
            ->when($isAdmin=='t', function($query) use ($departement, $karyawan) {
                if($departement){
                    $query->where('tbl_izin.kd_departement', $departement);
                }
                if ($karyawan) {
                    $query->where('tbl_izin.kd_karyawan', $karyawan);
                }
            })
            ->whereRaw("tbl_izin.tgl_cuti LIKE '%$year%'")
            ->where('tbl_izin.status_approval','Diterima')
            ->leftJoin('cuti.jenis_cuti', 'jenis_cuti.id', 'tbl_izin.id_jenis_cuti')
            ->leftJoin('public.karyawan', 'karyawan.kd_karyawan', 'tbl_izin.kd_karyawan')
            ->select("jenis_cuti.jenis_cuti", "tbl_izin.tgl_cuti", "karyawan.nama_panggilan", "tbl_izin.status_approval","tbl_izin.perihal_cuti")
            ->get();
    
        $dataCollect = collect($data)->map(function ($q) use ($tanggal_sekarang) {

            $tanggal_cuti_array = explode(", ", $q->tgl_cuti);
            $tanggal_cuti_array = array_map(function($tanggal) {
                return date('Y-m-d', strtotime($tanggal));
            }, $tanggal_cuti_array);

    
            $className = "bg-success"; 
            if ($tanggal_sekarang > max($tanggal_cuti_array)) {
                $className = "bg-danger"; 
            }
    
            return [
                "jenis_cuti" => $q->jenis_cuti,
                "tanggal_cuti" => $tanggal_cuti_array,
                "nama_karyawan" => $q->nama_panggilan,
                "className" => $className,
                "perihal_cuti" => $q->perihal_cuti,
            ];
        });
    
        return response()->json($dataCollect);
    }

    public function potongcuti()
    {
        return view('pages.cuti.potongcuti');  
    }
  
}
