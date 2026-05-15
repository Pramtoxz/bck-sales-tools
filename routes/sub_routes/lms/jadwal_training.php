<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lms\User\JadwalTrainingController;

Route::get('/jadwal',[JadwalTrainingController::class,'index'])->name('jadwal_training.view');
Route::get('/jadwal/show',[JadwalTrainingController::class,'show'])->name('jadwal_training.show');

?>