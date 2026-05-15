<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PendidikanController extends Controller
{
    public function index(){
        $pendidikan = DB::table('pendidikan')->select(['kd_pendidikan as id','nm_pendidikan as text'])->get();
        return response()->json($pendidikan);
    
    }
}
