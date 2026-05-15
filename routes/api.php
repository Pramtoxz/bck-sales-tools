<?php

use App\Http\Controllers\appsheet\AppsheetController;
use App\Http\Controllers\Aptana\WhatsAppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Digital\WaController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// prefix automated api example : /api/url


Route::middleware(['wa-msg'])->group(function(){
    Route::get('/wa/get-all-data',[WaController::class,'getAllData'])->name('wa.getAllData');
    Route::post('/wa/update-data',[WaController::class,'updateData'])->name('wa.updateData');
    // Rpa2 //
    Route::get('/wa/get-data-rpa2',[WaController::class,'getDataRpa2'])->name('wa.getDataRpa2');
    Route::post('/wa/update-data-rpa2',[WaController::class,'updateDataRpa2'])->name('wa.updateDataRpa2');

    // Mandiri //
    Route::get('/wa/get-data-mandiri',[WaController::class,'getDataMandiri'])->name('wa.getDataMandiri');
    Route::post('/wa/update-data-mandiri',[WaController::class,'updateDataMandiri'])->name('wa.updateDataMandiri');
});
Route::get('/get-report-wa',[WaController::class,'getReportWa'])->name('wa.getReportWa');

// Aptana
Route::prefix('whatsapp')->group(function () {

    // *Get account
    Route::get('/account', [WhatsAppController::class, 'getAccount']);
    
    // *List of senders
    Route::get('/senders', [WhatsAppController::class, 'getSenders']);
    
    // *List of message templates
    Route::get('/templates', [WhatsAppController::class, 'getTemplates']);

    // *Send Text Message
    Route::post('/process-queue', [WhatsAppController::class, 'processQueue']);

    Route::get('/report', [WhatsAppController::class, 'saveAndSendReport']);

});

// * URL Callback Webhooks
Route::post('/whatsapp/webhook', [WhatsAppController::class, 'webhook']);
Route::post('/whatsapp/webhook/scan', [WhatsAppController::class, 'webhookScan']);
Route::post('/appsheet', [AppsheetController::class, 'store']);

Route::post('/appsheet/store-tasks', [AppsheetController::class, 'StoreTask']);