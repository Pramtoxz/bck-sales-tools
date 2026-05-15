<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lms\User\LibraryController;

Route::get('/filelibrary',[LibraryController::class,'index']);
Route::get('/filelibrary/getData',[LibraryController::class,'getData'])->name('library.get');

Route::get('/filelibrary/viewFolder',[LibraryController::class,'viewFolder'])->name('library.viewFolder');
Route::get('/filelibrary/viewFile',[LibraryController::class,'viewFile'])->name('libraryFile.show');



Route::post('/simpanfolder',[LibraryController::class,'saveFolder'])->name('simpanfolder.get');

Route::post('/simpanfile',[LibraryController::class,'saveFile'])->name('simpanfile.get');

Route::post('/hapusdata',[LibraryController::class,'hapusData'])->name('hapusData.delete');


Route::get('/filelibrary/editFolder',[LibraryController::class,'editFolder'])->name('editfolder.show');

Route::get('/filelibrary/editFile',[LibraryController::class,'editFile'])->name('editfile.show');
// Route::get('/selectmateriNext',[MateriController::class,'allNext'])->name('materi.allNext');



?>