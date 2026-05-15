<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lms\ReportController;

Route::get('/report/training',[ReportController::class,'index'])->name('report.training.index');
Route::get('/report/training/donwload',[ReportController::class,'download_history_jadwal'])->name('report.training.download');
?>