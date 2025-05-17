<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController;

Route::get('api/flights', [FlightController::class, 'index']);
Route::get('api/flights/{id}', [FlightController::class, 'show']);
Route::post('api/flights', [FlightController::class, 'store']);
Route::put('api/flights/{id}', [FlightController::class, 'update']);
Route::delete('api/flights/{id}', [FlightController::class, 'destroy']);
Route::get('api/flights/search', [FlightController::class, 'search']);