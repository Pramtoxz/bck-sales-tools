<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $r)
    {
        return view('pages.profile.index');
    }
    public function get()
    {
        $user=auth()->user()->kd_karyawan;
        $profile = Karyawan::join('users','karyawan.kd_karyawan','=','users.kd_karyawan')
        ->select('karyawan.*','users.email')
        ->where('karyawan.kd_karyawan',$user)->first();
        return response()->json($profile);

    }
    public function getHistory()
    {
        $user = auth()->user()->kd_karyawan;
        $data = DB::table('history_job')
        ->join('karyawan', 'history_job.kd_karyawan', '=', 'karyawan.kd_karyawan')
        ->join('departement', 'history_job.kd_departement', '=', 'departement.kd_departement')
        ->join('jabatan', 'history_job.kd_jabatan', '=', 'jabatan.kd_jabatan')
        ->select('history_job.id','karyawan.nama_lengkap','departement.deskripsi','jabatan.nama_jabatan','history_job.mulai_menjabat','history_job.akhir_menjabat','history_job.resign')
        ->where('karyawan.kd_karyawan',$user)
        ->get();  
            return DataTables::of($data)
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
            ->make(true);
    }
    public function changePassword(Request $r)
    {
        // $user = auth()->user()->kd_karyawan;
        $user = auth()->user();
        // try{
            $validator=Validator::make($r->all(),[
                'old_password'=>[
                    'required', function($attribute,$value,$fail)use ($user) {
                        if (!Hash::check($value, $user->password)) {
                            $fail('The current password is incorrect');
                        }
                        // return $fail;
                        // if(!Hash::check($value,Auth::user()->password)){
                        //     return $fail(__('The current password is incorrect'));
                        // }
                    },
                    'min:3',
                    'max:30'
                ],
                'new_password'=>'required|min:8|max:30',
                'retype_new_password' => 'required|same:new_password',
            ],[
                'old_password.required'=>'Enter your current password',
                'old_password.min'=>'old password must have atleast 3 characters',
                'old_password.max'=>'old password not be greater than 30 character',
                'new_password.required'=>'Enter New Password',
                'new_password.min'=>'New password must have atleast 8 character',
                'new_password.max'=>'New Password not be greater than 30 character',
                'retype_new_password.required'=>'ReEnter your new password',
                'retype_new_password.same'=>'New Password and confirm new password must match'
            ]);

            // if(!$validator->passes()){
            if($validator->fails()){
                return response()->json(['status'=>0,'error'=>$validator->errors()->toArray()]);
            }else{
                $update=User::find(Auth::user()->id)->update(['password'=>Hash::make($r->new_password)]);
            }
            if(!$update){
                return response()->json(['status'=>0,'msg'=>'Something went wrong,Failed to update password in db']);
            }else{
                return response()->json(['status'=>1,'msg'=>'Your password has been changed succesfully']);
            }
    }
}
