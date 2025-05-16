<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\CountryController;

// Currency API Routes
Route::get('/currency', [CurrencyController::class, 'index']);
Route::get('/currency/{base}', [CurrencyController::class, 'getRatesByBase']);
Route::get('/currency/convert/{from}/{to}/{amount}', [CurrencyController::class, 'convert']);

// Weather API Routes
Route::get('/weather/forecast/coordinates', [WeatherController::class, 'forecastByCoordinates']);
Route::get('/weather/coordinates', [WeatherController::class, 'currentByCoordinates']);
Route::get('/weather/forecast/{city}', [WeatherController::class, 'forecastByCity']);
Route::get('/weather/{city}', [WeatherController::class, 'currentByCity']);

// Country API Routes
Route::get('/countries', [CountryController::class, 'index']);
Route::get('/countries/show', [CountryController::class, 'show']);
Route::get('/countries/view', [CountryController::class, 'showCountries']);
Route::get('/countries/{name}', [CountryController::class, 'getCountry']);

