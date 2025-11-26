<?php

use App\Http\Controllers\Api\PermitController;
use Illuminate\Support\Facades\Route;

Route::get('/permits/check', [PermitController::class, 'check']);
Route::post('/permits', [PermitController::class, 'store']);
