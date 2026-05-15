<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Exports\KaryawanExport;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helper\Helper;
use App\Imports\KaryawanImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Agama;
use App\Models\Status;
use App\Models\Jabatan;
use App\Models\Departement;
use App\Models\Pendidikan;
use App\Helper\Menu;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    public function index(Request $r)
    {
        return view('pages.master.karyawan');
    }

    public function get(Request $r){
        $data=Karyawan::leftjoin('public.jabatan','jabatan.kd_jabatan','karyawan.kd_jabatan')->select('karyawan.nama_lengkap','karyawan.tempat_lahir','karyawan.tanggal_lahir','karyawan.kd_departement','jabatan.nama_jabatan','karyawan.jenis_kelamin','karyawan.id')->where('karyawan.active','t');
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
        ->editColumn('jenis_kelamin',function($q){
            if($q->jenis_kelamin == "L"){
                return "Laki Laki";
            }else if($q->jenis_kelamin == "P"){
                return "Perempuan";
            }else{
                return "-";
            }
        })
        ->editColumn('tanggal_lahir',function($q){
            return date('d-m-Y',strtotime($q->tanggal_lahir));
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function save(Request $r){
        $kodeKaryawan = Helper::getKodeUniqueId("K");
        try {
            $rules = [
                'nama_lengkap' => 'required',
                'nama_panggilan' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir'=>'required',
                'jk' => 'required',
                'pendidikan' => 'required',
                'agama' => 'required',
                'alamat' => 'required',
                'sts' => 'required',
                'nama_susis' => '',
                'jumlah_anak' => '',
                'nama_ibu' => 'required',
                'notelp' => 'required',
                'tanggal_gabung' => 'required',
                'jbt' => 'required',
                'departement' => 'required',
                'no_kk' => 'required|numeric',
                'noker' => '',
                'nokes' => '',
                'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'kd_jabatan_wlk' => ''
            ];
            if($r->tipe_submit == "add"){
                $rules['email'] = 'required|email|unique:pgsql.public.users,email';
                $rules['no_ktp'] = 'required|numeric|unique:pgsql.public.karyawan,no_ktp';
            }else{
                $dataUser = Karyawan::where('karyawan.id',$r->id_data)->leftjoin('public.users','users.kd_karyawan','karyawan.kd_karyawan')->select('users.id')->first();
                $id_user = $dataUser->id ?? null;
                $rules['email'] = 'required|email|unique:pgsql.public.users,email,' . $id_user . ',id';
                $rules['no_ktp'] = 'required|numeric|unique:pgsql.public.karyawan,no_ktp,' . $r->id_data . ',id';
            }

            $message = [
                "nama_lengkap.required"=> "Nama Lengkap Wajib Diisi",
                "nama_panggilan.required"=> "Nama Panggilan Wajib Diisi",
                "tempat_lahir.required"=>"Tempat lahir Wajib Diisi",
                "tanggal_lahir.required"=>"Tanggal Lahir Wajib Diisi",
                "jk.required"=>"Jenis kelamin Wajib Diisi",
                "pendidikan.required"=> "Pendidikan Wajib Diisi",
                "agama.required"=>"Agama Wajib Diisi",
                "alamat.required"=>"Alamat Wajib Diisi",
                "sts.required"=>"Status Wajib Diisi",
                "nama_ibu.required"=>"Nama Ibu Wajib Diisi",
                "notelp.required"=>"No. Handphone Wajib Diisi",
                "tanggal_gabung.required"=>"Tanggal Gabung Wajib Diisi",
                "departement.required"=> "Departement Wajib Diisi",
                "no_ktp.required"=>"No. KTP Wajib Diisi",
                "no_kk.required"=>"No. KK Wajib Diisi",
                "foto.mimes"=>"Foto diupload dengan extension .jpeg .png .jpg .gif .svg",
                "foto.max"=>"Foto Max Size Upload 10 MB.",
                "email.required"=>"Email Wajib Diisi",
                "email.email"=>"Format Email Invalid",
                "email.unique"=>"Email Sudah Pernah Digunakan",
                "no_ktp.unique"=>"No KTP Sudah Pernah Digunakan",
            ];
            $validator = Validator::make($r->all(), $rules,$message);
            if ($validator->fails()) {
                return response()->json($validator->errors()->first(),422);
            }
            DB::beginTransaction();
            if($r->tipe_submit == "add"){
                $fileName=null;
                if($r->hasFile('foto')){
                    $files=$r->file('foto');
                    $fileName=date('YmdHis'). "." .$files->getClientOriginalExtension();
                    $path = Storage::put('karyawan'.'/'.$fileName,file_get_contents($files));
                    if(!$path){
                        $fileName = null;
                    }
                }
                $save = Karyawan::insert([
                    "kd_karyawan"=>$kodeKaryawan,
                    "nama_lengkap"=>strtoupper($r->nama_lengkap),
                    "nama_panggilan"=>ucwords($r->nama_panggilan),
                    "jenis_kelamin"=>$r->jk,
                    "kd_agama"=>$r->agama,
                    "kd_status"=>$r->sts,
                    "jumlah_anak"=>$r->jumlah_anak,
                    "no_hp"=>$r->notelp,
                    "kd_jabatan"=>$r->jbt,
                    "no_ktp"=>$r->no_ktp,
                    "no_ketenagakerjaan"=>$r->noker,
                    "tempat_lahir"=>strtoupper($r->tempat_lahir),
                    "tanggal_lahir"=>$r->tanggal_lahir, 
                    "kd_pendidikan"=>$r->pendidikan,
                    "alamat"=>$r->alamat, 
                    "nama_pasangan"=>strtoupper($r->nama_susis),  
                    "nama_ibu"=>strtoupper($r->nama_ibu),  
                    "tanggal_bergabung"=>$r->tanggal_gabung,
                    "kd_departement"=>$r->departement,  
                    "no_kk"=>$r->no_kk,
                    "no_kesehatan"=>$r->nokes,
                    "kode_jabatan_wlk"=>$r->kd_jabatan_wlk,
                    "npwp"=>$r->npwp,
                    "foto"=>$fileName,
                    "active"=>"t",
                    "created_at"=>date('Y-m-d H:i:s'),
                    "updated_at"=>date('Y-m-d H:i:s'),
                    "id_absensi"=>$r->id_absensi
                ]);
                if(!$save){
                    throw new \Exception("Gagal Simpan Karyawan",1);
                }
                $saveUser = User::insert([
                    "email"=>$r->email,
                    "password"=>Hash::make('12345'),
                    "kd_karyawan"=>$kodeKaryawan,
                    "is_admin"=>"f",
                    "created_at"=>date('Y-m-d H:i:s'),
                    "updated_at"=>date('Y-m-d H:i:s'),
                    "name"=>$r->nama_panggilan,
                    "is_verifikasi"=>"f"
                ]);
                if(!$saveUser){
                    throw new \Exception("Gagal Generate User",1);
                }
            }else{
                $karyawan = Karyawan::find($r->id_data);
                $karyawan->nama_lengkap = $r->nama_lengkap;
                $karyawan->nama_panggilan = $r->nama_panggilan;
                $karyawan->jenis_kelamin = $r->jk;
                $karyawan->kd_agama = $r->agama;
                $karyawan->kd_status = $r->sts;
                $karyawan->jumlah_anak = $r->jumlah_anak;
                $karyawan->no_hp = $r->notelp;
                $karyawan->kd_jabatan = $r->jbt;
                $karyawan->no_ktp = $r->no_ktp;
                $karyawan->no_ketenagakerjaan = $r->noker;
                $karyawan->tempat_lahir = $r->tempat_lahir;
                $karyawan->tanggal_lahir = $r->tanggal_lahir;
                $karyawan->kd_pendidikan = $r->pendidikan;
                $karyawan->alamat = $r->alamat;
                $karyawan->nama_pasangan = $r->nama_susis;
                $karyawan->nama_ibu = $r->nama_ibu;
                $karyawan->tanggal_bergabung = $r->tanggal_gabung;
                $karyawan->kd_departement = $r->departement;
                $karyawan->no_kk = $r->no_kk;
                $karyawan->no_kesehatan = $r->nokes;
                $karyawan->kode_jabatan_wlk = $r->kd_jabatan_wlk;
                $karyawan->npwp = $r->npwp;
                $karyawan->active = $r->active;
                $karyawan->id_absensi = $r->id_absensi;
                if($r->hasFile('foto')){
                    $foto_lama = $karyawan->foto;
                    $files=$r->file('foto');
                    $fileName=date('YmdHis'). "." .$files->getClientOriginalExtension();
                    $path = Storage::put('karyawan'.'/'.$fileName,file_get_contents($files));
                    if($path){
                        $karyawan->foto = $fileName;
                        if($foto_lama != null){
                            // hapus foto setelah edit
                            Storage::delete('karyawan'."/".$foto_lama);
                        }
                    }
                }
                User::where('kd_karyawan',$karyawan->kd_karyawan)->update([
                    "email"=>$r->email,
                    "name"=>$r->nama_panggilan
                ]);
                $save = $karyawan->save();
            }
            if($save){
                DB::commit();
                return response()->json([
                    "code"=>200,
                    "status"=>"true",
                    "message"=>"Sukses",
                ]);
            }else{
                DB::rollBack();
                return response()->json([
                    "code"=>400,
                    "status"=>"false",
                    "message"=>"Failed",
                ]);
            }
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([$th->getMessage()],500);
        }
    }

    public function show(Request $r){
        $id = $r->id;
        $karyawan = Karyawan::where('karyawan.id',$id)->leftjoin('public.users','users.kd_karyawan','karyawan.kd_karyawan')->select('karyawan.*','users.email')->first();
        return response()->json($karyawan);
    }

    public function delete(Request $r){
        try {
            DB::beginTransaction();
            $id = $r->id;
            $karyawan = Karyawan::find($id);
            $deleteUser = User::where('kd_karyawan',$karyawan->kd_karyawan)->delete();
            $delete = $karyawan->delete();
            if($delete && $deleteUser){
                DB::commit();
                return response()->json([
                    "code"=>200,
                    "status"=>"true",
                    "message"=>"Sukses Hapus Data",
                ]);
            }else{
                DB::rollBack();
                return response()->json([
                    "code"=>400,
                    "status"=>"false",
                    "message"=>"Gagal Hapus Data",
                ]);
            }
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([$th->getMessage()],500);
        }
        
    }

    public function all(){
        $karyawan = DB::table('karyawan')->select(['kd_karyawan as id','nama_lengkap as text'])->where('active','t')->orderBy('nama_lengkap','asc')->get();
        return response()->json($karyawan);
       
    }

    public function import(Request $r){
        $rules = [
            'upload_file' => 'required|mimes:csv,xlsx,xls'
        ];
        $message = [
            "upload_file.required"=> "File Tidak Boleh Kosong",
        ];
        $validator = Validator::make($r->all(), $rules,$message);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),422);
        }
        try{  
            // Excel::import(new KaryawanImport(), $r->file('upload_file'));
            $collection = Excel::toCollection(new KaryawanImport, $r->file('upload_file'));
            $sheet1 = $collection[0];
            $success = 0;
            $gagal = 0;
            $messageResponse = "";
            foreach($sheet1 as $value){
                $tanggal = date("Y-m-d",($value[11] - 25569) * 86400);
                $tanggal_bergabung = date("Y-m-d",($value[16] - 25569) * 86400);
                $namaPanggilan = explode(" ",$value[0]);
                $namaPanggilan = count($namaPanggilan) >= 1 ? $namaPanggilan[0] : $value[1];
                try {
                    // row nama lengkap
                    $namaLengkap = $value[1];
                    DB::beginTransaction();
                    $kodeKaryawan = Helper::getKodeUniqueId("K");
                    // row email
                    $email = $value[21];
                    $emailExists = User::where('email',$email)->exists();
                    if($emailExists){
                        throw new \Exception($namaLengkap . " Email Sudah Digunakan ".$email."</br>",1);
                    }
                    // row ktp
                    $no_ktp = $value[8];
                    $cekKTP = Karyawan::where('no_ktp',$no_ktp)->exists();
                    if($cekKTP){
                        throw new \Exception($namaLengkap . " No KTP Sudah Digunakan ".$no_ktp."</br>",1); 
                    }
                    $user = User::insert([
                        "email"=>$email,
                        "password"=>Hash::make('12345'),
                        "kd_karyawan"=>$kodeKaryawan,
                        "is_admin"=>"f",
                        // row panggilan
                        "name"=>$namaPanggilan,
                        "is_verifikasi"=>"f",
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    if(!$user){
                        throw new \Exception($namaLengkap . " Gagal Generate User Email ".$email."</br>",1);
                    }
                    $karyawan = Karyawan::insert([
                        'kd_karyawan' => $kodeKaryawan,
                        'nama_lengkap' => $value[0],
                        'nama_panggilan' => $namaPanggilan,
                        'jenis_kelamin' => $this->convertJenisKelamin($value[2]),
                        'kd_agama' => $this->convertAgama($value[3]),
                        'kd_status' => $this->convertStatus($value[4]),
                        'jumlah_anak' => $value[5],
                        'no_hp' => $value[6],
                        'kd_jabatan' => $this->convertJabatan($value[7]),
                        'no_ktp' => $no_ktp,
                        'no_ketenagakerjaan' => $value[9],
                        'tempat_lahir' => $value[10],
                        'tanggal_lahir' => $tanggal,
                        'kd_pendidikan' => $value[12],
                        'alamat' => $value[13],
                        'nama_pasangan' => $value[14],
                        'nama_ibu' => $value[15],
                        'tanggal_bergabung' => $tanggal_bergabung,
                        'kd_departement' => $this->convertDepartement($value[17]),
                        'no_kk' => $value[18],
                        'no_kesehatan' => $value[19],
                        'kode_jabatan_wlk' => $value[20],
                        'npwp' => null,
                        'active' => "t",
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    if(!$karyawan){
                        throw new \Exception($namaLengkap . " Gagal Generate Karyawan No KTP ".$no_ktp."</br>",1);
                    }
                    $success += 1;
                    DB::commit();
                } catch (\Exception $th) {
                    $gagal += 1;
                    $messageResponse .= $th->getMessage();
                    DB::rollBack();
                }
            }

            return response()->json([
                "code"=>200,
                "status"=>"true",
                "message"=>"Sukses Import Data",
                "data"=>[
                    "success"=>$success,
                    "gagal"=>$gagal,
                    "message"=>$messageResponse
                ]
            ]);
        }catch(\Exception $ex){
            return response()->json([
                "code"=>400,
                "status"=>"false",
                "message"=>$ex->getMessage(),
                "data"=>[]
            ]);
        }
    }

    public function downloadTemplate(){
        return Excel::download(new KaryawanExport, 'Template Upload Data Karyawan.xlsx');
    }

    public function getListKaryawan(Request $r){
        $departement = $r->departement;
        if($departement != ""){
            $exDepartement = explode(',',$departement);
        }else{
            $exDepartement = [];
        }
        $karyawan = Karyawan::where('active','t')->select("karyawan.id","karyawan.nama_lengkap","karyawan.tanggal_bergabung","karyawan.kd_departement","karyawan.foto","karyawan.kd_jabatan")
        ->when($departement != "",function($q) use($exDepartement){
            $q->whereIn('karyawan.kd_departement',$exDepartement);
        })
        ->with(['jabatan'])->orderBy('nama_lengkap','ASC')->get();
        return response()->json($karyawan);
    }

    private function convertAgama($string){
        return Agama::where('nama_agama',$string)->select("kd_agama")->first()->kd_agama ?? null;
    }

    private function convertStatus($string){
        return Status::where('nm_status',$string)->select("kd_status")->first()->kd_status ?? null;
    }

    private function convertJabatan($string){
        return Jabatan::where('nama_jabatan',$string)->select("kd_jabatan")->first()->kd_jabatan ?? null;
    }

    private function convertDepartement($string){
        return Departement::where('deskripsi',$string)->select("kd_departement")->first()->kd_departement ?? null;
    }

    private function convertJenisKelamin($string){
        // if($string == "Laki-Laki"){
        if($string == "L"){
            return "L";
        }else{
            return "P";
        }
    }

    public function getStatus(){
        $status = DB::table('status')->select('kd_status as id','nm_status as text')->get();
        return response()->json($status);
    }

    public function getFoto(){
        $user = auth()->user()->kd_karyawan;
        $foto=DB::table('public.users')
        ->join('public.karyawan', 'users.kd_karyawan', '=', 'karyawan.kd_karyawan')
        ->select('users.kd_karyawan','karyawan.foto')
        ->where('karyawan.kd_karyawan',$user )
        ->get();  
        
        return response()->json($foto);
    }
    public function lihat($id=null){
        if($id == null){
            $id = Auth::user()->karyawan->id ?? null;
        }
        return view('pages.lms.karyawanlihat.index', ['id' => $id]);
    }
    public function lihatdetail(Request $r){
        $id=$r->id;
        $data=Karyawan::leftjoin('public.jabatan','jabatan.kd_jabatan','karyawan.kd_jabatan')
        ->leftjoin('public.departement','departement.kd_departement','karyawan.kd_departement')
        ->leftjoin('public.users','users.kd_karyawan','karyawan.kd_karyawan')
        ->with([
            'historyjob'=>function ($query){
                $query->leftjoin('public.jabatan','history_job.kd_jabatan','jabatan.kd_jabatan')
                ->select('jabatan.kd_jabatan','jabatan.nama_jabatan','history_job.kd_karyawan','history_job.mulai_menjabat','history_job.akhir_menjabat');
            }])

        ->select('karyawan.kd_karyawan','karyawan.nama_lengkap','karyawan.tempat_lahir','karyawan.tanggal_lahir',
        'karyawan.kd_departement','karyawan.foto','karyawan.alamat','karyawan.no_hp','karyawan.tanggal_bergabung','karyawan.no_ketenagakerjaan','karyawan.no_kesehatan','users.email','departement.deskripsi',
        'jabatan.nama_jabatan','karyawan.jenis_kelamin','karyawan.id')
        ->where('karyawan.active','t')
        ->where('karyawan.id',$id)
        ->first();

        return response()->json($data);
      
    }

    public function lihatdetailtraining(Request $r){
        $id=$r->id;
        $data=Karyawan::join('lms.peserta_training','karyawan.kd_karyawan','peserta_training.kd_karyawan')
        ->Join('lms.event_training','peserta_training.kd_event_training','event_training.kd_event_training')
        ->Join('lms.training','event_training.kd_training','training.kd_training')
        ->select('lms.peserta_training.kd_karyawan','lms.peserta_training.nilai_post_test','lms.event_training.kd_event_training','event_training.tanggal_mulai','lms.training.kd_training','lms.training.nama_training')
        ->where('karyawan.id',$id)
        ->get();
        // return $data;
        return DataTables::of($data)
        ->addColumn('Ket_post_test',function($data){
            if($nilai_post_test=$data->nilai_post_test)
            {
                if($nilai_post_test>70){
                    $ket='Lulus';
                }else{
                    $ket="Tidak Lulus";
                }
            }else{
                return;
            }
          
            return $ket;
        })
        ->editColumn('tanggal_mulai',function($data){
            if($data->tanggal_mulai){
                return date('d-m-Y',strtotime($data->tanggal_mulai));
            }
        }) 
        ->make(true);
      
    }
}
