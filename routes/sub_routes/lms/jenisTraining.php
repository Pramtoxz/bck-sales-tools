<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lms\JenisTrainingController;

Route::get('/jenisTraining',[JenisTrainingController::class,'index'])->name('jenisTraining.index');
Route::get('/jenisTraining/get',[JenisTrainingController::class,'get'])->name('jenisTraining.get');
Route::post('/jenisTraining/save',[JenisTrainingController::class,'save'])->name('jenisTraining.save');
Route::get('/jenisTraining/all',[JenisTrainingController::class,'all'])->name('jenisTraining.all');
Route::get('/jenisTraining/show',[JenisTrainingController::class,'show'])->name('jenisTraining.show');
Route::post('/jenisTraining/delete',[JenisTrainingController::class,'delete'])->name('jenisTraining.delete');

?>