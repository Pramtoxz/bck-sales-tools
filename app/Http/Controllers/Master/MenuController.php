<?php

namespace App\Http\Controllers\Master;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MasterMenu;
use App\Models\ServiceApp;
use App\Models\UserMenu;

class MenuController extends Controller
{
    public function index(){
        $data['menu'] = ServiceApp::with('master_menu')->get();
        return view('pages.master.menu',$data);
    }

    public function menuUser(Request $r){
        $kd_karyawan = $r->kd_karyawan;
        $dataUserMenu = UserMenu::where("kd_karyawan",$kd_karyawan)->get();
        return response()->json($dataUserMenu);
    }

    public function simpanMenuUser(Request $r){
        $kd_karyawan = $r->kd_karyawan;
        $menu = $r->menu;
        $menu = explode(",",$menu);
        $data = [];
        try {
            DB::beginTransaction();
            $deleteMenu = UserMenu::where("kd_karyawan",$kd_karyawan)->delete();
            foreach($menu as $value){
                $data[] = [
                    "kd_karyawan"=>$kd_karyawan,
                    "id_menu"=>$value
                ];
            }
            $insertMenu = UserMenu::insert($data);
            DB::commit();
            return response()->json([
                "code"=>200,
                "status"=>"true",
                "message"=>"Sukses Update Menu User",
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "code"=>400,
                "status"=>"false",
                "message"=>"Gagal Update Menu User",
            ]);
        }
        

    }
}
