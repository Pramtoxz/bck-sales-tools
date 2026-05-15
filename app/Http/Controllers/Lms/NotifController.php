<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Karyawan;
use App\Models\User;
use App\Notifications\manualNotification;
use Illuminate\Support\Facades\Notification;

class NotifController extends Controller
{
    public function index()
    {
        return view('pages.lms.notifTraining.index');
    }
    public function get()
    {
        // $data=DB::table('public.notifications')->select('id','data')->get();
        $data = DB::table('notifications')
            ->select('notifications.id', 'notifications.data','notifications.read_at', 'users.name as user_name')
            ->leftJoin('users', 'notifications.notifiable_id', '=', 'users.id')
            ->where('notifications.notifiable_type', '=', 'App\\Models\\User')
            ->orderBy('notifications.created_at', 'desc')
            ->get();

        return DataTables::of($data)
        ->addColumn('title', function ($data) {
            $data = json_decode($data->data, true); 
            return $data['title'] ?? null;
        })
        ->addColumn('message', function ($data) {
            $data = json_decode($data->data, true);
            return $data['message'] ?? null;
        })
        ->addColumn('user_name', function ($data) {
            return $data->user_name ?? 'Unknown';
        })
        ->addColumn('aksi',function($q){
            $deleteAction = "";
                $deleteAction = '<button onClick="deleteData(' . "'$q->id'" . ')"  class="btn btn-danger btn-sm delete waves-effect waves-light" title="Delete">
                <i class="fas fa-trash" title="Delete"></i>
                </button>';
            
            $action = '<span>'.$deleteAction.'</span>';
            return $action;
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }
    public function delete(Request $r)
    {
        $id = $r->id;
        $delete = DB::table('notifications')
        ->where('id', $id)
        ->delete();

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
    public function peserta()
    {
        $dataKaryawan = Karyawan::leftjoin('public.jabatan','jabatan.kd_jabatan','karyawan.kd_jabatan')
        ->select('karyawan.nama_lengkap','karyawan.kd_departement','jabatan.nama_jabatan','karyawan.kd_karyawan')->orderBy('karyawan.kd_departement','ASC');
        return DataTables::of($dataKaryawan)
        ->addColumn('aksi',function($q){
                return '<input class="" type="checkbox" id="departement_karyawan_list" name="departement_karyawan_list" value="' . $q->kd_karyawan . '" >';   
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }
    public function pesertasave(Request $r){
        $title=$r->title;
        $message=$r->message;

        try {
            $arrayData = $r->arrayData;
            $userpenerima=User::whereIn('kd_karyawan',$arrayData)->get();     
            Notification::send($userpenerima,new manualNotification($title,$message));
            return response()->json([
                "code"=>200,
                "status"=>"true",
                "message"=>"Sukses Push Notification ke Peserta Training",
            ]);
        } catch (\Exception $th) {
            return response()->json([
                "code"=>400,
                "status"=>"false",
                "message"=>"Failed " . $th->getMessage(),
            ]);
        }
    }
}
