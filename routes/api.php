<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController;

Route::get('/flights/search', [FlightController::class, 'searchFlights']);
Route::get('/flights/search', [FlightController::class, 'searchByLocation']);
Route::get('/flights/search', [FlightController::class, 'searchByLocation'])->name('flights.search');
