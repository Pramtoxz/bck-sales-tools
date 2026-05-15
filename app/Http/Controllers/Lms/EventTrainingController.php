<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Helper\Helper;
use App\Models\EventTraining;
use Illuminate\Console\Scheduling\Event;
use App\Models\Karyawan;
use App\Models\PesertaTraining;
use App\Models\Soal;
use App\Notifications\TrainingNotifications;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Training;

class EventTrainingController extends Controller
{
    public function index()
    {
        return view('pages.lms.training.event_training');
    }

    public function get(Request $r){
        $tanggalSekarang = date('Y-m-d');
        $data = EventTraining::with('training.jenis_training','peserta_training')->orderBy('created_at','DESC')->where('tanggal_akhir','>=',$tanggalSekarang);
        return DataTables::of($data)
        ->addColumn('aksi',function($q){
            $tanggal = date('Y-m-d');
            $editAction ="";
            $deleteAction = "";
            $tambahAction = "";
            if($tanggal <= $q->tanggal_akhir){
                $tambahAction = '<button onClick="loadPeserta(' . "'$q->kd_event_training'"."," ."'$q->kd_training'". ')" class="btn btn-primary btn-sm edit waves-effect waves-light" title="Tambah" data-bs-toggle="modal" data-bs-target="#containerModalTambah">
                    <i class="fas fa-users" title="Tambah"></i> Peserta
                </button>';
            }
            if($tanggal <= $q->tanggal_mulai){
                $editAction = '<button onClick="editData(' . "'$q->id'" . ')" class="btn btn-warning btn-sm edit waves-effect waves-light" title="Edit" data-bs-toggle="modal" data-bs-target="#containerModal">
                <i class="fas fa-pencil-alt" title="Edit"></i>
                </button>';
            }
            if($tanggal <= $q->tanggal_mulai){
                $deleteAction = '<button onClick="deleteData(' . "'$q->id'" . ')"  class="btn btn-danger btn-sm delete waves-effect waves-light" title="Delete">
                <i class="fas fa-trash" title="Delete"></i>
                </button>';
            }
            $action = '<span>'.$tambahAction." ".$editAction." ".$deleteAction.'</span>';
            return $action;
        })
        ->addColumn('jumlah_peserta',function($q){
            $jumlahPesertaTraining = count($q->peserta_training);
            return $jumlahPesertaTraining;
        })
        ->editColumn('tanggal_mulai',function($q){
            return date('d-m-Y',strtotime($q->tanggal_mulai));
        })
        ->editColumn('tanggal_akhir',function($q){
            return date('d-m-Y',strtotime($q->tanggal_akhir));
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }
    
    public function save(Request $r){
        $kodeEventTraining = Helper::getKodeUniqueId("ET");
        try {
            $rules = [
                'kd_training' => 'required',
                'tanggal_mulai' => 'required',
                'tanggal_akhir' => 'required',
                'kode_soal_pre_test' => 'required',
                'kode_soal_post_test' => 'required',
            ];
            $message = [
                "kd_training.required"=> "Nama Training Wajib Diisi",
                "tanggal_mulai.required"=>"tanggal mulai Wajib Diisi",
                "kode_soal_pre_test.required"=>"Kode Soal Pre Test Wajib Dipilih",
                "kode_soal_post_test.required"=>"Kode Soal Post Test Wajib Dipilih",
            ];
            $validator = Validator::make($r->all(), $rules,$message);
            if ($validator->fails()) {
                return response()->json($validator->errors()->first(),422);
            }
            if($r->tipe_submit == "add"){
                // count training pada event
                $save = EventTraining::insert([
                    "kd_event_training"=>$kodeEventTraining,
                    "kd_training"=>$r->kd_training,
                    "tanggal_mulai"=>$r->tanggal_mulai,
                    "tanggal_akhir"=>$r->tanggal_akhir,
                    "kode_soal_pre_test"=>$r->kode_soal_pre_test,
                    "kode_soal_post_test"=>$r->kode_soal_post_test,
                    "created_at"=>date('Y-m-d H:i:s'),
                    "updated_at"=>date('Y-m-d H:i:s'),
                ]);
            }else{
                $save = EventTraining::where('id',$r->id_data)->update([
                    "kd_training"=>$r->kd_training,
                    "tanggal_mulai"=>$r->tanggal_mulai,
                    "tanggal_akhir"=>$r->tanggal_akhir,
                    "kode_soal_pre_test"=>$r->kode_soal_pre_test,
                    "kode_soal_post_test"=>$r->kode_soal_post_test,
                ]);
            }
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
        
    }
    
    public function show(Request $r){
        $id = $r->id;
        return response()->json(EventTraining::find($id));
    }
    
    public function delete(Request $r){
        $id = $r->id;
        $event = EventTraining::find($id);
        $deletePesertaTraining = PesertaTraining::where('kd_event_training',$event->kd_event_training)->delete();
        if($event->delete()){
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
    
    public function all(){
        $eventTraining = DB::table('lms.event_training')
        ->join('lms.training', 'lms.event_training.kd_training', '=', 'lms.training.kd_training')
        ->select(['kd_departement as id','deskripsi as text'])->where('active','t')->get();
        return response()->json($eventTraining);
       
    }

    public function peserta(Request $r){
        $kd_event_training  = $r->kd_event_training;
        $dataKaryawan = Karyawan::leftjoin('lms.peserta_training',function($q) use($kd_event_training){
            $q->on('peserta_training.kd_karyawan','karyawan.kd_karyawan')->where('peserta_training.kd_event_training',$kd_event_training);
        })
        ->leftjoin('public.jabatan','jabatan.kd_jabatan','karyawan.kd_jabatan')
        ->select('karyawan.nama_lengkap','karyawan.kd_departement','jabatan.nama_jabatan','peserta_training.kd_event_training','karyawan.kd_karyawan','peserta_training.id as id_peserta_training')->orderBy('id_peserta_training','ASC');
        return DataTables::of($dataKaryawan)
        ->addColumn('aksi',function($q){
            if($q->kd_event_training == null){
                return '<input class="" type="checkbox" id="departement_karyawan_list" name="departement_karyawan_list" value="' . $q->kd_karyawan . '" >';
            }else{
                return '<i class="fas fa-check text-success" title="Sudah">';
            }
        })
        ->addColumn('hapus',function($q){
            if($q->kd_event_training != null){
                $deleteAction = '<button onClick="deleteDataPeserta(' . "'$q->id_peserta_training'" . ')"  class="btn btn-danger btn-sm delete waves-effect waves-light" title="Delete">
                    <i class="fas fa-trash" title="Delete"></i>
                </button>';
                $action = '<span>'.$deleteAction.'</span>';
                return $action;
            }else{
                return '';
            }
        })
        ->rawColumns(['aksi','hapus'])
        ->make(true);
    }

    public function savePeserta(Request $r){
        $kdtraining=$r->kd_training;
        $event_training=$r->kd_event_training;
        try {
            $rules = [
                'kd_event_training' => 'required',
            ];
            $message = [
                "kd_event_training.required"=> "Kode Event Training Wajib Diisi",
            ];
            $validator = Validator::make($r->all(), $rules,$message);
            if ($validator->fails()) {
                return response()->json($validator->errors()->first(),422);
            }
            $arrayData = $r->arrayData;
            // get no soal pre test dan post test
            $dataEvenTraining = EventTraining::where('kd_event_training',$event_training)->select("kode_soal_pre_test","kode_soal_post_test")->first();
            $getNoSoalPreTest = Soal::where('kode_soal',$dataEvenTraining->kode_soal_pre_test)->select("no_soal")->get()->pluck("no_soal")->toArray();
            $getNoSoalPostTest = Soal::where('kode_soal',$dataEvenTraining->kode_soal_post_test)->select("no_soal")->get()->pluck("no_soal")->toArray();
            
            foreach($arrayData as $value){
                shuffle($getNoSoalPreTest);
                shuffle($getNoSoalPostTest);
                $noSoalPreTest = implode(",",$getNoSoalPreTest);
                $noSoalPostTest = implode(",",$getNoSoalPostTest);
                PesertaTraining::updateOrInsert(
                    [
                        "kd_event_training"=>$r->kd_event_training,
                        "kd_karyawan"=>$value['kd_karyawan']
                    ],
                    [
                        "status"=> "Belum Mulai",
                        "created_at"=>date('Y-m-d H:i:s'),
                        "updated_at"=>date('Y-m-d H:i:s'),
                        "soal_display_pre_test"=>$noSoalPreTest,
                        "soal_display_post_test"=>$noSoalPostTest,
                    ]
                );
            }
            $userpenerima=User::whereIn('kd_karyawan',$arrayData)->get();
            $namatraining=Training::join('lms.event_training', 'lms.training.kd_training', '=', 'lms.event_training.kd_training')
            ->where('lms.training.kd_training',$kdtraining)
            ->where('lms.event_training.kd_event_training', $event_training)
            ->select('training.kd_training','training.nama_training','event_training.kd_event_training','event_training.tanggal_mulai','event_training.tanggal_akhir')
            ->first();
            
            Notification::send($userpenerima,new TrainingNotifications($namatraining));
            return response()->json([
                "code"=>200,
                "status"=>"true",
                "message"=>"Sukses Melakukan Penambahan Peserta Training",
            ]);
        } catch (\Exception $th) {
            return response()->json([
                "code"=>400,
                "status"=>"false",
                "message"=>"Failed " . $th->getMessage(),
            ]);
        }
    }

    public function deletePeserta(Request $r){
        $peserta = PesertaTraining::find($r->id_peserta_training);
        $peserta = $peserta->delete();
        if($peserta){
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
    
    public function getKodeSoal(Request $r){
        $kd_training = $r->kd_training;
        $dataSoal = DB::table('lms.group_soal')->where('kd_training',$kd_training)->select('kode_soal as id','nama_soal as text')->get();
        return response()->json($dataSoal);
    }
}
