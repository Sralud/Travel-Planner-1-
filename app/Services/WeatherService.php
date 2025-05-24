<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
        $this->client = new Client([
            'base_uri' => 'https://api.openweathermap.org/data/2.5/',
            'timeout'  => 10.0,
        ]);
    }

    /**
     * Get current weather by city name.
     */
    public function getCurrentWeatherByCity(string $city)
    {
        try {
            $response = $this->client->get('weather', [
                'query' => [
                    'q' => $city,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('WeatherService error (city): ' . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Failed to fetch current weather by city.',
            ];
        }
    }

    /**
     * Get 5-day forecast by city name.
     */
    public function getForecastByCity(string $city)
    {
        try {
            $response = $this->client->get('forecast', [
                'query' => [
                    'q' => $city,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('WeatherService error (forecast city): ' . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Failed to fetch forecast by city.',
            ];
        }
    }

}
