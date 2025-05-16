<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.currencyapi.com/v3/',
            'timeout' => 15.0, // Increased timeout
            'connect_timeout' => 10.0, // Extra time to establish connection
            RequestOptions::FORCE_IP_RESOLVE => 'v4', // Force IPv4
            'verify' => true, // Ensure SSL is verified
        ]);
    }

    public function getRates($base = 'USD')
    {
        try {
            $response = $this->client->request('GET', 'latest', [
                'query' => [
                    'apikey' => config('services.currency.key'),
                    'base_currency' => $base,
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('CurrencyAPI Error: ' . $e->getMessage());
            return [
                'error' => 'Failed to fetch currency rates',
                'message' => $e->getMessage(),
            ];
        }
    }
}
