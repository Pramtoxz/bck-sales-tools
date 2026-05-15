<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\JenisTraining;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Helper\Helper;
use Illuminate\Support\Facades\DB;

class JenisTrainingController extends Controller
{
    public function index()
    {
        return view('pages.lms.training.jenis_training');
    }

     public function get(Request $r){
        $data = JenisTraining::withCount('training')->get();
        return DataTables::of($data)
        ->addColumn('aksi',function($q){
            $deleteAction = "";
            $editAction = '<button onClick="editData(' . "'$q->id'" . ')" class="btn btn-warning btn-sm edit waves-effect waves-light" title="Edit" data-bs-toggle="modal" data-bs-target="#containerModal">
                <i class="fas fa-pencil-alt" title="Edit"></i>
            </button>';
            if($q->training_count == 0){
                $deleteAction = '<button onClick="deleteData(' . "'$q->id'" . ')"  class="btn btn-danger btn-sm delete waves-effect waves-light" title="Delete">
                <i class="fas fa-trash" title="Delete"></i>
                </button>';
            }
            $action = '<span>'.$editAction." ".$deleteAction.'</span>';
            return $action;
        })
        ->editColumn('active',function($q){
            if($q->active == "t"){
                return "Aktif";
            }else{
                return "Tidak Aktif";
            }
        })
        ->editColumn('deskripsi',function($q){
            return substr($q->deskripsi,0,50)."...";
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }


    public function show(Request $r){
        $id = $r->id;
        return response()->json(JenisTraining::find($id));
    }

    public function save(Request $r){
        $kodeJenisTraining = Helper::getKodeUniqueId("T");
        try {
            $rules = [
                'nama_jenis' => 'required',
                'active' => 'required',
            ];
            $message = [
                "nama_jenis.required"=> "Nama Jenis Training Wajib Diisi",
                "active.required"=>"Status Active Wajib Diisi",
            ];
            $validator = Validator::make($r->all(), $rules,$message);
            if ($validator->fails()) {
                return response()->json($validator->errors()->first(),422);
            }
            if($r->tipe_submit == "add"){
                $save = JenisTraining::insert([
                    "kd_jenis_training"=>$kodeJenisTraining,
                    "nama_jenis"=>$r->nama_jenis,
                    "deskripsi"=>$r->deskripsi,
                    "active"=>$r->active,
                    "created_at"=>date('Y-m-d H:i:s'),
                    "updated_at"=>date('Y-m-d H:i:s')
                ]);
            }else{
                $save = JenisTraining::where('id',$r->id_data)->update([
                    "nama_jenis"=>$r->nama_jenis,
                    "deskripsi"=>$r->deskripsi,
                    "active"=>$r->active,
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

    public function delete(Request $r){
        $id = $r->id;
        $delete = JenisTraining::find($id)->delete();
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

    public function all(){
        $jenistraining = DB::table('lms.jenis_training')->select(['kd_jenis_training as id','nama_jenis as text'])->where('active','t')->get();
        return response()->json($jenistraining); 
    }
}
