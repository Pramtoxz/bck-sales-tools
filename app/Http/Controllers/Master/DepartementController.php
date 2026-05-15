<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DepartementController extends Controller
{
    public function index()
    {
        return view('pages.master.departement');
    }

    public function get(Request $r){
        $data = Departement::get();
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
                'kd_departement' => 'required',
                'deskripsi' => 'required',
                'active' => 'required',
            ];
            $message = [
                "kd_departement.required"=> "Kode Departement Wajib Diisi",
                "deskripsi.required"=>"Deskripsi Wajib Diisi",
                "active.required"=>"Status Active Wajib Diisi",
            ];
            $validator = Validator::make($r->all(), $rules,$message);
            if ($validator->fails()) {
                return response()->json($validator->errors()->first(),422);
            }
            if($r->tipe_submit == "add"){
                $save = Departement::insert([
                    "kd_departement"=>$r->kd_departement,
                    "deskripsi"=>ucwords(strtolower($r->deskripsi)),
                    "active"=>$r->active,
                    "created_at"=>date('Y-m-d H:i:s'),
                    "updated_at"=>date('Y-m-d H:i:s')
                ]);
            }else{
                $save = Departement::where('id',$r->id_data)->update([
                    "kd_departement"=>$r->kd_departement,
                    "deskripsi"=>ucwords(strtolower($r->deskripsi)),
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
        return response()->json(Departement::find($id));
    }

    public function delete(Request $r){
        $id = $r->id;
        $delete = Departement::find($id)->delete();
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
        $departement = DB::table('departement')->select(['kd_departement as id','deskripsi as text'])->where('active','t')->get();
        return response()->json($departement);
       
    }

    // public function filterr($id){
    //     $departement = DB::table('departement')->select('kd_departement')->where('kd_jabatan',$id)->get();
    //     // return response()->json($departement); 
    //     return $departement;
    // }
}
