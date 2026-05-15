<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\MenuController;

Route::get('/menu',[MenuController::class,'index'])->name('menu.index');
Route::post('/menu-user',[MenuController::class,'menuUser'])->name('menu.user.view');
Route::post('/menu-user-simpan',[MenuController::class,'simpanMenuUser'])->name('menu.user.simpan');

?>