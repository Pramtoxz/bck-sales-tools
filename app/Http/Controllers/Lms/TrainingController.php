<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\Materi;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helper\Helper;
use App\Models\ActivityTraining;
use App\Models\EventTraining;
use App\Models\FeedbackTraining;
use App\Models\JenisTraining;
use App\Models\PesertaTraining;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Mockery\Undefined;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TemplateSoalExport;
use App\Imports\ImportSoal;
use App\Models\GroupSoal;
use Illuminate\Support\Str;
use App\Models\Soal;
use App\Models\PesertaTest;
use App\Models\JawabanSoal;
use PDF;

class TrainingController extends Controller
{
    public function index()
    {
        return view('pages.lms.training.index');
    }

    public function get(Request $r){
        $data = Training::join('lms.jenis_training', 'lms.training.kd_jenis_training', '=', 'lms.jenis_training.kd_jenis_training')
        ->select('training.id','training.kd_training','training.nama_training', 'jenis_training.nama_jenis','training.training_tag','training.deskripsi','training.active','training.document_pre_test','training.document_post_test')->orderBy('training.created_at','DESC')->withCount('trainingEvent');
 
        return DataTables::of($data)
        ->addColumn('aksi',function($q){
            $deleteAction = "";
            $tambahAction = '<button onClick="tambahMateri2(' . "'$q->kd_training'" . ')" class="btn btn-primary btn-sm edit waves-effect waves-light" title="Tambah" data-bs-toggle="modal" data-bs-target="#containerModalTambah">
                <i class="fas fa-folder" title="Tambah"></i> Materi
            </button>';

            $editAction = '<button onClick="editData(' . "'$q->id'" . ')" class="btn btn-warning btn-sm edit waves-effect waves-light" title="Edit" data-bs-toggle="modal" data-bs-target="#containerModal">
                <i class="fas fa-pencil-alt" title="Edit"></i>
            </button>';

            $tambahSoal = '<button onClick="tambahSoal2(' . "'$q->kd_training'" . ')" class="btn btn-success btn-sm edit waves-effect waves-light" title="Soal" data-bs-toggle="modal" data-bs-target="#containerModalSoal">
            <i class="fas fa-question" title="Soal"></i> Soal
            </button>';

            if($q->training_event_count == 0){
                $deleteAction = '<button onClick="deleteData(' . "'$q->id'" . ')"  class="btn btn-danger btn-sm delete waves-effect waves-light" title="Delete">
                    <i class="fas fa-trash" title="Delete"></i>
                </button>';
            }
            $action = '<span>'.$tambahAction." ".$editAction." ".$deleteAction. " " .$tambahSoal.'</span>';
            return $action;
        })
        ->editColumn('active',function($q){
            if($q->active == "t"){
                return "Yes";
            }else{
                return "No";
            }
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

   

    public function save(Request $r){
        $kodeTraining = Helper::getKodeUniqueId("TR");
        try {
            $rules = [
                'kd_jenis_training' => 'required',
                'nama_training' => 'required',
                'training_tag'=>'required',
                'active' => 'required',
            ];
            if($r->tipe_submit == "add"){
                $rules['avatar_training'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240';
            }else{
                $rules['avatar_training'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:10240';
            }
            $message = [
                "kd_jenis_training.required"=> "Jenis Training Wajib Diisi",
                "nama_training.required"=> "Nama Training Wajib Diisi",
                "avatar_training.mimes"=>"Foto diupload dengan extension .jpeg .png .jpg .gif .svg",
                "avatar_training.max"=>"Foto Max Size Upload 10 MB",
                "avatar_training.required"=>"Foto Training Wajib diupload",
                "training_tag.required"=>"Tag Training Wajib Diisi",
                "active.required"=>"Active Wajib Diisi",
            ];
            $validator = Validator::make($r->all(), $rules,$message);
            if ($validator->fails()) {
                return response()->json($validator->errors()->first(),422);
            }
            DB::beginTransaction();
            if($r->tipe_submit == "add"){
                $avaTraining=null;
                $docPreTest=null;
                $docPostTest=null;
                // $materi=null;
                if($r->hasFile('avatar_training')){
                    $files=$r->file('avatar_training');
                    $avaTraining=date('YmdHis'). "." .$files->getClientOriginalExtension();
                    $path = Storage::put('gambar_training'.'/'.$avaTraining,file_get_contents($files));
                    if(!$path){
                        $avaTraining = null;
                    }
                }

                $save = Training::insert([
                    "kd_training"=>$kodeTraining,
                    "nama_training"=>$r->nama_training,
                    "kd_jenis_training"=>$r->kd_jenis_training,
                    "training_tag"=>implode(",",$r->training_tag),
                    "deskripsi"=>$r->deskripsi,
                    "avatar_training"=>$avaTraining,
                    "active"=>$r->active,
                    "created_at"=>date('Y-m-d H:i:s'),
                    "updated_at"=>date('Y-m-d H:i:s')
                ]);
                if(!$save){
                    throw new \Exception("Gagal Simpan Data Training",1);
                }
            }
            else{
                $training = Training::find($r->id_data);
                $training->nama_training = $r->nama_training;
                $training->kd_jenis_training = $r->kd_jenis_training;
                $training->training_tag = implode(",",$r->training_tag);
                $training->deskripsi = $r->deskripsi;
                $training->active = $r->active;
                if($r->hasFile('avatar_training')){
                    $files=$r->file('avatar_training');
                    $avaTraining=date('YmdHis'). "." .$files->getClientOriginalExtension();
                    $path = Storage::put('gambar_training'.'/'.$avaTraining,file_get_contents($files));
                    if($path){
                        $avaTrainingLama = $training->avatar_training;
                        if($avaTrainingLama != null){
                            // hapus ava training setelah edit
                            Storage::delete('gambar_training'."/".$avaTrainingLama);
                        }
                        $training->avatar_training = $avaTraining;
                    }
                }
                $save = $training->save();
            }
            if($save){
                DB::commit();
                return response()->json([
                    "code"=>200,
                    "status"=>"true",
                    "message"=>"Sukses",
                ]);
            }else{
                DB::rollBack();
                return response()->json([
                    "code"=>400,
                    "status"=>"false",
                    "message"=>"Failed",
                ]);
            }
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([$th->getMessage()],500);
        }
        
    }

    public function saveMateri(Request $r){
        // return $r->all();
        $rules = [
            'tipe_materi' => 'required',
            'jenis_materi' => 'required',
            'materi_file' => 'file|mimes:pdf,docx,doc'
        ];
        $message = [
            "tipe_materi.required"=> "Tipe Materi Wajib Diisi",
            "jenis_materi.required"=> "Jenis Materi Wajib Diisi",
            "materi_file.mimes" => "Extension yang diizinkan : pdf,docx,doc",
            "materi_file.file" => "Harus Berupa File"
        ];

        $validator = Validator::make($r->all(), $rules,$message);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),422);
        }
        try {
            $filename=null;    
            $link=null;    
            DB::beginTransaction();
                if($r->jenis_materi == "file"){
                    if($r->hasFile('materi_file')){
                        $files=$r->file('materi_file');
                        $filename=date('YmdHis'). "." .$files->getClientOriginalExtension();
                        $path = Storage::put('materi'.'/'.$filename,file_get_contents($files));
                        if(!$path){
                            $filename = null;
                        }
                    }else{
                        throw new \Exception("Tidak Ada File Yang Diupload",1);
                    }
                }else{
                    // jenis materi link
                    if($r->materi_link == ""){
                        throw new \Exception("Tidak Ada Link Yang Dimasukan",1);
                    }else{
                        $link = $r->materi_link;
                    }

                }
                
                // return $materi;
                $save = Materi::insert([
                    "jenis_penggunaan"=>"Training",
                    "kd_training"=>$r->kd_training,
                    "tipe_materi"=>$r->tipe_materi,
                    "link"=>$link,
                    "filename"=>$filename,
                    "jenis_materi"=>$r->jenis_materi,
                    "active"=>"t",
                    "created_at"=>date('Y-m-d H:i:s')
                    // "updated_at"=>date('Y-m-d H:i:s')
                ]);
                if(!$save){
                    throw new \Exception("Gagal Simpan Data Materi",1);
                }
             DB::commit(); 
             return response()->json([
                "code"=>200,
                "status"=>"true",
                "message"=>"Sukses Upload Materi",
            ]); 
        } 
        catch (\Exception $th) {
            DB::rollBack();
            return response()->json([$th->getMessage()],500);
        }
        
    }

    public function delete(Request $r){
        $id = $r->id;
        $training = Training::find($id);
        // hapus document training
            Storage::delete('gambar_training'."/".$training->avatar_training);
        $delete = $training->delete();
        if($delete){
            return response()->json([
                "code"=>200,
                "status"=>"true",
                "message"=>"Sukses Hapus Data",
            ]);
        }else{
            return response()->json([
                "code"=>400,
                "status"=>"false",
                "message"=>"Gagal Hapus Data",
            ]);
        }
    }

    public function deleteMateri(Request $r){
        $id = $r->id;
        $delete = Materi::find($id);
            Storage::delete('materi'."/".$delete->filename);
        $delete = $delete->delete();
     
        // $delete = Training::find($id)->delete();
        if($delete){
            return response()->json([
                "code"=>200,
                "status"=>"true",
                "message"=>"Sukses Hapus Data",
            ]);
        }else{
            return response()->json([
                "code"=>400,
                "status"=>"false",
                "message"=>"Gagal Hapus Data",
            ]);
        }
    }

    public function show(Request $r){
        $id = $r->id;
        return response()->json(Training::find($id));
    }

    public function all(){
        $training = DB::table('lms.training')->select(['kd_training as id','nama_training as text'])->where('active','t')->get();
        return response()->json($training);     
    }

    public function matshow(Request $r)
    {
        $id = $r->kd_training;
        // $mattraining = Training::find($id);
        $selectMateri = Materi::with('training')->where('kd_training',$id)->get();
        // return $selectMateri;
        // return response()->json($selectMateri); 
        return DataTables::of($selectMateri)
        ->addColumn('aksi',function($q){
            $deleteAction = '<button onClick="btnHapusMateri(' . "'$q->id'" . ')"  class="btn btn-danger btn-sm delete waves-effect waves-light" title="Delete">
                <i class="fas fa-trash" title="Delete"></i>
            </button>';
            $action = '<span>'.$deleteAction.'</span>';
            return $action;
        })
        ->addColumn('preview',function($q){
            if($q->jenis_materi == "file"){
                $path = "storage/materi/".$q->filename;
                $btnPreview = '<a onClick="preview('."'$path'" .')" href="#"><i class="fas fa-link" title="Preview"></i> Preview</a>';
                $action = '<span>'.$btnPreview.'</span>';
                return $action;
            }else{
                $btnPreview = '<a href="'.$q->link.'" target="_blank"><i class="fas fa-link" title="Preview"></i> Preview</a>';
                $action = '<span>'.$btnPreview.'</span>';
                return $action;
            }
            
        })
        ->rawColumns(['aksi','preview'])
        ->make(true);
    }

    public function matshowUser(Request $r)
    {
        $id = $r->kd_training;
        $selectMateri = Materi::with('training')->where('kd_training',$id)->get();
        return DataTables::of($selectMateri)
        ->addColumn('preview',function($q){
            if($q->jenis_materi == "file"){
                $path = "storage/materi/".$q->filename;
                $btnPreview = '<a onClick="preview('."'$path'" .')" href="#"><i class="fas fa-link" title="Preview"></i> Preview</a>';
                $action = '<span>'.$btnPreview.'</span>';
                return $action;
            }else{
                $btnPreview = '<a href="'.$q->link.'" target="_blank"><i class="fas fa-link" title="Preview"></i> Preview</a>';
                $action = '<span>'.$btnPreview.'</span>';
                return $action;
            }
            
        })
        ->rawColumns(['preview'])
        ->make(true);
    }

    function getListTraining(Request $r)
    {
        $tanggalsekarang=date('Y-m-d');
        $kdkaryawan=$r->kode;
        if($kdkaryawan) {
            $search=$r->search;
            $jenis = $r->jenis;
            
            if($jenis != ""){
                $exJenis = explode(',',$jenis);
            }else{
                $exJenis = [];
            }
            $training = Training::join('lms.event_training', 'lms.training.kd_training', '=', 'lms.event_training.kd_training')
            ->leftjoin('lms.peserta_training', 'lms.event_training.kd_event_training', '=', 'lms.peserta_training.kd_event_training')
            ->where('active','t')
            ->where('peserta_training.kd_karyawan',$kdkaryawan)
            ->where('peserta_training.status', '<>', 'Selesai')
            ->where('event_training.tanggal_akhir', '>=',$tanggalsekarang)
            ->select("training.id","training.kd_training","training.kd_jenis_training","training.nama_training","training.training_tag","training.deskripsi","training.avatar_training","event_training.kd_event_training")
            ->when($jenis != "",function($q) use($exJenis){
                $q->whereIn('training.kd_jenis_training',$exJenis);
            })
            ->when($search!="",function($q) use($search){
                $q->where('training.nama_training','ilike', '%' . $search . '%');
            })
            ->with([
                'jenis_training', 
                'eventTraining', 
                'feedbackTraining' => function ($query) {
                    $query->select('kd_training', DB::raw('ROUND(AVG(rating),1) as ratarating'))
                        ->groupBy('kd_training'); 
                }
            ])
            ->get();
        }else{
            $search=$r->search;
            $jenis = $r->jenis;
            
            if($jenis != ""){
                $exJenis = explode(',',$jenis);
            }else{
                $exJenis = [];
            }
            // $training = Training::join('lms.event_training', 'lms.training.kd_training', '=', 'lms.event_training.kd_training')
            $training = Training::where('active','t')->select("training.id","training.kd_training","training.kd_jenis_training","training.nama_training","training.training_tag","training.deskripsi","training.avatar_training")
            ->when($jenis != "",function($q) use($exJenis){
                $q->whereIn('training.kd_jenis_training',$exJenis);
            })
            ->when($search!="",function($q) use($search){
                $q->where('training.nama_training','ilike', '%' . $search . '%');
            })
            ->with([
                'jenis_training', 
                'eventTraining', 
                'feedbackTraining' => function ($query) {
                    $query->select('kd_training', DB::raw('ROUND(AVG(rating),1) as ratarating'))
                        ->groupBy('kd_training'); 
                }
            ])
            ->get();
        }
        // ROUND(AVG(nilai_post_test), 2) AS avgposttest,
    
       
        // return $training;
       
        return response()->json($training);
    }

    function getEventTraining(Request $r)
    {
        $kd_event_training=$r->kd_event_training;
        $kodeTraining=$r->kd_training;
        $user = auth()->user()->kd_karyawan;
        
        $results = Training::leftjoin('lms.event_training', 'lms.training.kd_training', '=', 'lms.event_training.kd_training')
        ->where('lms.training.kd_training',$kodeTraining)
        ->select('training.kd_training', 'training.nama_training', 'training.deskripsi','training.avatar_training',
        'event_training.kd_event_training','event_training.tanggal_mulai','event_training.tanggal_akhir')
        ->with('feedbackTraining')
        ->with(['pesertaEventTraining' => function ($query) use ($kd_event_training) {
            $query->where('event_training.kd_event_training', $kd_event_training);
        }])
        ->orderBy('event_training.tanggal_mulai','desc')
        ->get();
        return response()->json($results);
    }

    function getPesertaTraining(Request $r){
        $kodeTraining=$r->kd_training;
        $user = auth()->user()->kd_karyawan;
        $results=PesertaTraining::join('lms.event_training','lms.peserta_training.kd_event_training','=','lms.event_training.kd_event_training')
        ->where('lms.event_training.kd_training',$kodeTraining)
        ->where('lms.peserta_training.kd_karyawan',$user)
        ->select('peserta_training.kd_karyawan','peserta_training.kd_event_training','peserta_training.status')
        ->orderBy('event_training.tanggal_mulai','desc')
        ->first();
        return response()->json($results);
    }

    function downloadPreTest($path){
        return Storage::download("doc_pre_test/{$path}");
    }

    function downloadPostTest($path){
        return Storage::download("doc_post_test/{$path}");
    }

    function getRiwayatTraining(Request $r){
        if($r->kd_karyawan){ 
            $results = Training::join('lms.event_training', 'lms.training.kd_training', '=', 'lms.event_training.kd_training')
             ->leftjoin('lms.peserta_training','lms.event_training.kd_event_training','=','lms.peserta_training.kd_event_training')
            ->where('lms.peserta_training.kd_karyawan',$r->kd_karyawan)
             ->select('training.kd_training', 'training.nama_training', 'training.deskripsi','training.avatar_training',
            'event_training.tanggal_mulai','event_training.tanggal_akhir','peserta_training.kd_karyawan','peserta_training.status','peserta_training.nilai_pre_test','peserta_training.nilai_post_test')
            ->orderBy('event_training.tanggal_mulai','desc')
             ->get();

            //  return $results;

            return DataTables::of($results)
            ->addColumn('Ket_pre_test',function($results){
                $nilai_pre_test=$results->nilai_pre_test;
                if($nilai_pre_test===null){
                    $ket='-';
                }else if($nilai_pre_test>70){
                    $ket='Lulus';
                }else{
                    $ket="Tidak Lulus";
                }
                return $ket;
            })
            ->addColumn('Ket_post_test',function($results){
                $nilai_post_test=$results->nilai_post_test;
                if($nilai_post_test===null){
                    $ket='-';
                }else if($nilai_post_test>70){
                    $ket='Lulus';
                }else{
                    $ket="Tidak Lulus";
                }
                return $ket;
            })
            ->make(true);
        }else{

             $user = auth()->user()->kd_karyawan;
             $results = Training::join('lms.event_training', 'lms.training.kd_training', '=', 'lms.event_training.kd_training')
        ->leftjoin('lms.peserta_training','lms.event_training.kd_event_training','=','lms.peserta_training.kd_event_training')
        ->where('lms.peserta_training.kd_karyawan',$user)
        ->select('training.kd_training', 'training.nama_training', 'training.deskripsi','training.avatar_training',
        'event_training.kd_event_training','event_training.tanggal_mulai','event_training.tanggal_akhir','peserta_training.status')
        ->orderBy('event_training.tanggal_mulai','desc')
        ->get();

            return response()->json($results);
        }
        // return $user;
        // return $r->kd_karyawan;
        
        
    }
    
    function detailTraining($kd_training,$event_training){
       $auth =  Auth::user()->unreadNotifications->where('id',request('id'))->first();
        if ($auth) {
            $auth->markAsRead();
        }
        return view('pages.lms.training.detail_training', ['kd_training' => $kd_training,'event_training' => $event_training]);
    }

    function detailTrainingUser(Request $r){
        // $kodeTraining=$r->kd_training;
        $kodeEventTraining=$r->kd_event_training;
        $user = auth()->user()->kd_karyawan;
        $results=JenisTraining::join('lms.training','lms.jenis_training.kd_jenis_training','=','lms.training.kd_jenis_training')
        ->leftjoin('lms.event_training','lms.training.kd_training','=','lms.event_training.kd_training')
        ->leftjoin('lms.peserta_training','lms.event_training.kd_event_training','=','lms.peserta_training.kd_event_training')
        ->where('lms.peserta_training.kd_karyawan',$user)
        ->where('lms.event_training.kd_event_training',$kodeEventTraining)
        ->select('jenis_training.nama_jenis','training.nama_training','training.deskripsi','event_training.tanggal_mulai',
        'event_training.tanggal_akhir','peserta_training.status','training.document_pre_test',
        'training.document_post_test')
        ->get();
        return response()->json($results);
    }

    function HistoryPesertaTraining(Request $r){
        $idPeserta=$r->idPeserta;
        $karyawan = PesertaTraining::where('id', $idPeserta)->first();
            if (!$karyawan->tanggal_mulai) {
                  PesertaTraining::where('id', $idPeserta)
                   ->update([
                    "tanggal_mulai"=>date('Y-m-d H:i:s'),
                    "status"=>"Proses"
                    ]);
            }
    }

    function HistoryActivityTraining(Request $r){
        $idPeserta=$r->idPeserta; 
        $idMateri=$r->idMateri;
        $keterangan=$r->keterangan;
        $karyawan = PesertaTraining::where('id', $idPeserta)->first();
        if ($karyawan) {
            $save = ActivityTraining::insert([
                "kd_karyawan"=>$karyawan->kd_karyawan,
                "kd_event_training"=>$karyawan->kd_event_training,
                "id_materi"=>$idMateri,
                "keterangan"=>$keterangan,
                "created_at"=>date('Y-m-d H:i:s')
            ]);
        }
    }

    public function historyDetail(Request $r){
        $kd_karyawan = Auth::user()->kd_karyawan;
        $kd_event_training = $r->kd_event_training;
        $data = ActivityTraining::where("kd_karyawan",$kd_karyawan)->where("kd_event_training",$kd_event_training)->orderBy('created_at','ASC')->get();
        $dataBaru = collect($data)->map(function($q){
            $q->tanggal = date('d/m/Y H:i:s',strtotime($q->created_at));
            return $q;
        });
        return response()->json($dataBaru);
    }

    public function TrainingTagAll(){
        $trainingTag = DB::table('lms.tag_training')->select(['name as id','name as text'])->get();
        return response()->json($trainingTag); 
    }

    public function downloadTemplate(){
        return Excel::download(new TemplateSoalExport, 'Template Upload Data Soal.xlsx');
    }

    public function previewSoal(Request $r){
        $kode_soal = $r->kode_soal;
        $dataGroupSoal = GroupSoal::where("group_soal.kode_soal",$kode_soal)->leftjoin("lms.training","training.kd_training","group_soal.kd_training")->select("group_soal.kode_soal","training.nama_training","group_soal.nama_soal","group_soal.created_at")->with('soalPreview')->first();

        $data = [
            'data' => $dataGroupSoal
        ];
              
        $pdf = PDF::loadView('pages.lms.report.cetak.preview_soal', $data);
       
        return $pdf->download('preview_soal.pdf');
    }

    public function saveBankSoal(Request $request){
        try {
            $rules = [
                'bank_soal' => 'required|mimes:xlsx,xls',
                'nama_soal' => 'required'
            ];
            $message = [
                "bank_soal.required"=> "File Tidak Boleh Kosong",
                "bank_soal.mimes"=> "File Hanya Boleh Format XLSX, XLS",
                "nama_soal.required"=> "Nama Soal Tidak Boleh Kosong",
            ];
            $validator = Validator::make($request->all(), $rules,$message);
            if ($validator->fails()) {
                return response()->json($validator->errors()->first(),422);
            }
            DB::beginTransaction();
            $data = Excel::toCollection(new ImportSoal, $request->file('bank_soal'));
            $sheet1 = $data[0];
            $dataLengkap = $sheet1->chunk(4);
            $tmp = [];
            $kodeSoal = Str::random(16);
            // key 1 A dan Soal Dan Jawab dan Nomor
            // key 2 B
            // key 3 C
            // key 4 D
            $bankSoal = [
                "kode_soal"=> $kodeSoal,
                "kd_training"=>$request->kd_training_soal,
                "created_at"=>date('Y-m-d H:i:s'),
                "updated_at"=>date('Y-m-d H:i:s')
            ];
            foreach($dataLengkap as $chunk){
                $keySoal = 0;
                foreach($chunk as $key => $value ){
                    if($keySoal == 0){
                        $bankSoal["no_soal"] = $value[0];
                        $bankSoal["text_soal"] = $value[1];
                        $bankSoal["kunci_jawaban"] = $value[4];
                        if($bankSoal["no_soal"] == "" || $bankSoal["text_soal"] == "" || $bankSoal["kunci_jawaban"]== ""){
                            throw new \Exception("Format Soal Tidak Sesuai dengan no soal, text soal dan kunci jawaban",1);
                        }
                    }
    
                    if($value[2] == "a"){
                        $bankSoal["opsi_a"] = $value[3];
                    }
    
                    if($value[2] == "b"){
                        $bankSoal["opsi_b"] = $value[3];
                    }
    
                    if($value[2] == "c"){
                        $bankSoal["opsi_c"] = $value[3];
                    }
    
                    if($value[2] == "d"){
                        $bankSoal["opsi_d"] = $value[3];
                    }
                    $keySoal +=1;
                }
                $tmp[] = $bankSoal;
            }
            $insertSoal = Soal::insert($tmp);

            $insertGroupSoal = GroupSoal::insert([
                "kode_soal"=>$kodeSoal,
                "nama_soal"=>$request->nama_soal,
                "kd_training"=>$request->kd_training_soal,
                "created_at"=>date("Y-m-d H:i:s"),
                "updated_at"=>date("Y-m-d H:i:s"),
                "jumlah_soal"=>count($tmp)
            ]);

            DB::commit();
            return response()->json([
                "code"=>200,
                "status"=>"true",
                "message"=>"Sukses Import Data",
                "data"=>[]
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "code"=>400,
                "status"=>"false",
                "message"=>$th->getMessage(),
                "data"=>[]
            ]);
        }
    }

    public function deleteSoal(Request $request){
        try {
            DB::beginTransaction();
            $kode_soal = $request->kode_soal;
            GroupSoal::where("kode_soal",$kode_soal)->delete();
            Soal::where("kode_soal",$kode_soal)->delete();
            DB::commit();
            return response()->json([
                "code"=>200,
                "status"=>"true",
                "message"=>"Sukses Hapus Data",
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "code"=>400,
                "status"=>"false",
                "message"=>"Gagal Hapus Data",
            ]);

        }
        
    }

    public function showBankSoal(Request $r){
        $kd_training = $r->kd_training;
        // $data = Soal::where('kd_training',$kd_training)->groupBy('kode_soal','created_at')->select('kode_soal', \DB::raw('count(*) as total'),'created_at')->get();

        $data = GroupSoal::where("kd_training",$kd_training)->select("kode_soal","nama_soal","created_at","jumlah_soal")->withCount('soalPreTest','soalPostTest')->orderBy("created_at","DESC")->get();
        // return response()->json($data);
        return DataTables::of($data)
        ->editColumn('created_at',function($q){
            return date('d/m/Y H:i:s',strtotime($q->created_at));
        })
        ->addColumn('aksi',function($q){
            $deleteAction = "";
            if($q->soal_pre_test_count == 0 && $q->soal_post_test_count == 0){
                $deleteAction = '<button onClick="btnHapusSoal(' . "'$q->kode_soal'" . ')"  class="btn btn-danger btn-sm delete waves-effect waves-light" title="Delete">
                <i class="fas fa-trash" title="Delete"></i>
                </button>';
            }
            $previewAction = '<button onClick="previewSoal(' . "'$q->kode_soal'" . ')"  class="btn btn-info btn-sm waves-effect waves-light" title="Preview">
                <i class="fas fa-link" title="Preview"></i> Preview
                </button>';
            $action = '<span>'.$previewAction." ".$deleteAction.'</span>';
            return $action;
            
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function viewTest($kd_event_training,$keterangan){
        $data['kd_event_training'] = $kd_event_training;
        $kd_karyawan = Auth::user()->kd_karyawan;
        $dataTest = PesertaTest::where('kd_event_training',$kd_event_training)->where('kd_karyawan',$kd_karyawan)->first();
        $dataEventTraining = EventTraining::where('kd_event_training',$kd_event_training)->first();
        $data['data_test'] = $dataTest;
        $data['keterangan'] = $keterangan;
        if($keterangan == "pre-test"){
            $kode_soal = $dataEventTraining->kode_soal_pre_test;
        }else{
            $kode_soal = $dataEventTraining->kode_soal_post_test;
        }
        $data['kode_soal'] = $kode_soal;
        $data['jumlah_jawaban'] = null;
        if(!empty($dataTest)){
            if($keterangan == "pre-test"){
                if($dataTest->user_selesai_pre_test != null){
                    $data['jumlah_jawaban'] = $this->hitungBetul($kd_event_training,$keterangan,$kode_soal);
                }
            }else{
                if($dataTest->user_selesai_post_test != null){
                    $data['jumlah_jawaban'] = $this->hitungBetul($kd_event_training,$keterangan,$kode_soal);
                }
            }
        }
        return view('pages.lms.training.cat',$data);
    }

    public function viewPilihanSoal(Request $r){
        $kd_event_training = $r->kd_event_training;
        $keterangan = $r->keterangan;
        $kode_soal = $r->kode_soal;
        $kd_karyawan = Auth::user()->kd_karyawan;

        $pilihanSoal = $this->getSoalByKeterangan($kd_event_training,$keterangan);
        // load soal 1
        $urutanSoal = $pilihanSoal[0];
        $dataSoal = Soal::where('kode_soal',$kode_soal)->where('no_soal',$urutanSoal)->first();
        // dapatkan jawaban kalau ada
        $jawaban = JawabanSoal::where('kode_soal',$kode_soal)->where('kd_karyawan',$kd_karyawan)->where('kd_event_training',$kd_event_training)->where('tipe_test',$keterangan)->select("no_soal")->get()->pluck('no_soal');

        // dapatkan jawaban satu
        $jawabanPilihan = JawabanSoal::where('kode_soal',$kode_soal)->where('kd_karyawan',$kd_karyawan)->where('kd_event_training',$kd_event_training)->where('tipe_test',$keterangan)->where('no_soal',$urutanSoal)->select("pilihan")->first()->pilihan ?? null;

        $display = [
            "data_soal"=>$dataSoal,
            "data_pilihan"=>$pilihanSoal,
            "jawaban"=>$jawaban,
            "jawaban_pilihan"=>$jawabanPilihan
        ];
        return response()->json($display);
    }

    public function saveJawaban(Request $r){
        $kd_karyawan = Auth::user()->kd_karyawan;
        $kd_event_training = $r->kd_event_training;
        $keterangan = $r->keterangan;
        $kode_soal = $r->kode_soal;
        $no_soal = $r->no_soal;
        $no_soal_index = (int) $r->no_soal_index;
        $option = $r->option;

        // cari betul atau tidak nya
        $jawaban = Soal::where('kode_soal',$kode_soal)->where('no_soal',$no_soal)->select('kunci_jawaban')->first()->kunci_jawaban ?? null;
        $status = $jawaban == $option ? true : false;
        $storeJawaban = JawabanSoal::updateOrInsert([
            "kode_soal"=>$kode_soal,
            "kd_karyawan"=>$kd_karyawan,
            "kd_event_training"=>$kd_event_training,
            "no_soal"=>$no_soal,
            "tipe_test"=>$keterangan
        ],[
            "pilihan"=>$option,
            "status"=>$status,
            "created_at"=>date("Y-m-d H:i:s"),
            "updated_at"=>date("Y-m-d H:i:s")
        ]);
        // setelah store jawaban lakukan ambil soal selanjutnya
        $no_soal_index_inc = $no_soal_index + 1;
        $pilihanSoal = $this->getSoalByKeterangan($kd_event_training,$keterangan);
        $jumlahSoal = count($pilihanSoal);
        // cek jika sama besar dengan jumlah soal
        if($jumlahSoal == $no_soal_index_inc){
            $no_soal_index_inc =  $no_soal_index;
            $display = [
                "data_soal"=>null,
                "index"=>$no_soal_index,
                "jawaban_pilihan"=>null
            ];
        }else{
            // load soal selanjutnya
            $urutanSoal = $pilihanSoal[$no_soal_index_inc];
            $dataSoal = Soal::where('kode_soal',$kode_soal)->where('no_soal',$urutanSoal)->first();
            // dapatkan jawaban selanjutnya kalau ada
            $jawabanPilihan = JawabanSoal::where('kode_soal',$kode_soal)->where('kd_karyawan',$kd_karyawan)->where('kd_event_training',$kd_event_training)->where('no_soal',$urutanSoal)->where('tipe_test',$keterangan)->select("pilihan")->first()->pilihan ?? null;

            $display = [
                "data_soal"=>$dataSoal,
                "index"=>$no_soal_index_inc,
                "jawaban_pilihan"=>$jawabanPilihan
            ];
        }
        
        return response()->json($display);

    }

    public function viewSoal(Request $r){
        $kd_karyawan = Auth::user()->kd_karyawan;
        $kd_event_training = $r->kd_event_training;
        $keterangan = $r->keterangan;
        $kode_soal = $r->kode_soal;
        $no_soal = $r->no_soal;
        $no_soal_index = (int) $r->no_soal_index;

        // setelah store jawaban lakukan ambil soal selanjutnya
        $pilihanSoal = $this->getSoalByKeterangan($kd_event_training,$keterangan);
        // load soal selanjutnya
        $no_soal_index_inc = $no_soal_index;
        $urutanSoal = $pilihanSoal[$no_soal_index_inc];
        $dataSoal = Soal::where('kode_soal',$kode_soal)->where('no_soal',$urutanSoal)->first();
        // dapatkan jawaban selanjutnya kalau ada
        $jawabanPilihan = JawabanSoal::where('kode_soal',$kode_soal)->where('kd_karyawan',$kd_karyawan)->where('kd_event_training',$kd_event_training)->where('no_soal',$urutanSoal)->where('tipe_test',$keterangan)->select("pilihan")->first()->pilihan ?? null;

        $display = [
            "data_soal"=>$dataSoal,
            "index"=>$no_soal_index_inc,
            "jawaban_pilihan"=>$jawabanPilihan
        ];
        return response()->json($display);

    }

    public function startTest(Request $r){
        // return "oke";
        $kd_event_training = $r->kd_event_training;
        $keterangan = $r->keterangan;
        $kd_karyawan = Auth::user()->kd_karyawan;
        $data = [
            "kd_event_training"=>$kd_event_training,
            "kd_karyawan"=>$kd_karyawan,
        ];
        // menit
        $tambahWaktu = 15;
        $dataInsert = [];
        if($keterangan == "pre-test"){
            $dataInsert['waktu_mulai_pre_test'] = date('Y-m-d H:i:s');
            $dataInsert['batas_waktu_pre_test'] = date('Y-m-d H:i:s',strtotime("+$tambahWaktu minutes"));
        }else{
            $dataInsert['waktu_mulai_post_test'] = date('Y-m-d H:i:s');
            $dataInsert['batas_waktu_post_test'] = date('Y-m-d H:i:s',strtotime("+$tambahWaktu minutes"));
        }
        $insert = PesertaTest::updateOrinsert($data,$dataInsert);
        $dataDisplay = array_merge($data,$dataInsert);
        return response()->json($dataDisplay);
    }

    public function selesaiTest(Request $r){
        $kd_event_training = $r->kd_event_training;
        $kode_soal = $r->kode_soal;
        $keterangan = $r->keterangan;
        $kd_karyawan = Auth::user()->kd_karyawan;
        $tanggalSekarang = date('Y-m-d H:i:s');
        $dataHasil = $this->hitungBetul($kd_event_training,$keterangan,$kode_soal);
        if($keterangan == "pre-test"){
            $data['user_selesai_pre_test'] = $tanggalSekarang;
            // hitung betul
            PesertaTraining::where("kd_event_training",$kd_event_training)->where('kd_karyawan',$kd_karyawan)->update([
                "nilai_pre_test"=>$dataHasil['nilai'],
                "betul_pre_test"=>$dataHasil['jumlah_betul'],
                "salah_pre_test"=>$dataHasil['jumlah_salah']
            ]);
        }else{
            $data['user_selesai_post_test'] = $tanggalSekarang;
            // update status peserta training
            PesertaTraining::where("kd_event_training",$kd_event_training)->where('kd_karyawan',$kd_karyawan)->update([
                "status"=>"Selesai",
                "nilai_post_test"=>$dataHasil['nilai'],
                "betul_post_test"=>$dataHasil['jumlah_betul'],
                "salah_post_test"=>$dataHasil['jumlah_salah'],
                "tanggal_selesai"=>date("Y-m-d H:i:s")
            ]);
        }
        $update = PesertaTest::where('kd_event_training',$kd_event_training)->where('kd_karyawan',$kd_karyawan)
        ->update($data);
        return response()->json($update);
    }

    private function hitungBetul($kd_event_training,$keterangan,$kode_soal){
        $kd_karyawan = Auth::user()->kd_karyawan;
        $jawabanSoal = JawabanSoal::where('kd_event_training',$kd_event_training)->where('kode_soal',$kode_soal)->where('tipe_test',$keterangan)->where('kd_karyawan',$kd_karyawan)->groupBy('status')->select(\DB::raw('count(status) as jumlah'),'status')->get();
        $jumlahBetul = 0;
        $jumlahSalah = 0;
        $nilai = 0;
        foreach($jawabanSoal as $value){
            if($value->status){
                $jumlahBetul = $value->jumlah;
            }else{
                $jumlahSalah = $value->jumlah;
            }
        }
        // hitung jumlah soal
        $jumlahSoal = Soal::where('kode_soal',$kode_soal)->count();
        $totalJawaban = $jumlahBetul + $jumlahSalah;
        $selisih = $jumlahSoal - $totalJawaban;
        $jumlahSalah += $selisih;
        $nilai = round(($jumlahBetul/$jumlahSoal)*100);
        $tmp = [
            "jumlah_betul"=>$jumlahBetul,
            "jumlah_salah"=>$jumlahSalah,
            "nilai"=>$nilai
        ];
        return $tmp;
    }

    private function getSoalByKeterangan($kd_event_training,$keterangan){
        $kd_karyawan = Auth::user()->kd_karyawan;
        $dataPesertaTraining = PesertaTraining::where('kd_event_training',$kd_event_training)->where('kd_karyawan',$kd_karyawan)->first();

        if($keterangan == "pre-test"){
            $pilihanSoal = explode(",",$dataPesertaTraining->soal_display_pre_test);
        }else{
            $pilihanSoal = explode(",",$dataPesertaTraining->soal_display_post_test);
        }
        return $pilihanSoal;
    }

    public function FinalProject(Request $r)
    {
        $kd_karyawan = Auth::user()->kd_karyawan;
        $event_training = $r->kd_event_training;

        $finalProject = PesertaTraining::select(
            'peserta_training.kd_event_training',
            'training.nama_training',
            'peserta_training.nilai_pre_test',
            'peserta_training.nilai_post_test',
            'event_training.tanggal_mulai',
            'peserta_training.final_project'
        )
        ->join('lms.event_training', 'peserta_training.kd_event_training', 'event_training.kd_event_training')
        ->join('lms.training','training.kd_training', 'event_training.kd_training')
        ->where('peserta_training.kd_event_training', $event_training)
        ->where('peserta_training.kd_karyawan', $kd_karyawan)
        ->first();

        if ($finalProject && $finalProject->final_project) {
            $finalProject->preview_url = asset('storage/final_project/' . $finalProject->final_project);
        }

        return response()->json($finalProject);
    }

    public function UploadFinalProject(Request $r)
{
    $kd_event_training = $r->kd_event_training;
    $kd_karyawan = Auth::user()->kd_karyawan;

    try {
        DB::beginTransaction();

        $rules = [
            // 'file' => 'required|mimes:pdf,docx,doc|max:10240'
            'file' => 'required|mimes:xls,xlsx|max:10240'
        ];
        $message = [
            "file.required" => "Document Wajib di upload",
            "file.max" => "Max Document Upload 10 MB.",
            "file.mimes" => "Document diupload dengan extension .xls .xlsx",
        ];

        $validator = Validator::make($r->all(), $rules, $message);
        if ($validator->fails()) {
            DB::rollBack();
            return response()->json($validator->errors()->first(), 422);
        }

        if (!$r->hasFile('file')) {
            DB::rollBack();
            return response()->json("File tidak ditemukan", 422);
        }

        $files = $r->file('file');

        $fileName=date('YmdHis'). "." .$files->getClientOriginalExtension();

        $peserta=PesertaTraining::where('kd_event_training',$kd_event_training)
        ->select('final_project')
        ->where('kd_karyawan',$kd_karyawan)
        ->first();

        if($peserta && $peserta->final_project){
            $doclama= 'final_project/' . $peserta->final_project;
            if(Storage::exists($doclama)){
                Storage::delete($doclama);
            }
        }

        $path = Storage::put('final_project'.'/'.$fileName,file_get_contents($files));

        if (!$path) {
            DB::rollBack();
            return response()->json("Gagal menyimpan file", 500);
        }

        $save = PesertaTraining::where('kd_event_training', $kd_event_training)
            ->where('kd_karyawan', $kd_karyawan)
            ->update(["final_project" => $fileName]);

        if (!$save) {
            DB::rollBack();
            return response()->json([
                "code" => 400,
                "status" => "false",
                "message" => "Failed",
            ]);
        }

        DB::commit();
        return response()->json([
            "code" => 200,
            "status" => "true",
            "message" => "Sukses",
            "kd_event_training" => $kd_event_training,
        ]);

    } catch (\Exception $th) {
        DB::rollBack();
        return response()->json(["message" => $th->getMessage()], 500);
    }
}

// public function UploadFinalProject(Request $r)
// {
//     $kd_event_training = $r->kd_event_training;
//     $kd_karyawan = Auth::user()->kd_karyawan;

//     try {
//         DB::beginTransaction();

//         if (!$r->hasFile('file')) {
//             DB::rollBack();
//             return response()->json("File tidak ditemukan", 422);
//         }

//         $files = $r->file('file');
//         $fileName = date('YmdHis') . "." . $files->getClientOriginalExtension();
//         $newFilePath = 'final_projects/' . $fileName;

//         $peserta = PesertaTraining::where('kd_event_training', $kd_event_training)
//             ->where('kd_karyawan', $kd_karyawan)
//             ->first();

//         if ($peserta && $peserta->final_project) {
//             $oldPath = 'public/final_projects/' . $peserta->final_project;

//             if (Storage::exists($oldPath)) {
//                 Storage::delete($oldPath);
//             }
//         }

//         $path = $files->storeAs('final_projects', $fileName, 'public');

//         if (!$path) {
//             DB::rollBack();
//             return response()->json("Gagal menyimpan file", 500);
//         }

//         $save = PesertaTraining::where('kd_event_training', $kd_event_training)
//             ->where('kd_karyawan', $kd_karyawan)
//             ->update(["final_project" => $fileName]);

//         if (!$save) {
//             DB::rollBack();
//             return response()->json([
//                 "code" => 400,
//                 "status" => "false",
//                 "message" => "Gagal update data",
//             ]);
//         }

//         DB::commit();
//         return response()->json([
//             "code" => 200,
//             "status" => "true",
//             "message" => "Berhasil upload ulang file",
//         ]);

//     } catch (\Exception $th) {
//         DB::rollBack();
//         return response()->json(["message" => $th->getMessage()], 500);
//     }
// }

}
