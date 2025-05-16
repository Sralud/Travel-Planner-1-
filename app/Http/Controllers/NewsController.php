<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    public function topHeadlines($country)
    {
        // Convert country name to code if needed or just use $country as is
        $countryCode = $this->getCountryCodeFromName($country) ?? $country;

        $apiKey = env('GNEWS_API_KEY');
        $url = "https://gnews.io/api/v4/top-headlines?country={$countryCode}&token={$apiKey}";

        $response = Http::get($url);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Unable to fetch news'], 500);
    }

    public function search(Request $request)
    {
        $query = $request->query('q', '');
        $country = $request->query('country', null);

        $apiKey = env('GNEWS_API_KEY');
        $url = "https://gnews.io/api/v4/search?q={$query}&token={$apiKey}";

        if ($country) {
            // Convert country name to code or append directly if it's already a code
            $countryCode = $this->getCountryCodeFromName($country) ?? $country;
            $url .= "&country={$countryCode}";
        }

        $response = Http::get($url);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Unable to fetch news'], 500);
    }

    // Helper to convert country names to ISO country codes
    protected function getCountryCodeFromName($countryName)
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
            'vietnam' => 'vn',
        ];
    
        return $countries[strtolower(trim($countryName))] ?? null;
    }
    
}
