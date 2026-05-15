<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\PendidikanController;
Route::get('/pendidikan',[PendidikanController::class,'index'])->name('pendidikan.all');

?>