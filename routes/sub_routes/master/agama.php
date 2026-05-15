<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\AgamaController;

Route::get('/agama',[AgamaController::class,'index'])->name('agama.all');

?>