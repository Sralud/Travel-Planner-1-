<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\FlightController;

// Currency API Routes
Route::get('/currency', [CurrencyController::class, 'index']);
Route::get('/currency/{base}', [CurrencyController::class, 'getRatesByBase']);
Route::get('/currency/convert/{from}/{to}/{amount}', [CurrencyController::class, 'convert']);

// Weather API Routes
Route::get('/weather/forecast/{city}', [WeatherController::class, 'forecastByCity']);
Route::get('/weather/{city}', [WeatherController::class, 'currentByCity']);

// Country API Routes
Route::get('/countries', [CountryController::class, 'index']);
Route::get('/countries/show', [CountryController::class, 'show']);
Route::get('/countries/view', [CountryController::class, 'showCountries']);
Route::get('/countries/{name}', [CountryController::class, 'getCountry']);

// News API Routes
Route::get('/news/top/{country}', [NewsController::class, 'topHeadlines']);
// ex: http://127.0.0.1:8000/news/top/france
Route::get('/news/search', [NewsController::class, 'search']);
// ex: http://127.0.0.1:8000/news/search?q=tech&country=japan


// Flights API Routes
Route::get('/flights', [FlightController::class, 'index']);
// ex: http://127.0.0.1:8000/api/api/flights/
Route::get('/flights/{id}', [FlightController::class, 'show']);
Route::post('/flights', [FlightController::class, 'store']);
Route::put('/flights/{id}', [FlightController::class, 'update']);
Route::delete('/flights/{id}', [FlightController::class, 'destroy']);
