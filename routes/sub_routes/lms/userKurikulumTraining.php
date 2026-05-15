<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lms\User\UserKurikulumController;

Route::get('/kurikulum',[UserKurikulumController::class,'index'])->name('kurikulum.index');
Route::get('/moreNotifications',[UserKurikulumController::class,'show'])->name('moreNotifications');
// Route::get('/kurikulum/filter/{id}',[UserKurikulumController::class,'filterr'])->name('kurikulum.filter');
// Route::get('/kurikulum/all',[UserKurikulumController::class,'all'])->name('kurikulum.all');
// Route::post('/kurikulum/save',[UserKurikulumController::class,'save'])->name('kurikulum.save');
// Route::get('/kurikulum/show',[UserKurikulumController::class,'show'])->name('kurikulum.show');
// Route::post('/kurikulum/delete',[UserKurikulumController::class,'delete'])->name('kurikulum.delete');

?>