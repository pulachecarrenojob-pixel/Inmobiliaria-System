<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LeadController;

Route::post('/leads', [LeadController::class, 'store']);
Route::get('/leads', [LeadController::class, 'index']);
Route::put('/leads/{id}', [LeadController::class, 'update']);
Route::delete('/leads/{id}', [LeadController::class, 'destroy']);
Route::get('/leads/stats', [LeadController::class, 'stats']);