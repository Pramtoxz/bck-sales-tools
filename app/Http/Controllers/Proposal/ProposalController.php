<?php

namespace App\Http\Controllers\proposal;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\proposal\Proposal;
use Yajra\DataTables\Facades\DataTables;

class ProposalController extends Controller
{
    public function index(){
        return view('pages.proposal.proposal');
    }
    public function get(){
        $data=Proposal::get();
        return DataTables::of($data)
        ->addColumn('aksi',function($q){
            $viewAction = '<button onClick="view(' . "'$q->id'" . ')" class="btn btn-warning btn-sm info waves-effect waves-light" title="Info" data-bs-toggle="modal" data-bs-target="#containerModal">
                <i class="fa fa-eye" title="View Detail"></i> View Detail
            </button>';
            $editAction = '<button onClick="editData(' . "'$q->id'" . ')"  class="btn btn-danger btn-sm delete waves-effect waves-light" title="Delete">
                <i class="fas fa-pencil-alt" title="Delete"></i> Edit Proposal
            </button>';
            $submitAction = '<button onClick="deleteData(' . "'$q->id'" . ')"  class="btn btn-danger btn-sm delete waves-effect waves-light" title="Delete">
            <i class="fas fa-trash" title="Delete"></i> Ajukan Proposal
            </button>';
            $action = '<span>'.$viewAction." ".$editAction." ". $submitAction.'</span>' ;
            return $action;
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }
    public function save(){
        
    }
    public function getapproval(){ 

            $approval=DB::table('karyawan')
            ->select('kd_karyawan','nama_lengkap',
            DB::raw("(
                CASE WHEN kd_karyawan='K-53259-104035' THEN 'GA'
                     WHEN kd_karyawan='K1' THEN 'GM' ELSE 'Kabag' END) as role"))
            ->get();
            return response()->json($approval);
    }
}
