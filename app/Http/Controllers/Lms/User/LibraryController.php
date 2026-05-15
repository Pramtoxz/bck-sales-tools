<?php

namespace App\Http\Controllers\Lms\User;

use App\Http\Controllers\Controller;
use App\Models\LibraryFolder;
use App\Models\LibraryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class LibraryController extends Controller
{
    public function index()
    {
        return view('pages.lms.library.library');
    }

    public function getData()
    {
        //     $folders = LibraryFolder::join('public.karyawan', 'karyawan.kd_karyawan', '=', 'library_folder.id_pemilik')
        //     ->selectRaw("'Folder' as type, nama as name, NULL as size, id_pemilik, nama_lengkap, library_folder.updated_at, library_folder.id")
        //     ->getQuery();
        //      $files = LibraryFile::join('public.karyawan', 'karyawan.kd_karyawan', '=', 'library_file.id_pemilik')
        //     ->whereNull('id_folder')
        //     ->selectRaw("'File' as type, nama as name, file_size as size, id_pemilik, nama_lengkap, library_file.updated_at, library_file.id")
        //     ->getQuery();

        //      $union = $folders->unionAll($files);

        //      $data = DB::table(DB::raw("({$union->toSql()}) as combined"))
        //     ->mergeBindings($folders)
        //     ->orderByDesc('type')
        //     ->orderByDesc('updated_at')
        //     ->get();

        $user = auth()->user()->kd_karyawan;
        
        $folders = LibraryFolder::join('public.karyawan','karyawan.kd_karyawan','library_folder.id_pemilik')
        ->selectRaw("'Folder' as type, nama as name, NULL as size,id_pemilik,nama_lengkap, library_folder.updated_at,library_folder.id, NULL as download")
        ->getQuery();

        $files = LibraryFile::join('public.karyawan','karyawan.kd_karyawan','library_file.id_pemilik')
            ->whereNull('id_folder')
            ->selectRaw("'File' as type, nama as name, file_size as size,id_pemilik, nama_lengkap, library_file.updated_at,library_file.id, library_file.file as download")
            ->getQuery();

        $union = $folders->unionAll($files);

        $data = DB::table(DB::raw("({$union->toSql()}) as combined"))
            // ->mergeBindings($union)
            ->mergeBindings($folders)
            ->orderByDesc('type')
            ->orderByDesc('updated_at');
            // ->get();

        return DataTables::of($data)
            ->addColumn('aksi', function ($q) use($user) {
                if ($q->type == 'Folder') {
                    $type = '<button onClick="tambahFile(' . "'$q->id'" . ')" class="btn btn-primary btn-sm edit waves-effect waves-light" title="Tambah" data-bs-toggle="modal" data-bs-target="#containerModalTambah">
                    <i class="fas fa-folder" title="Tambah"></i>
                    </button>';
                } else {
                    $path = "storage/library/".$q->download;
                    $type='<a onClick="preview('."'$path'" .')" href="#" class="btn btn-sm btn-secondary"><i class="fas fa-download" title="Tambah"></i></a>';
                }

                if ($user == $q->id_pemilik){
                    if ($q->type == 'Folder') {
                        $editAction = '<button onClick="editDataFolder(' . "'$q->id'" . ')"  class="btn btn-warning btn-sm delete waves-effect waves-light" title="edit" data-bs-toggle="modal" data-bs-target="#modal_folder">
                        <i class="fas fa-pencil-alt" title="edit"></i>
                        </button>';
                    }else{
                        $editAction = '<button onClick="editDataFile(' . "'$q->id'" . ')"  class="btn btn-warning btn-sm delete waves-effect waves-light" title="edit" data-bs-toggle="modal" data-bs-target="#modal_file">
                        <i class="fas fa-pencil-alt" title="edit"></i>
                        </button>';  
                    }

                    $hapusAction = '<button onClick="deleteData(\'' . $q->id . '\', \'' . $q->type . '\')" class="btn btn-danger btn-sm delete waves-effect waves-light" title="Delete">
                    <i class="fas fa-trash" title="Delete"></i>
                    </button>';
                    
                    // $hapusAction = '<button onClick="deleteData(' . "'$q->id'" .',' . "'$q->type'" . ')"  class="btn btn-danger btn-sm delete waves-effect waves-light" title="Delete">
                    // <i class="fas fa-trash" title="Delete"></i>
                    // </button>';
                }else{
                    $editAction = '';
                    $hapusAction = '';
                }
                $action = '<span>'.$type." ".$editAction." ".$hapusAction.'</span>' ;
                return $action;

            })
            ->editColumn('name', function ($q) {
                if ($q->type == 'Folder') {
                    return '<i class="fas fa-folder" style=color:#ffbf53></i>' ." ". $q->name ;
                } else {
                    return '<i class="fas fa-file" style=color:#8ab4f8></i>' ." ".$q->name ;
                }
            })
            ->editColumn('size', function ($q) {
                return $q->type == 'File' ? number_format($q->size / 1024, 2).' KB' : '-';
            })
            ->editColumn('updated_at',function($q){
                return date('d-m-Y H:i:s',strtotime($q->updated_at));
            })
            ->rawColumns(['aksi','name'])
            ->make(true);
    }

    public function getData2()
    {
        $user = auth()->user()->kd_karyawan;
        $folders = LibraryFolder::get()
        ->map(function ($folder) {
            return [
                'id' => $folder->id,
                'name' => $folder->nama,
                'id_pemilik' => $folder->id_pemilik,
                'type' => 'folder',
                'size' => '-',
                'updated_at' => $folder->created_at->toDateTimeString(),
                'aksi' => '<a href="' . route('library.viewFolder', $folder->id) . '" class="btn btn-sm btn-primary">Open</a>'
            ];
        });

    $files = LibraryFile::whereNull('id_folder')
        ->get()
        ->map(function ($file) {
            return [
                'id' => $file->id,
                'name' => $file->nama,
                'id_pemilik' => $file->id_pemilik,
                'type' => 'file',
                'size' => number_format($file->file_size / 1024, 2) . ' KB',
                'updated_at' => $file->created_at->toDateTimeString(),
                'aksi' => '<a href="#" class="btn btn-sm btn-secondary">Download</a>'
            ];
        });

    $gabungData = $folders->merge($files);

    $sortData = $gabungData->sort(function ($a, $b) {
        if ($a['type'] === $b['type']) {
            return strcmp(strtolower($a['updated_at']), strtolower($b['updated_at']));
        }
        return $a['type'] === 'folder' ? -1 : 1;
    });

    return DataTables::of($sortData)->rawColumns(['aksi'])->make(true);
    }

    public function viewFile(Request $r)
    {
            $user = auth()->user()->kd_karyawan;
            $id = $r->id_folder;
            $ambilfile = LibraryFile::join('public.karyawan','karyawan.kd_karyawan','library_file.id_pemilik')
            ->select('library_file.id','library_file.nama','library_file.id_pemilik','library_file.updated_at',
            'library_file.file_size','library_file.file','karyawan.nama_lengkap')
            ->where('id_folder',$id)
            ->orderBy('updated_at','Desc')
            ->get();

            return DataTables::of($ambilfile)
            ->addColumn('aksi',function($q) use ($user){
                $path = "storage/library/".$q->file;
                $btnPreview = '<a onClick="preview('."'$path'" .')" class="btn btn-sm btn-secondary" href="#"><i class="fas fa-download" title="Preview"></i></a>';

                if ($user == $q->id_pemilik){
                    $editAction = '<button onClick="editDataFileFolder(' . "'$q->id'" . ')"  class="btn btn-warning btn-sm delete waves-effect waves-light" title="edit">
                    <i class="fas fa-pencil-alt" title="edit"></i>
                    </button>';
                    $hapusAction = '<button onClick="deleteData(\'' . $q->id . '\', \'' . 'blank' . '\')"  class="btn btn-danger btn-sm delete waves-effect waves-light" title="Delete">
                    <i class="fas fa-trash" title="Delete"></i>
                    </button>';
                }else{
                    $editAction = '';
                    $hapusAction = '';
                }
    
                $action = '<span>'.$btnPreview." ".$editAction." ".$hapusAction.'</span>' ;
                return $action;
            })
            ->addColumn('preview',function($q){
                    $path = "storage/library/".$q->file;
                    $btnPreview = '<a onClick="preview('."'$path'" .')" href="#"><i class="fas fa-download" title="Preview"></i> Preview</a>';
                    $action = '<span>'.$btnPreview.'</span>';
                    return $action;    
            })
            ->addColumn('type',function($q){
                return 'File';  
            })
            ->editColumn('size', function ($q) {
                return number_format($q->file_size / 1024, 2).' KB';
            })
            ->editColumn('nama', function ($q) {
                return '<i class="fas fa-file" style=color:#8ab4f8></i>' ." ".$q->nama ;
                
            })
            ->editColumn('updated_at',function($q){
                return date('d-m-Y H:i:s',strtotime($q->updated_at));
            })
            ->rawColumns(['aksi','preview','nama'])
            ->make(true);
        
    }

    public function saveFolder(Request $r)
    {
        $kd_karyawan = auth()->user()->kd_karyawan;
            try {
                $rules = [
                    'nama_folder'=>'required'
                ];
        
                $message = [
                    "nama_folder.required"=>"Nama Folder Wajib diisi"
                ];

                $validator = Validator::make($r->all(), $rules,$message);
                if ($validator->fails()) {
                    return response()->json($validator->errors()->first(),422);
                }
                DB::beginTransaction();
                if($r->tipe_submit_folder == "add"){
                    $save = LibraryFolder::insert([
                        "nama"=>$r->nama_folder,
                        "id_pemilik"=>$kd_karyawan,
                        "created_at"=>date('Y-m-d H:i:s'),
                        "updated_at"=>date('Y-m-d H:i:s'),
                    ]);
          
                }else{
                    $folder=LibraryFolder::find($r->id_data_folder);
                    $folder->nama=$r->nama_folder;
                    $folder->updated_at = date('Y-m-d H:i:s');

                    $save = $folder->save();
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

    public function saveFile(Request $r)
    {
        // return $r->all();
        $kd_karyawan = auth()->user()->kd_karyawan;
        // return $kd_karyawan;

        $rules = [
            'nama_file' => 'required',
            'file_upload' => 'nullable|file|mimes:pdf,docx,doc,xls,xlsx'
        ];

        $message = [
            "nama_file.required" => "Nama File Wajib diisi",
            "file_upload.mimes" => "Extension yang diizinkan : pdf,docx,doc,xls,xlsx",
        ];

        $validator = Validator::make($r->all(), $rules, $message);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 422);
        }

        try {
        DB::beginTransaction();
            $filename = null;
            $size = null;

        if ($r->hasFile('file_upload')) {
            $files = $r->file('file_upload');
            $filename = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $path = Storage::put('library/' . $filename, file_get_contents($files));
            // return $path;
            $size = $files->getSize();

            if (!$path) {
                throw new \Exception("Gagal upload file");
            }
        }
        // return $r->tipe_submit_file;

        if ($r->tipe_submit_file == "add") {
            // return 'ok';
            $save = LibraryFile::insert([
                "id_folder" => $r->id_folder,
                "nama" => $r->nama_file,
                "id_pemilik" => $kd_karyawan,
                "created_at" => now(),
                "updated_at" => now(),
                "file_size" => $size,
                "file" => $filename,
            ]);
    
        } else {
            $file = LibraryFile::findOrFail($r->id_data_file);

            $file->nama = $r->nama_file;
            $file->updated_at = now();

   
            // if ($r->filled('id_folder')) {
            //     $file->id_folder = $r->id_folder;
            // }
  
            if ($filename) {
                if ($file->file && Storage::exists('library/' . $file->file)) {
                    Storage::delete('library/' . $file->file);
                }
                $file->file = $filename;
                $file->file_size = $size;
            }

            $save = $file->save();
        }

        if ($save) {
            DB::commit();
            return response()->json([
                "code" => 200,
                "status" => "true",
                "message" => "Sukses simpan data file",
            ]);
        } else {
            DB::rollBack();
            return response()->json([
                "code" => 400,
                "status" => "false",
                "message" => "Gagal simpan data file",
            ]);
        }
    } catch (\Exception $th) {
        DB::rollBack();
        return response()->json([
            "code" => 500,
            "status" => "false",
            "message" => "Terjadi kesalahan: " . $th->getMessage()
        ], 500);
    }
}

    // public function saveFile(Request $r)
    // {
    //     $kd_karyawan = auth()->user()->kd_karyawan;

    //     $rules = [
    //         'nama_file'=>'required',
    //         'file_upload' => 'file|mimes:pdf,docx,doc,xls,xlsx'
    //     ];

    //     $message = [
    //         "nama_file.required"=>"Nama File Wajib diisi",
    //         "file_upload.mimes" => "Extension yang diizinkan : pdf,docx,doc,xls,xlsx",
    //     ];

    //     $validator = Validator::make($r->all(), $rules,$message);
    //     if ($validator->fails()) {
    //         return response()->json($validator->errors()->first(),422);
    //     }

    //         try {
    //             $filename=null; 
    //                 if($r->hasFile('file_upload')){
    //                     $files=$r->file('file_upload');
    //                     $filename=date('YmdHis'). "." .$files->getClientOriginalExtension();
    //                     $path = Storage::put('library'.'/'.$filename,file_get_contents($files));
    //                     $size = $files->getSize();

    //                     if(!$path){
    //                         $filename = null;
    //                     }
    //                 }else{
    //                     throw new \Exception("Tidak Ada File Yang Diupload",1);
    //                 }
    //                 DB::beginTransaction();
    //                 if($r->tipe_submit_file == "add"){
    //                     $save = LibraryFile::insert([
    //                         "id_folder"=>$r->id_folder,
    //                         "nama"=>$r->nama_file,
    //                         "id_pemilik"=>$kd_karyawan,
    //                         "created_at"=>date('Y-m-d H:i:s'),
    //                         "updated_at"=>date('Y-m-d H:i:s'),
    //                         "file_size"=>$size,
    //                         "file"=> $filename,
    //                     ]);

    //                 }else{

    //                     $file=LibraryFile::find($r->id_data_file);
    //                     $file->nama=$r->nama_file;
    //                     $file->updated_at = date('Y-m-d H:i:s');

    //                     $file_lama=$file->file;

    //                     if($path){
    //                         $file->file = $filename;
    //                         $file->file_size = $size;
    //                         if($file_lama != null){
    //                             Storage::delete('library'."/".$file_lama);
    //                         }
    //                     }
    
    //                     $save = $file->save(); 
    //                 }

    //             if($save){
    //                 DB::commit(); 

    //                 return response()->json([
    //                     "code"=>200,
    //                     "status"=>"true",
    //                     "message"=>"Sukses",
    //                 ]);
    //             }else{
    //                 return response()->json([
    //                     "code"=>400,
    //                     "status"=>"false",
    //                     "message"=>"Failed",
    //                 ]);
    //             }
    //         } catch (\Exception $th) {
    //             DB::rollBack();
    //             return response()->json([$th->getMessage()],500);
    //         }
        
    // }

    public function hapusData(Request $r)
    {
        try {
            DB::beginTransaction();
            $id=$r->id;
            $type=$r->type;
            $berhasilHapus=false;
    
            if ($type=='Folder') {
                $files=LibraryFile::where('id_folder', $id)->get();
    
                foreach ($files as $file) {
                    if ($file->file && Storage::exists('library/' . $file->file)) {
                        Storage::delete('library/' . $file->file);
                    }
                    $file->delete();
                }
    
                $berhasilHapus=LibraryFolder::where('id', $id)->delete();

            }else{
                $file = LibraryFile::find($id);
                if ($file) {
                    if ($file->file && Storage::exists('library/' . $file->file)) {
                        Storage::delete('library/' . $file->file);
                    }
                    $berhasilHapus = $file->delete();
                }
            }
    
            if ($berhasilHapus) {
                DB::commit();
                return response()->json([
                    "code" => 200,
                    "status" => "true",
                    "message" => "Data berhasil dihapus.",
                    "type"=>$type,
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    "code" => 400,
                    "status" => "false",
                    "message" => "Gagal menghapus data."
                ]);
            }
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                "code" => 500,
                "status" => "false",
                "message" => "Terjadi kesalahan: " . $th->getMessage()
            ], 500);
        }
    }

    public function editFolder(Request $r)
    {
        $id_data=$r->id;
        $data=LibraryFolder::where('id', $id_data)
        ->first();
        return response()->json($data);
    }

    public function editFile(Request $r)
    {
        $id_data=$r->id;
        $data=LibraryFile::where('id', $id_data)
        ->first();
        return response()->json($data);
    }

}
