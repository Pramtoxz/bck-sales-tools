<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lms\RiwayatController;

Route::get('/riwayatUser',[RiwayatController::class,'indexUser'])->name('riwayatUser.index');
Route::get('/riwayatUser/get',[RiwayatController::class,'getUser'])->name('riwayatUser.get');

Route::get('/downloadSoalPreTest/{path}',[RiwayatController::class,'downloadSoalPreTest'])->name('downloadSoalPreTest.save');
Route::get('/downloadJawabanPreTest/{path}',[RiwayatController::class,'downloadJawabanPreTest'])->name('downloadJawabanPreTest.save');
Route::get('/downloadSoalPostTest/{path}',[RiwayatController::class,'downloadSoalPostTest'])->name('downloadSoalPostTest.save');
Route::get('/downloadJawabanPostTest/{path}',[RiwayatController::class,'downloadJawabanPostTest'])->name('downloadJawabanPostTest.save');


?>