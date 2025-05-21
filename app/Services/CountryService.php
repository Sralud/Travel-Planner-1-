<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CountryService
{
    protected $baseUrl = 'https://restcountries.com/v3.1';

    public function getAllCountries()
    {
        return Http::get("{$this->baseUrl}/all");
    }

    public function getCountryByName($name)
    {
        try {
            $response = Http::get("{$this->baseUrl}/name/" . urlencode($name));

            if (!$response->successful()) {
                Log::error('Country API error', [
                    'name' => $name,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [
                    'success' => false,
                    'status' => $response->status(),
                    'error' => $response->status() === 404 ? 'Country not found' : 'Unable to fetch country data'
                ];
            }

            $data = $response->json();

            if (!is_array($data) || empty($data[0])) {
                return [
                    'success' => false,
                    'status' => 500,
                    'error' => 'Invalid country data received'
                ];
            }

            $country = $data[0];

            return [
                'success' => true,
                'country' => [
                    'name' => $country['name']['common'] ?? 'Unknown',
                    'flag' => $country['flag'] ?? '🏳️',
                    'details' => [
                        'capital' => $country['capital'][0] ?? 'Unknown',
                        'population' => number_format($country['population'] ?? 0),
                        'region' => $country['region'] ?? 'Unknown',
                    ]
                ]
            ];
        } catch (\Exception $e) {
            Log::error('CountryService exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'status' => 500,
                'error' => 'An unexpected error occurred'
            ];
        }
    }
}
