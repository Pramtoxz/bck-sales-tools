<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\DepartementController;

Route::get('/departement',[DepartementController::class,'index'])->name('departement.index');
Route::get('/departement/get',[DepartementController::class,'get'])->name('departement.get');
Route::get('/departement/filter/{id}',[DepartementController::class,'filterr'])->name('departement.filter');
Route::get('/departement/all',[DepartementController::class,'all'])->name('departement.all');
Route::post('/departement/save',[DepartementController::class,'save'])->name('departement.save');
Route::get('/departement/show',[DepartementController::class,'show'])->name('departement.show');
Route::post('/departement/delete',[DepartementController::class,'delete'])->name('departement.delete');

?>