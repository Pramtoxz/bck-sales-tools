<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\checkServiceApps;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/berkas-spk/{filename}', function ($filename) {
    $path = '/var/www/html/dmsdev/storage/app/public/spk/' . $filename;

    if (!File::exists($path)) {
        return response()->json([
            'status' => 'error',
            'message' => 'File tidak ditemukan.',
            'path_dicari' => $path
        ], 404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    return Response::make($file, 200)->header("Content-Type", $type);
});
Route::get('/dokumen-sqm/{filename}', function ($filename) {
    $path = '/var/www/html/dmsdev/storage/app/public/dokumen_sqm/' . $filename;

    if (!File::exists($path)) {
        return response()->json([
            'status' => 'error',
            'message' => 'File tidak ditemukan.',
            'path_dicari' => $path
        ], 404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    return Response::make($file, 200)->header("Content-Type", $type);
});

Route::get('/terimakasih/{filename}', function ($filename) {
    $path = '/var/www/html/portalnms/storage/app/public/terimakasih/' . $filename;

    if (!File::exists($path)) {
        return response()->json([
            'status' => 'error',
            'message' => 'File tidak ditemukan.',
            'path_dicari' => $path
        ], 404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    return Response::make($file, 200)->header("Content-Type", $type);
});

Route::get('/email/verify', function () {
  return view('auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
  $request->user()->sendEmailVerificationNotification();
  
  // return back()->with('message', 'Verification link sent!');
  return response()->json([
    "code"=>200,
    "message"=>"Berhasil kirim verifikasi email, harap cek email pada inbox atau pada spam",
  ]);
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
  $request->fulfill();

  return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::middleware(['auth','verified','service-apps'])->group(function(){
  //dashboard
  Route::get('/',[HomeController::class,'index'])->name('home');
  //profil
  
  //kurikulum
  require_once 'sub_routes/lms/userKurikulumTraining.php';

  require_once 'sub_routes/lms/jadwal_training.php';
  //materi
  require_once 'sub_routes/lms/materi.php';
  
  //user Nilai
  require_once 'sub_routes/lms/nilaiPeserta.php';

  //statistik
  require_once 'sub_routes/lms/statistik.php';

  
  //karyawan
  require_once 'sub_routes/master/karyawan.php';
  
   //historyjob
  require_once 'sub_routes/master/historyJob.php';
    //jenisTraining
  require_once 'sub_routes/lms/jenisTraining.php';
    //Training
  require_once 'sub_routes/lms/training.php';
     //Event Training
  require_once 'sub_routes/lms/eventTraining.php';
     //Penilaian
  require_once 'sub_routes/lms/penilaian.php';
      //riwayat admin
  require_once 'sub_routes/lms/riwayatTraining.php';
     //riwayat user
  require_once 'sub_routes/lms/riwayatUser.php';
      //notifikasi training
  require_once 'sub_routes/lms/notifTraining.php';
      // report
  require_once 'sub_routes/lms/report.php';

  // menu
  require_once 'sub_routes/master/menu.php';

  //proposal
  require_once 'sub_routes/proposal/proposal.php';

  //cuti
  require_once 'sub_routes/cuti/cuti.php';

   //file library
   require_once 'sub_routes/lms/library.php';


});

Route::middleware(['auth','verified'])->group(function(){
  require_once 'sub_routes/service_app.php';
  require_once 'sub_routes/profile.php';
  // departement
  require_once 'sub_routes/master/departement.php';
  // jabatan
  require_once 'sub_routes/master/jabatan.php';

  //pendidikan
  require_once 'sub_routes/master/pendidikan.php';
   //agama
  require_once 'sub_routes/master/agama.php';
});

Auth::routes();