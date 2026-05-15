<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Absensi\CutiController;
use App\Http\Controllers\Absensi\AbsensiController;

Route::get('/cuti',[CutiController::class,'index'])->name('cuti.all');
Route::get('/cuti/get',[CutiController::class,'get'])->name('cuti.get');
Route::get('/cuti/getjeniscuti',[CutiController::class,'getJenisCuti'])->name('getjeniscuti.get');
Route::get('/cuti/getcutisaldo',[CutiController::class,'getCutiSaldo'])->name('getcutisaldo.all');

Route::get('/cuti/getdetailcuti',[CutiController::class,'getCutiDetail'])->name('getcutidetail.get');

Route::get('/cuti/editcuti',[CutiController::class,'editCuti'])->name('editCuti.show');

Route::get('/cuti/getsisacuti',[CutiController::class,'getsisacuti'])->name('getsisacuti.get');


// Route::post('/cuti/cekjumlahcuti',[CutiController::class,'CekCuti'])->name('hitungsisacuti.get');

Route::post('/cuti/simpanCuti',[CutiController::class,'SimpanCuti'])->name('simpandatacuti.post');

Route::post('/cuti/tanggalLibur',[CutiController::class,'SimpanLibur'])->name('simpanlibur.post');

Route::post('/cuti/approveCuti',[CutiController::class,'ApproveCuti'])->name('approveCuti.save');

Route::post('/cuti/rejectCuti',[CutiController::class,'RejectCuti'])->name('rejectCuti.save');

Route::post('/cuti/DeleteCuti',[CutiController::class,'DeleteCuti'])->name('hapuscuti.delete');

Route::get('/ambildepartement/all',[CutiController::class,'AmbilDepartement'])->name('ambilDepartement.all');

Route::get('/cuti/ambilkaryawan',[CutiController::class,'AmbilKaryawan'])->name('ambilkaryawan.get');

Route::get('/kalendercuti',[CutiController::class,'kalendercuti'])->name('kalendercuti.get');

Route::get('/jadwalcuti',[CutiController::class,'jadwalcuti'])->name('jadwal_cuti.show');

Route::get('/potongcuti',[CutiController::class,'potongcuti'])->name('potongcuti.get');

Route::post('/cuti/simpanpotongcuti',[CutiController::class,'simpanpotongcuti'])->name('simpanpotongcuti.get');

Route::get('/cuti/report/view',[CutiController::class,'reportCuti'])->name('cuti.reportView');

Route::get('/cuti/report/get',[CutiController::class,'reportCutiGet'])->name('cuti.reportGet');

Route::get('/cuti/export/get',[CutiController::class,'exportCutiGet'])->name('cuti.exportGet');

Route::get('/cuti/exportraw/get',[CutiController::class,'exportRawCutiGet'])->name('cuti.exportRawGet');

Route::get('/absensi',[AbsensiController::class,'index'])->name('absensi.index');

Route::get('/absensi/data',[AbsensiController::class,'show'])->name('absensi.show');
Route::post('/absensi/import',[AbsensiController::class,'import'])->name('absensi.import');
Route::post('/absensi/delete',[AbsensiController::class,'delete'])->name('absensi.delete');


Route::get('/absensi/report',[AbsensiController::class,'report'])->name('absensi.report');
Route::get('/absensi/report/view',[AbsensiController::class,'reportView'])->name('absensi.reportView');



Route::get('/testing/scheduler',[CutiController::class,'handle'])->name('testing.handle');

Route::get('/data_libur/data',[CutiController::class,'getDataLibur'])->name('getDataLibur.get');
Route::post('/tanggallibur/delete',[CutiController::class,'hapusLibur'])->name('tanggallibur.delete');


Route::get('/coe',[AbsensiController::class,'indexcoe'])->name('coemanager.index');

Route::get('/coemanager/getdata',[AbsensiController::class,'getdatacoe'])->name('coemanager.getdatacoe');
?>