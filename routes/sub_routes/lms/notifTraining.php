<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lms\NotifController;

Route::get('/notifTraining',[NotifController::class,'index'])->name('notifTraining.index');
Route::get('/notifTraining/get',[NotifController::class,'get'])->name('notifTraining.get');
Route::post('/notifTraining/delete',[NotifController::class,'delete'])->name('notifTraining.delete');
Route::post('/notifTraining/save',[NotifController::class,'pesertasave'])->name('notifTraining.peserta.save');
Route::get('/notifTraining/peserta',[NotifController::class,'peserta'])->name('pesertaTraining.get');
// Route::get('/jenisTraining/show',[NotifController::class,'show'])->name('jenisTraining.show');



?>