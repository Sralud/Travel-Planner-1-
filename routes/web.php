<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\FlightController;

// Currency API Routes
Route::get('/currency', [CurrencyController::class, 'index']);
Route::get('/currency/convert/{from}/{to}/{amount}', [CurrencyController::class, 'convert']);

// Weather API Routes
Route::get('/weather/forecast/{city}', [WeatherController::class, 'forecastByCity']);
Route::get('/weather/{city}', [WeatherController::class, 'currentByCity']);

// Country API Routes
Route::get('/countries', [CountryController::class, 'index']);
Route::get('/countries/view', [CountryController::class, 'showCountries']);
Route::get('/countries/{name}', [CountryController::class, 'getCountry']);

// News API Routes
Route::get('/news/top/{country}', [NewsController::class, 'topHeadlines']);
// ex: http://127.0.0.1:8000/news/top/france
Route::get('/news/search', [NewsController::class, 'search']);
// ex: http://127.0.0.1:8000/news/search?q=tech&country=japan


// Flights API Routes

// API route (for JSON responses)
Route::get('/api/flights/search', [FlightController::class, 'searchFlightsJson'])->name('flights.search.json');
//http://127.0.0.1:8000/api/flights/search?origin=MNL&destination=CEB&date=2025-07-02

// Web route (Blade View)
Route::get('/flights/search', [FlightController::class, 'searchFlightsView'])->name('flights.search.view');
//http://127.0.0.1:8000/flights/search?origin=MNL&destination=JFK&date=2025-05-25



