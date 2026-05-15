<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lms\RiwayatController;

Route::get('/riwayatTraining',[RiwayatController::class,'index'])->name('riwayatTraining.index');
Route::get('/riwayatTraining/get',[RiwayatController::class,'get'])->name('riwayatTraining.get');
// Route::get('/riwayatTraining/filter/{id}',[RiwayatController::class,'filterr'])->name('riwayatTraining.filter');
// Route::get('/riwayatTraining/all',[RiwayatController::class,'all'])->name('riwayatTraining.all');
// Route::post('/riwayatTraining/save',[RiwayatController::class,'save'])->name('riwayatTraining.save');
// Route::get('/riwayatTraining/show',[RiwayatController::class,'show'])->name('riwayatTraining.show');
// Route::post('/riwayatTraining/delete',[RiwayatController::class,'delete'])->name('riwayatTraining.delete');

?>