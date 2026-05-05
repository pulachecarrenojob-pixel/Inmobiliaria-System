<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LeadController;

Route::post('/leads', [LeadController::class, 'store']);