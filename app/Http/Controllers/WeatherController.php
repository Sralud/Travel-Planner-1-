<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    // GET /weather/{city}
    public function currentByCity($city)
    {
        $data = $this->weatherService->getCurrentWeatherByCity($city);
        return response()->json($data);
    }

    // GET /weather/forecast/{city}
    public function forecastByCity($city)
    {
        $data = $this->weatherService->getForecastByCity($city);
        return response()->json($data);
    }

    // GET /weather/coordinates?lat=...&lon=...
    public function currentByCoordinates(Request $request)
    {
        $lat = $request->query('lat');
        $lon = $request->query('lon');

        $data = $this->weatherService->getCurrentWeatherByCoordinates($lat, $lon);
        return response()->json($data);
    }

    // GET /weather/forecast/coordinates?lat=...&lon=...
    public function forecastByCoordinates(Request $request)
    {
        $lat = $request->query('lat');
        $lon = $request->query('lon');

        $data = $this->weatherService->getForecastByCoordinates($lat, $lon);
        return response()->json($data);
    }
}
