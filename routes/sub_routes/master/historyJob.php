<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\HistoryJobController;

Route::get('/historyJob',[HistoryJobController::class,'index'])->name('historyJob.index');
Route::get('/historyJob/get',[HistoryJobController::class,'get'])->name('historyJob.get');
Route::post('/historyJob/save',[HistoryJobController::class,'save'])->name('historyJob.save');
Route::get('/historyJob/show',[HistoryJobController::class,'show'])->name('historyJob.show');
Route::post('/historyJob/delete',[HistoryJobController::class,'delete'])->name('historyJob.delete');

?>