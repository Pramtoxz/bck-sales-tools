<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Proposal\ProposalController;

Route::get('/proposal',[ProposalController::class,'index'])->name('proposal.all');
Route::get('/proposal/get',[ProposalController::class,'get'])->name('proposal.get');
Route::get('/proposal/kabag',[ProposalController::class,'getapproval'])->name('proposal.kabag');
Route::post('/proposal/save',[ProposalController::class,'save'])->name('proposal.save');
?>