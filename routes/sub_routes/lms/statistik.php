<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lms\User\StatistikController;

Route::get('/statistik',[StatistikController::class,'index'])->name('statistik.index');
Route::get('/tahun/get',[StatistikController::class,'get'])->name('getYear.all');
Route::get('/bulan/get',[StatistikController::class,'getMonth'])->name('getMonth.all');
Route::get('/statistik/karyawan/get',[StatistikController::class,'karyawanget'])->name('getKaryawan.all');
// Route::get('/statistik/filter/{id}',[StatistikController::class,'filterr'])->name('statistik.filter');
Route::get('/statistik/all',[StatistikController::class,'all'])->name('statistik.all');
// Route::post('/statistik/save',[StatistikController::class,'save'])->name('statistik.save');
// Route::get('/statistik/show',[StatistikController::class,'show'])->name('statistik.show');
// Route::post('/statistik/delete',[StatistikController::class,'delete'])->name('statistik.delete');
Route::get('/adminStatistik',[StatistikController::class,'index'])->name('userstatistik.all');
?>