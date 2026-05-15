<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lms\User\MateriController;

Route::get('/materi/{kd_training}/{kd_event_training}',[MateriController::class,'index']);
Route::get('/selectmateri',[MateriController::class,'all'])->name('materi.all');
Route::get('/checkUlasan',[MateriController::class,'checkUlasan'])->name('checkUlasan.all');
Route::post('/ulasan',[MateriController::class,'ulasan'])->name('ulasan.save');


// Route::get('/selectmateriNext',[MateriController::class,'allNext'])->name('materi.allNext');



?>