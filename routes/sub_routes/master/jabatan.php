<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\JabatanController;

Route::get('/jabatan',[JabatanController::class,'index'])->name('jabatan.index');
Route::get('/jabatan/get',[JabatanController::class,'get'])->name('jabatan.get');
Route::get('/jabatan/filter/{id}',[JabatanController::class,'filterr'])->name('jabatan.filter');
Route::get('/jabatan/filterkode/{id}',[JabatanController::class,'filter'])->name('jabatan.filterkode');
Route::get('/jabatan/all',[JabatanController::class,'all'])->name('jabatan.all');
Route::post('/jabatan/save',[JabatanController::class,'save'])->name('jabatan.save');
Route::get('/jabatan/show',[JabatanController::class,'show'])->name('jabatan.show');
Route::post('/jabatan/delete',[JabatanController::class,'delete'])->name('jabatan.delete');

?>