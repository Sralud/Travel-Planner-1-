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

    public function getTopHeadlinesByCountry($countryCode)
    {
        $response = Http::get("{$this->baseUrl}/top-headlines", [
            'country' => $countryCode,
            'token' => $this->apiKey,
            'lang' => 'en',
            'max' => 10,
        ]);

        return $response->successful() ? $response->json() : null;
    }

    public function searchNews($query, $countryCode = null)
    {
        $params = [
            'q' => $query,
            'token' => $this->apiKey,
            'lang' => 'en',
        ];

        if ($countryCode) {
            $params['country'] = $countryCode;
        }

        $response = Http::get("{$this->baseUrl}/search", $params);

        return $response->successful() ? $response->json() : null;
    }

    public function getCountryCodeFromName($countryName)
    {
        $countries = [
            'afghanistan' => 'af',
            'argentina' => 'ar',
            'australia' => 'au',
            'austria' => 'at',
            'bangladesh' => 'bd',
            'belgium' => 'be',
            'brazil' => 'br',
            'bulgaria' => 'bg',
            'canada' => 'ca',
            'china' => 'cn',
            'colombia' => 'co',
            'croatia' => 'hr',
            'czech republic' => 'cz',
            'denmark' => 'dk',
            'egypt' => 'eg',
            'estonia' => 'ee',
            'finland' => 'fi',
            'france' => 'fr',
            'germany' => 'de',
            'greece' => 'gr',
            'hong kong' => 'hk',
            'hungary' => 'hu',
            'iceland' => 'is',
            'india' => 'in',
            'indonesia' => 'id',
            'ireland' => 'ie',
            'israel' => 'il',
            'italy' => 'it',
            'japan' => 'jp',
            'kazakhstan' => 'kz',
            'kenya' => 'ke',
            'latvia' => 'lv',
            'lithuania' => 'lt',
            'malaysia' => 'my',
            'mexico' => 'mx',
            'netherlands' => 'nl',
            'new zealand' => 'nz',
            'nigeria' => 'ng',
            'norway' => 'no',
            'pakistan' => 'pk',
            'philippines' => 'ph',
            'poland' => 'pl',
            'portugal' => 'pt',
            'romania' => 'ro',
            'russia' => 'ru',
            'saudi arabia' => 'sa',
            'serbia' => 'rs',
            'singapore' => 'sg',
            'slovakia' => 'sk',
            'slovenia' => 'si',
            'south africa' => 'za',
            'south korea' => 'kr',
            'spain' => 'es',
            'sweden' => 'se',
            'switzerland' => 'ch',
            'taiwan' => 'tw',
            'thailand' => 'th',
            'turkey' => 'tr',
            'ukraine' => 'ua',
            'united arab emirates' => 'ae',
            'united kingdom' => 'gb',
            'united states' => 'us',
            'usa' => 'us',
            'vietnam' => 'vn',
        ];

        return $countries[strtolower(trim($countryName))] ?? null;
    }
}
