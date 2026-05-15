<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\HistoryJob;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class HistoryJobController extends Controller
{
    public function index()
    {
        return view('pages.master.history_job');
    }

    public function get(Request $r){

    $data = DB::table('history_job')
    ->join('karyawan', 'history_job.kd_karyawan', '=', 'karyawan.kd_karyawan')
    ->join('departement', 'history_job.kd_departement', '=', 'departement.kd_departement')
    ->join('jabatan', 'history_job.kd_jabatan', '=', 'jabatan.kd_jabatan')
    ->select('history_job.id','karyawan.nama_lengkap','departement.deskripsi','jabatan.nama_jabatan','history_job.mulai_menjabat','history_job.akhir_menjabat','history_job.resign')
    ->orderBy('karyawan.nama_lengkap', 'asc')
    ->get();    
    
        return DataTables::of($data)
        ->addColumn('aksi',function($q){
            $editAction = '<button onClick="editData(' . "'$q->id'" . ')" class="btn btn-warning btn-sm edit waves-effect waves-light" title="Edit" data-bs-toggle="modal" data-bs-target="#containerModal">
                <i class="fas fa-pencil-alt" title="Edit"></i>
            </button>';
            $deleteAction = '<button onClick="deleteData(' . "'$q->id'" . ')"  class="btn btn-danger btn-sm delete waves-effect waves-light" title="Delete">
                <i class="fas fa-trash" title="Delete"></i>
            </button>';
            $action = '<span>'.$editAction." ".$deleteAction.'</span>';
            return $action;
        })
        ->editColumn('mulai_menjabat',function($q){
            return date('d-m-Y',strtotime($q->mulai_menjabat));
        })
        ->editColumn('akhir_menjabat',function($q){
            return date('d-m-Y',strtotime($q->akhir_menjabat));
        })
        ->editColumn('resign',function($q){
            if($q->resign != null){
                return date('d-m-Y',strtotime($q->resign));
            }else{
                return "";
            }
        })
       
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function save(Request $r){
        
        try {
            $rules = [
                'kd_karyawan' => 'required',
                'kd_jabatan' => 'required',
                'mulai_menjabat'=>'required',
                // 'akhir_menjabat' => 'required',
            ];
            $message = [
                "kd_karyawan.required"=> "Karyawan Wajib Diisi",
                "kd_jabatan.required"=>"Jabatan Wajib Diisi",
                "mulai_menjabat.required"=>"Mulai menjabat Wajib Diisi",
                // "akhir_menjabat.required"=>"Akhir Menjabat Wajib Diisi",
               
            ];
            $validator = Validator::make($r->all(), $rules,$message);
            if ($validator->fails()) {
                return response()->json($validator->errors()->first(),422);
            }
            if($r->tipe_submit == "add"){
                $save = HistoryJob::insert([
                    "kd_karyawan"=>$r->kd_karyawan,
                    "kd_departement"=>$r->kd_departement,
                    "kd_jabatan"=>$r->kd_jabatan,
                    "mulai_menjabat"=>$r->mulai_menjabat,
                    "akhir_menjabat"=>$r->akhir_menjabat,
                    "resign"=>$r->resign,
                ]);
            }else{
                $save = HistoryJob::where('id',$r->id_data)->update([
                    "kd_karyawan"=>$r->kd_karyawan,
                    "kd_departement"=>$r->kd_departement,
                    "kd_jabatan"=>$r->kd_jabatan,
                    "mulai_menjabat"=>$r->mulai_menjabat,
                    "akhir_menjabat"=>$r->akhir_menjabat,
                    "resign"=>$r->resign,
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
        return response()->json(HistoryJob::find($id));
     
    }

    public function delete(Request $r){
        // return $r;
        $id = $r->id;
        $delete = HistoryJob::find($id)->delete();
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


}
