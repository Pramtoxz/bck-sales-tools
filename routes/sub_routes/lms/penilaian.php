<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lms\PenilaianController;

Route::get('/penilaian',[PenilaianController::class,'index'])->name('penilaian.index');
Route::get('/penilaian/get',[PenilaianController::class,'get'])->name('penilaian.get');
Route::post('/penilaian/save',[PenilaianController::class,'save'])->name('penilaian.save');
Route::get('/penilaian/getUlasan',[PenilaianController::class,'getUlasan'])->name('ulasan.get');
// Route::post('/penilaianMateri/save',[PenilaianController::class,'saveMateri'])->name('penilaianMateri.save');
Route::get('/penilaian/{kd_training}/{batch}',[PenilaianController::class,'batchGet']);
Route::get('/batch/show',[PenilaianController::class,'dataBatch'])->name('batch');
Route::get('/batch/showAll',[PenilaianController::class,'dataBatchAll'])->name('batch.all');
Route::get('/penilaian/show',[PenilaianController::class,'show'])->name('penilaian.show');
Route::post('/penilaian/delete',[PenilaianController::class,'delete'])->name('penilaian.delete');
Route::get('/penilaian/batch-akhir',[PenilaianController::class,'eventAkhir'])->name('batch.akhir');

// Route::post('/penilaianMateri/delete',[PenilaianController::class,'deleteMateri'])->name('penilaianMateri.delete');

// Route::get('/materipenilaian/show',[PenilaianController::class,'matshow'])->name('materipenilaian.show');

?>