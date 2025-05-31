<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlightService
{
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl;

    public function __construct()
    {
        $this->clientId = config('services.amadeus.client_id');
        $this->clientSecret = config('services.amadeus.client_secret');
        $this->baseUrl = rtrim(config('services.amadeus.base_uri'), '/');

        Log::info('FlightService initialized', [
            'clientIdSet' => !empty($this->clientId),
            'clientSecretSet' => !empty($this->clientSecret),
            'baseUrl' => $this->baseUrl,
        ]);
    }

    public function searchFlights(string $origin, string $destination, string $date)
    {
        if (!$this->clientId || !$this->clientSecret) {
            throw new \Exception('AMADEUS_CLIENT_ID or AMADEUS_CLIENT_SECRET is not set.');
        }

        // Step 1: Get access token
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post($this->baseUrl . '/v1/security/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if (!$response->successful()) {
            Log::error('Amadeus token request failed:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Failed to get access token: ' . $response->body());
        }

        $accessToken = $response->json()['access_token'] ?? null;

        if (!$accessToken) {
            throw new \Exception('Access token not found in response.');
        }

        // Step 2: Search for flights
        $response = Http::withToken($accessToken)
            ->post($this->baseUrl . '/v2/shopping/flight-offers', [
                'currencyCode' => 'PHP',
                'originDestinations' => [
                    [
                        'id' => '1',
                        'originLocationCode' => strtoupper($origin),
                        'destinationLocationCode' => strtoupper($destination),
                        'departureDateTimeRange' => [
                            'date' => $date,
                        ],
                    ]
                ],
                'travelers' => [
                    [
                        'id' => '1',
                        'travelerType' => 'ADULT',
                    ]
                ],
                'sources' => ['GDS'],
            ]);

        if (!$response->successful()) {
            Log::error('Amadeus flight offers search failed:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Flight offers search failed: ' . $response->body());
        }

        return $response->json()['data'] ?? [];
    }

    public function formatFlightsForJson(array $flights)
    {
        return collect($flights)->map(function ($flight) {
            return [
                'flightId' => $flight['id'] ?? null,
                'price' => [
                    'total' => $flight['price']['total'] ?? null,
                    'currency' => $flight['price']['currency'] ?? null,
                ],
                'departure' => [
                    'iataCode' => $flight['itineraries'][0]['segments'][0]['departure']['iataCode'] ?? null,
                    'dateTime' => $flight['itineraries'][0]['segments'][0]['departure']['at'] ?? null,
                ],
                'arrival' => [
                    'iataCode' => $flight['itineraries'][0]['segments'][0]['arrival']['iataCode'] ?? null,
                    'dateTime' => $flight['itineraries'][0]['segments'][0]['arrival']['at'] ?? null,
                ],
            ];
        })->toArray();
    }
}
