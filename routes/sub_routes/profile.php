<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;


Route::get('/profile',[ProfileController::class,'index'])->name('profile.index');
Route::get('/profile/get',[ProfileController::class,'get'])->name('profile.get');
Route::get('/profilehistory/get',[ProfileController::class,'getHistory'])->name('profilehistory.get');
Route::post('/profileChangePassword/update',[ProfileController::class,'changePassword'])->name('profileChangePassword.update');

?>