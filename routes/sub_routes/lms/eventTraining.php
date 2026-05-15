<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lms\EventTrainingController;

Route::get('/eventTraining',[EventTrainingController::class,'index'])->name('eventTraining.index');
Route::get('/eventTraining/get',[EventTrainingController::class,'get'])->name('eventTraining.get');
Route::post('/eventTraining/save',[eventTrainingController::class,'save'])->name('eventTraining.save');
Route::get('/eventTraining/all',[eventTrainingController::class,'all'])->name('eventTraining.all');
Route::get('/eventTraining/show',[eventTrainingController::class,'show'])->name('eventTraining.show');
Route::post('/eventTraining/delete',[eventTrainingController::class,'delete'])->name('eventTraining.delete');
Route::get('/eventTraining/peserta/get',[EventTrainingController::class,'peserta'])->name('eventTraining.peserta');
Route::post('/eventTraining/peserta/save',[EventTrainingController::class,'savePeserta'])->name('eventTraining.peserta.save');
Route::post('/eventTraining/peserta/delete',[EventTrainingController::class,'deletePeserta'])->name('eventTraining.peserta.delete');
Route::get('/eventTraining/get/kode-soal',[EventTrainingController::class,'getKodeSoal'])->name('eventTraining.getKodeSoal');


?>