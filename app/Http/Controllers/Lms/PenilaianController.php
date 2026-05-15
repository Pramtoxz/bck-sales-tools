<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\EventTraining;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Karyawan;
use App\Models\PesertaTraining;
use App\Models\FeedbackTraining;
use App\Models\HistoryJob;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PDF;
use Auth;
class PenilaianController extends Controller

{
    public function index(Request $r)
    {
        return view('pages.lms.penilaian.index');
    }

    public function get(Request $r)
    {
        // $data = Training::join('lms.jenis_training', 'lms.training.kd_jenis_training', '=', 'lms.jenis_training.kd_jenis_training')
        // ->select('training.id','training.kd_training','training.nama_training', 'jenis_training.nama_jenis','training.training_tag','training.deskripsi','training.active','training.document_pre_test','training.document_post_test')->orderBy('training.created_at','DESC');
        $data=DB::table('lms.training')
        ->join('lms.jenis_training','lms.training.kd_jenis_training','=','lms.jenis_training.kd_jenis_training')
        // ->join('lms.event_training','lms.training.kd_training','=' , 'lms.event_training.kd_training')
        ->groupBy('training.kd_training','training.id','jenis_training.nama_jenis')
        ->select('training.id','training.kd_training','training.nama_training','jenis_training.nama_jenis','training.training_tag','training.deskripsi') 
        ->orderBy('training.created_at','DESC')
        ->get();
        // return $data;

        return DataTables::of($data)
        ->addColumn('aksi',function($q){
            $tambahAction = '<button onClick="tambahNilaiPeserta(' . "'$q->kd_training'" . ')" class="btn btn-primary btn-sm edit waves-effect waves-light" title="Tambah" data-bs-toggle="modal" data-bs-target="#containerModalTambah">
                <i class="fas fa-users" title="Tambah"></i> Peserta
            </button>';
            $ulasan = '<button onClick="ulasan(' . "'$q->kd_training'" . ')" class="btn btn-danger btn-sm edit waves-effect waves-light" title="Edit" data-bs-toggle="modal" data-bs-target="#containerModalUlasan">
                <i class="fas fa-pencil-alt" title="Edit"></i> Ulasan
            </button>';

            $action = '<span>'.$tambahAction." ".$ulasan.'</span>';
            return $action;
        })
     
        ->rawColumns(['aksi'])
        ->make(true);

    }

    public function show(Request $r)
    {
    
        $id_event = $r->id_event;
        if($id_event==="null") {
            return response()->json([]);
        }
        // return $id_event;
        $results = Karyawan::join('jabatan', 'karyawan.kd_jabatan', '=', 'jabatan.kd_jabatan')
            ->join('lms.peserta_training', 'karyawan.kd_karyawan', '=', 'lms.peserta_training.kd_karyawan')
            ->leftJoin('lms.event_training', 'lms.peserta_training.kd_event_training', '=', 'lms.event_training.kd_event_training')
            ->leftJoin('lms.training', 'lms.training.kd_training', '=', 'lms.event_training.kd_training')
            ->where('event_training.id', $id_event)
            ->select('karyawan.id', 'karyawan.nama_lengkap', 'jabatan.id', 'jabatan.nama_jabatan', 
            'training.id', 'training.kd_training','training.nama_training', 'event_training.id', 
            'event_training.tanggal_mulai','event_training.tanggal_akhir','peserta_training.id', 'peserta_training.status' , 'peserta_training.nilai_pre_test' , 'peserta_training.nilai_post_test','peserta_training.final_project')
            ->get();
        // return $results;
        return DataTables::of($results)
            ->addColumn('kd_training',function($q) {
                return $q->kd_training;
                
            })
            ->addColumn('final_project',function($q) {
                if($q->final_project){
                    $path = "storage/final_project/".$q->final_project;
                    $btnPreview = '<a onClick="preview('."'$path'" .')" href="#"><i class="fas fa-link" title="Preview"></i> Preview</a>';
                    $action = '<span>'.$btnPreview.'</span>';
                    return $action;
                }else{
                    return '-';
                }
                
            })
            ->rawColumns(['aksi','final_project'])
            ->make(true);
           
    }
    
