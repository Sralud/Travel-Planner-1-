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
            'timeout' => 15.0,
            'connect_timeout' => 10.0,
            RequestOptions::FORCE_IP_RESOLVE => 'v4',
            'verify' => true,
        ]);
    }

    /**
     * Get exchange rates for a base currency
     */
    public function getRatesByBase(string $base = 'USD'): array
    {
        try {
            $response = $this->client->request('GET', 'latest', [
                'query' => [
                    'apikey' => config('services.currency.key'),
                    'base_currency' => strtoupper($base),
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

    /**
     * Convert an amount from one currency to another
     */
    public function convert(string $from, string $to, float $amount): array
    {
        $rates = $this->getRatesByBase($from);

        if (isset($rates['data'][$to])) {
            $rate = $rates['data'][$to]['value'];
            return [
                'from' => strtoupper($from),
                'to' => strtoupper($to),
                'amount' => $amount,
                'converted' => $amount * $rate,
                'rate' => $rate,
            ];
        }

        return [
            'error' => 'Currency not found',
            'message' => "Currency code '{$to}' not found for base '{$from}'.",
        ];
    }
}
