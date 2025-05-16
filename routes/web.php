<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\WeatherController;

// Currency API Routes
Route::get('/currency', [CurrencyController::class, 'index']);
Route::get('/currency/{base}', [CurrencyController::class, 'getRatesByBase']);
Route::get('/currency/convert/{from}/{to}/{amount}', [CurrencyController::class, 'convert']);

// Weather API Routes (Put static routes before dynamic ones!)
Route::get('/weather/forecast/coordinates', [WeatherController::class, 'forecastByCoordinates']);
Route::get('/weather/coordinates', [WeatherController::class, 'currentByCoordinates']);
Route::get('/weather/forecast/{city}', [WeatherController::class, 'forecastByCity']);
Route::get('/weather/{city}', [WeatherController::class, 'currentByCity']);
