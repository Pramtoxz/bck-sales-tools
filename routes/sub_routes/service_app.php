<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceAppController;


Route::get('/service-apps',[ServiceAppController::class,'index'])->name('service.app.index');
Route::get('/service-apps/pilih/{kd_service}',[ServiceAppController::class,'pilih'])->name('service.app.pilih');

?>