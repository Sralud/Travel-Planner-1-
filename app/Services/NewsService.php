<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NewsService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gnews.key');
        $this->baseUrl = 'https://gnews.io/api/v4';
    }

    public function getTopHeadlinesByCountry($country)
    {
        $response = Http::get("{$this->baseUrl}/top-headlines", [
            'country' => $country,
            'token' => $this->apiKey,
            'lang' => 'en',
            'max' => 10, // Optional: max articles
        ]);

        return $response->successful() ? $response->json() : null;
    }

    public function searchNews($query, $country = null)
    {
        $params = [
            'q' => $query,
            'token' => $this->apiKey,
            'lang' => 'en',
        ];

        if ($country) {
            $params['country'] = $country;
        }

        $response = Http::get("{$this->baseUrl}/search", $params);

        return $response->successful() ? $response->json() : null;
    }
}
