<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FlightService
{
    protected $apiKey;
    protected $apiHost;

    public function __construct()
    {
        $this->apiKey = config('services.skyscanner.key');
        $this->apiHost = config('services.skyscanner.host');
    }

    public function searchFlights($origin, $destination, $date)
    {
        $response = Http::withHeaders([
            'X-RapidAPI-Key' => $this->apiKey,
            'X-RapidAPI-Host' => $this->apiHost
        ])->get('https://skyscanner89.p.rapidapi.com/search', [
            'adults' => '1',
            'origin' => $origin,
            'destination' => $destination,
            'departureDate' => $date,
            'currency' => 'USD'
        ]);

        return $response->successful() ? $response->json() : null;
    }
}