    public function save(Request $r){
        $save = PesertaTraining::where('id',$r->id)->update([
            "nilai_pre_test"=>$r->nilai_pre_test,
            "nilai_post_test"=>$r->nilai_post_test,
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
    }

    public function getUlasan(Request $r)
    {
        $id = $r->kd_training;
        $data=DB::table('lms.feedback_training')
        ->join('lms.training','lms.feedback_training.kd_training','=','lms.training.kd_training')
        ->join('karyawan','lms.feedback_training.kd_karyawan','=' , 'karyawan.kd_karyawan')
        ->leftJoin('jabatan','karyawan.kd_jabatan','=' , 'jabatan.kd_jabatan')
        ->where('lms.training.kd_training', $id)
        ->select('feedback_training.id','training.kd_training','training.nama_training','karyawan.nama_lengkap','jabatan.nama_jabatan','feedback_training.rating','feedback_training.catatan')
        ->orderBy('feedback_training.id','DESC')
        ->get();
        // return $data;

        return DataTables::of($data)
        ->addColumn('aksi',function($q){
            $deleteAction = '<button onClick="deleteData(' . "'$q->id'" . ')"  class="btn btn-danger btn-sm delete waves-effect waves-light" title="Delete">
                <i class="fas fa-trash" title="Delete"></i>
            </button>';

            $action = '<span>'.$deleteAction.'</span>';
            return $action;
        })
     
        ->rawColumns(['aksi'])
        ->make(true);

    }

    public function delete(Request $r){
        $id = $r->id;
        $delete = FeedbackTraining::find($id)->delete();
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

    public function dataBatch(Request $r){
    
        $kd_training = $r->kd_training;
          $eventTraining = EventTraining::where('kd_training', $kd_training)
        ->select('id', DB::raw("(tanggal_mulai || ' - ' || tanggal_akhir) as text"),"tanggal_mulai","tanggal_akhir")
        ->orderBy('tanggal_mulai', 'asc')
        ->get();
        return collect($eventTraining)->map(function($item,$key){
            $tgl_mulai = Carbon::parse($item['tanggal_mulai'])->isoFormat('DD MMMM YYYY');
            $tgl_akhir = Carbon::parse($item['tanggal_akhir'])->isoFormat('DD MMMM YYYY');
            return [
                        'id' => $item['id'],
                        'text' => 'Batch-'.($key+1) .' : '.$tgl_mulai. " s/d " . $tgl_akhir,
        
                    ];
        })->sortDesc()->values()->all();
    }

    public function dataBatchAll(){
        // return "testetstt";
        $batchAll = DB::table('lms.event_training')->select('id',DB::raw("(tanggal_mulai || '-' || tanggal_akhir) as text"))->orderBy('tanggal_mulai','desc')->get();
       
        return response()->json($batchAll); 

        // $batchAlll = DB::table('event_training')->select(['kd_training as id','tanggal_akhir as text'])->get();
        // return response()->json($batchAlll);
    }

    public function eventAkhir(Request $r){
        $kd_trainig = $r->kd_training;
        $id = EventTraining::where('kd_training',$kd_trainig)->select('id')->orderBy('id','DESC')->first()->id ?? null;
        return response()->json($id);
    }

    public function userGetNilai(){
        return view('pages.lms.userNilai.index'); 
    }

    public function userGetNilaiTable(Request $r){
        $user = auth()->user()->kd_karyawan;

        $results = PesertaTraining::leftjoin('lms.event_training', 'peserta_training.kd_event_training', '=', 'event_training.kd_event_training')
        ->leftjoin('lms.training', 'training.kd_training', '=', 'event_training.kd_training')
        ->where('peserta_training.kd_karyawan', $user)
        ->select('training.nama_training','peserta_training.nilai_pre_test','peserta_training.nilai_post_test','peserta_training.tanggal_mulai','peserta_training.tanggal_selesai','event_training.kd_event_training')
        ->orderBy("peserta_training.created_at","DESC")
        ->get();

        return DataTables::of($results)

        // ->addColumn('nama_training',function($q){
        //     return $q->nama_training;
        // })
        ->addColumn('nama_training',function($q){
            $nama_training = $q->nama_training;
            $jumlahKarakter = strlen($q->nama_training);
            if($jumlahKarakter >= 40){
                $nama_training = substr($nama_training,0,30)."...";
            }
            return $nama_training;
        })

        ->addColumn('tanggal_mulai',function($q){
            if($q->tanggal_mulai != null){
                return date('d/m/Y',strtotime($q->tanggal_mulai));
            }else{
                return "-";
            }
        })
        ->addColumn('tanggal_selesai',function($q){
            if($q->tanggal_selesai != null){
                return date('d/m/Y',strtotime($q->tanggal_selesai));
            }else{
                return "-";
            }
        })
        ->addColumn('action',function($q){
            if($q->tanggal_selesai != null){
                $sertifikatButton = '<button onClick="downloadSertifikat(' . "'$q->kd_event_training'" . ')"  class="btn btn-success btn-sm delete waves-effect waves-light" title="Download">
                    <i class="fas fa-download" title="Download"></i>
                </button>';

                $action = '<span>'.$sertifikatButton.'</span>';
                return $action;
            }else{
                return "-";
            }
        })
        ->rawColumns(['action'])
        ->make(true);
    }


    public function downloadSertifikat($kode_event){
        $user = auth()->user()->kd_karyawan;
        $data = PesertaTraining::join('lms.event_training','event_training.kd_event_training','peserta_training.kd_event_training')->join('lms.training','event_training.kd_training','training.kd_training')->where("peserta_training.kd_event_training",$kode_event)->where("peserta_training.kd_karyawan",$user)
        ->select("training.nama_training","event_training.kd_event_training")
        ->first();

        $datapdf = [
            'nama_training' => $data->nama_training ?? "-",
            'kode_event_training' => $data->kd_event_training ?? "-",
            'nama_karyawan' => strtoupper(Auth::user()->karyawan->nama_lengkap)
        ];

        if(empty($data)){
            abort(404);
        }else{
            $pdf = PDF::loadView('pages.lms.userNilai.sertifikat',$datapdf)->setPaper('a4', 'landscape');
            return $pdf->download('e-sertifikat-lms.pdf');
        }


        
        // return view('pages.lms.userNilai.sertifikat'); 
    }

}
