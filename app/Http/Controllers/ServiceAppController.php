<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\ServiceApp;
use App\Models\UserMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ServiceAppController extends Controller
{
    public function index(Request $r)
    {
        if(Auth::user()->is_admin == "t"){
            $data['service_app'] = ServiceApp::orderBy("created_at","ASC")->get();
        }else{
            $kd_karyawan = Auth::user()->kd_karyawan;
            $listServiceApps = UserMenu::where("kd_karyawan",$kd_karyawan)->join("public.master_menu",'master_menu.id','user_menu.id_menu')->select("master_menu.kd_service_apps")->groupBy("kd_service_apps")->get()->pluck("kd_service_apps");
            $data['service_app'] = ServiceApp::whereIn("kd_service_apps",$listServiceApps)->orderBy("created_at","ASC")->get();
            // check jika service app yg tersedia cuma 1 maka langsung masuk
        }
        if(count($data['service_app']) == 1){
            Session::put("kd_service_apps",$data['service_app'][0]["kd_service_apps"]);
            return redirect()->route('home');
        }
        return view('pages.serviceapp.index',$data);
    }

    public function pilih($kd_service_apps){
        $existsService = ServiceApp::where('kd_service_apps',$kd_service_apps)->exists();
        if(!$existsService){
            return "Tidak Terdaftar";
        }
        Session::put("kd_service_apps",$kd_service_apps);
        return redirect()->route('home');
    }
}
