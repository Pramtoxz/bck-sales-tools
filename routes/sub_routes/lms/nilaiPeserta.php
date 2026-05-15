<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lms\PenilaianController;

Route::get('/nilai',[PenilaianController::class,'userGetNilai'])->name('userGetNilai.all');
Route::get('/nilai/get',[PenilaianController::class,'userGetNilaiTable'])->name('userGetNilai.get');
Route::get('/download/sertifikat/{kode_event}',[PenilaianController::class,'downloadSertifikat'])->name('downloadSertifikat');


?>