<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helper\Helper;

class JabatanController extends Controller
{
    public function index()
    {
        return view('pages.master.jabatan');
    }

    public function get(Request $r){
        $data=Jabatan::with('departement')->orderBy('nama_jabatan','asc')
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
        ->editColumn('active',function($q){
            if($q->active == "t"){
                return "Aktif";
            }else{
                return "Tidak Aktif";
            }
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function save(Request $r){
        try {
            $rules = [
                'kd_departement'=>'required',
                'nama_jabatan'=>'required',
                'active' => 'required',
            ];
            $message = [
                "kd_departement.required"=>"Nama Departement Wajib dipilih",
                "nama_jabatan.required"=>"Nama Jabatan Wajib Diisi",
                "active.required"=>"Active Wajib Diisi",
            ];
            $validator = Validator::make($r->all(), $rules,$message);
            if ($validator->fails()) {
                return response()->json($validator->errors()->first(),422);
            }
            if($r->tipe_submit == "add"){
                $kodeJabatan = Helper::getKodeUniqueId("JBT");
                $save = Jabatan::insert([
                    "kd_departement"=>$r->kd_departement,
                    "kd_jabatan"=>$kodeJabatan,
                    "nama_jabatan"=>ucwords(strtolower($r->nama_jabatan)),
                    "active"=>$r->active
                ]);
            }else{
                $save = Jabatan::where('id',$r->id_data)->update([
                    "kd_departement"=>$r->kd_departement,
                    "nama_jabatan"=>ucwords(strtolower($r->nama_jabatan)),
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

    public function show(Request $r){
        $id = $r->id;
        return response()->json(Jabatan::find($id));
    }

    public function delete(Request $r){
        $id = $r->id;
        $delete = jabatan::find($id)->delete();
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
        $jabatan = DB::table('jabatan')->select(['kd_jabatan as id','nama_jabatan as text'])->where('active','t')->get();
        return response()->json($jabatan); 
    }

    public function filterr($id){
        $jabatan = DB::table('jabatan')->select(['kd_jabatan as id','nama_jabatan as text'])->where('kd_departement',$id)->where('active','t')->get();
        return response()->json($jabatan); 
    }

    public function filter($id){
        $departement = DB::table('jabatan')->select('kd_departement')->where('kd_jabatan',$id)->where('active','t')->first();
        return response()->json($departement); 
        // return $departement;
    }
}
