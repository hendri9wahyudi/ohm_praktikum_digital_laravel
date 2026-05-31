<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController;

Route::get('/sensors/latest', [SensorController::class, 'latest']);
Route::post('/sensors/ingest', [SensorController::class, 'ingest']);
