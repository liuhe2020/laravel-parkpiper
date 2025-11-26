<?php

use App\Http\Controllers\Web\PermitController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PermitController::class, 'home'])->name('home');
Route::get('/issue', [PermitController::class, 'issuePermit'])->name('permits.issue');
Route::get('/permits', [PermitController::class, 'index'])->name('permits.index');
Route::post('/permits', [PermitController::class, 'store'])->name('permits.store');
Route::get('/permits/check', [PermitController::class, 'check'])->name('permits.check');
Route::get('/documentation', [PermitController::class, 'documentation'])->name('documentation');
