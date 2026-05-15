<?php

namespace App\Http\Controllers\Master;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AgamaController extends Controller
{
    public function index(){
        $agama = DB::table('agama')->select(['kd_agama as id','nama_agama as text'])->get();
        return response()->json($agama);
    
    }
}
