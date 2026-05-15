<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\KaryawanController;
Route::get('/karyawan',[KaryawanController::class,'index'])->name('karyawan.index');
Route::get('/karyawan/get',[KaryawanController::class,'get'])->name('karyawan.get');
Route::get('/karyawan/all',[KaryawanController::class,'all'])->name('karyawan.all');
Route::post('/karyawan/save',[KaryawanController::class,'save'])->name('karyawan.save');
Route::get('/karyawan/show',[KaryawanController::class,'show'])->name('karyawan.show');
Route::post('/karyawan/delete',[KaryawanController::class,'delete'])->name('karyawan.delete');
Route::post('/karyawan/import',[KaryawanController::class,'import'])->name('karyawan.import');
Route::get('/karyawan/download-template',[KaryawanController::class,'downloadTemplate'])->name('karyawan.downloadTemplate');
Route::get('/karyawan/get-list',[KaryawanController::class,'getListKaryawan'])->name('karyawan.getListKaryawan');
Route::get('/karyawan/get-status',[KaryawanController::class,'getStatus'])->name('karyawan.getStatus');
Route::get('/getFoto',[KaryawanController::class,'getFoto'])->name('getFoto.get');
Route::get('/karyawan/lihat/{id?}',[KaryawanController::class,'lihat'])->name('karyawan.lihat');
Route::get('/karyawan/lihatdetail',[KaryawanController::class,'lihatdetail'])->name('karyawan.lihatdetail');
Route::get('/karyawan/lihatdetailtraining',[KaryawanController::class,'lihatdetailtraining'])->name('karyawan.lihatdetailtraining');

?>