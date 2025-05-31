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

        if (isset($data['error']) && $data['error']) {
            return response()->json($data, 500);
        }

        return response()->json([
            'city' => $data['name'],
            'temperature' => round($data['main']['temp']) . '°C',
            'condition' => $this->formatCondition($data['weather'][0]['main']),
        ]);
    }

    // GET /weather/forecast/{city}
    public function forecastByCity($city)
    {
        $data = $this->weatherService->getForecastByCity($city);

        if (isset($data['error']) && $data['error']) {
            return response()->json($data, 500);
        }

        $dailyForecasts = [];
        $groupedByDay = [];

        foreach ($data['list'] as $forecast) {
            $date = substr($forecast['dt_txt'], 0, 10);
            $groupedByDay[$date][] = $forecast;
        }

        foreach ($groupedByDay as $date => $forecasts) {
            $temps = [];
            $conditions = [];
            $popValues = [];

            foreach ($forecasts as $f) {
                $temps[] = $f['main']['temp'];
                $conditions[] = $f['weather'][0]['main'];
                $popValues[] = $f['pop'] ?? 0;
            }

            $avgTemp = round(array_sum($temps) / count($temps));
            $maxTemp = round(max($temps));
            $minTemp = round(min($temps));
            $conditionCounts = array_count_values($conditions);
            arsort($conditionCounts);
            $mainCondition = key($conditionCounts);
            $avgPop = round((array_sum($popValues) / count($popValues)) * 100);
            $dayName = date('l', strtotime($date));

            $dailyForecasts[] = [
                'day' => $dayName,
                'date' => date('n/j', strtotime($date)),
                'temperature' => [
                    'high' => $maxTemp . '°C',
                    'low' => $minTemp . '°C'
                ],
                'condition' => $this->formatCondition($mainCondition),
                'description' => $this->generateDescription($mainCondition, $avgTemp),
                'rain_chance' => $avgPop . '%',
            ];
        }

        return response()->json(array_slice($dailyForecasts, 0, 5));
    }

    /**
     * Map OpenWeatherMap conditions to friendlier terms.
     */
    private function formatCondition(string $condition): string
    {
        return match (strtolower($condition)) {
            'clear' => 'Sunny',
            'clouds' => 'Clouds and sunshine',
            'rain' => 'Rainy',
            'drizzle' => 'Drizzle',
            'thunderstorm' => 'Stormy',
            'snow' => 'Snowy',
            'mist', 'fog', 'haze', 'smoke' => 'Foggy',
            default => ucfirst($condition),
        };
    }

    /**
     * Generate a short weather description based on condition and temperature.
     */
    private function generateDescription(string $condition, int $temp): string
    {
        $condition = strtolower($condition);

        if ($condition === 'clear' && $temp >= 30) {
            return 'Mainly clear and warm';
        }

        return match ($condition) {
            'clouds' => 'Mainly clear and warm',
            'rain' => 'Intermittent rain showers',
            'drizzle' => 'Light rain possible',
            'thunderstorm' => 'Stormy with rain',
            'snow' => 'Snowfall likely',
            'fog', 'haze', 'mist', 'smoke' => 'Foggy conditions',
            default => 'Weather conditions vary',
        };
    }
}
