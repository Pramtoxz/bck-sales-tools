<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lms\TrainingController;

Route::get('/training',[TrainingController::class,'index'])->name('training.index');
Route::get('/training/get',[TrainingController::class,'get'])->name('training.get');
Route::post('/training/save',[TrainingController::class,'save'])->name('training.save');
Route::post('/trainingMateri/save',[TrainingController::class,'saveMateri'])->name('trainingMateri.save');

Route::get('/training/all',[TrainingController::class,'all'])->name('training.all');
Route::get('/training/show',[TrainingController::class,'show'])->name('training.show');
Route::get('/training/getListTraining',[TrainingController::class,'getListTraining'])->name('training.getListTraining');
Route::get('/training/getRiwayatTraining',[TrainingController::class,'getRiwayatTraining'])->name('training.getRiwayatTraining');
Route::get('/training/getEventTraining',[TrainingController::class,'getEventTraining'])->name('training.getEventTraining');
Route::get('/training/getPesertaTraining',[TrainingController::class,'getPesertaTraining'])->name('training.getPesertaTraining');
Route::post('/training/delete',[TrainingController::class,'delete'])->name('training.delete');

Route::post('/trainingMateri/delete',[TrainingController::class,'deleteMateri'])->name('trainingMateri.delete');

Route::get('/materiTraining/show',[TrainingController::class,'matshow'])->name('materiTraining.show');
Route::get('/materiTrainingUser/show',[TrainingController::class,'matshowUser'])->name('materiTrainingUser.show');

//user pesertatraining
// Route::post('/peserta/update',[TrainingController::class,'updatePeserta'])->name('pesertaTraining.update');
// Route::post('/peserta/updates',[TrainingController::class,'updatePesertas'])->name('pesertaTrainingg.update');


Route::get('/downloadPreTestFile/{path}',[TrainingController::class,'downloadPreTest'])->name('downloadPreTest.save');
Route::get('/downloadPostTestFile/{path}',[TrainingController::class,'downloadPostTest'])->name('downloadPostTest.save');


Route::get('/detail/{kd_training}/{event_training}',[TrainingController::class,'detailTraining'])->name('detail.show');

Route::get('/detailTrainingUser/show',[TrainingController::class,'detailTrainingUser'])->name('detailTrainingUser.show');

Route::get('/detailTrainingUser/history-detail',[TrainingController::class,'historyDetail'])->name('detailTrainingUser.historyDetail');

Route::get('/training/HistoryPesertaTraining',[TrainingController::class,'HistoryPesertaTraining'])->name('training.HistoryPesertaTraining');

Route::get('/training/HistoryActivityTraining',[TrainingController::class,'HistoryActivityTraining'])->name('training.HistoryActivityTraining');

Route::get('/training/final_project',[TrainingController::class,'FinalProject'])->name('training.FinalProject');

Route::post('/training/upload_final_project',[TrainingController::class,'UploadFinalProject'])->name('training.uploadFinalProject');

Route::get('/training-tag/all',[TrainingController::class,'TrainingTagAll'])->name('trainigTag.all');

Route::get('/training/downloadTemplate',[TrainingController::class,'downloadTemplate'])->name('training.downloadTemplate');

// baru
Route::post('/trainingSoal/save',[TrainingController::class,'saveBankSoal'])->name('trainingSoal.save');
Route::get('/trainingSoal/show',[TrainingController::class,'showBankSoal'])->name('trainingSoal.show');
Route::get('/view/test/{kd_event_training}/{keterangan}',[TrainingController::class,'viewTest'])->name('trainingSoal.viewTest');
Route::get('/view/pilihan/soal',[TrainingController::class,'viewPilihanSoal'])->name('trainingSoal.viewPilihanSoal');
Route::get('/view/soal',[TrainingController::class,'viewSoal'])->name('trainingSoal.viewSoal');
Route::post('/start/test',[TrainingController::class,'startTest'])->name('trainingSoal.startTest');
Route::post('/selesai/test',[TrainingController::class,'selesaiTest'])->name('trainingSoal.selesaiTest');
Route::post('/save/jawaban',[TrainingController::class,'saveJawaban'])->name('trainingSoal.saveJawaban');
Route::post('/training/delete/soal',[TrainingController::class,'deleteSoal'])->name('trainingSoal.deleteSoal');
Route::get('/pre-view/soal',[TrainingController::class,'preViewSoal'])->name('trainingSoal.preViewSoal');
?>