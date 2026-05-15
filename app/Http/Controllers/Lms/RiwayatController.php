<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;


class RiwayatController extends Controller
{
    public function index()
    {
        return view('pages.lms.riwayat.index');
    }
    public function get(Request $r){
        $departement = $r->departement;
        if($departement==="null") {
            // return response()->json([]);
            $data=Karyawan::join('public.jabatan','karyawan.kd_jabatan','=','jabatan.kd_jabatan')
            ->where('karyawan.active','t')  
            ->select('karyawan.id','karyawan.kd_karyawan','karyawan.nama_lengkap','karyawan.kd_departement','karyawan.jenis_kelamin','jabatan.nama_jabatan')
             ->get(); 
        }else{
            $data=Karyawan::join('public.jabatan','karyawan.kd_jabatan','=','jabatan.kd_jabatan')
            ->where('karyawan.active','t') 
            ->where('karyawan.kd_departement',$departement) 
            ->select('karyawan.id','karyawan.kd_karyawan','karyawan.nama_lengkap','karyawan.kd_departement','karyawan.jenis_kelamin','jabatan.nama_jabatan')
            ->get();
        }
        return DataTables::of($data)
        ->addColumn('aksi',function($q){
            $viewAction = '<button onClick="viewData(' . "'$q->kd_karyawan'" . ')" class="btn btn-warning btn-sm edit waves-effect waves-light" title="View" data-bs-toggle="modal" data-bs-target="#containerModalTambah">
                <i class="fas fa-eye" title="View"></i>
            </button>';
            $action = '<span>'.$viewAction.'</span>';
            return $action;
        })
        ->editColumn('jenis_kelamin',function($q){
            if($q->jenis_kelamin == "L"){
                return "Laki Laki";
            }else if($q->jenis_kelamin == "P"){
                return "Perempuan";
            }else{
                return "-";
            }
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function indexUser(){
        return view('pages.lms.riwayatUser.index');
    }
    public function downloadSoalPreTest($path){
        return Storage::download("doc_pre_test/{$path}");
    }
    public function downloadJawabanPreTest($path){
        return Storage::download("jawaban_pre_test/{$path}");
    }
    public function downloadSoalPostTest($path){
        return Storage::download("doc_post_test/{$path}");
    }
    public function downloadJawabanPostTest($path){
        return Storage::download("jawaban_post_test/{$path}");
    }
  
}
