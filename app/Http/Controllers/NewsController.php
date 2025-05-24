<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NewsService;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function topHeadlines($country)
    {
        // Convert country name to ISO code if needed
        $countryCode = $this->newsService->getCountryCodeFromName($country) ?? $country;

        $headlines = $this->newsService->getTopHeadlinesByCountry($countryCode);

        if ($headlines) {
            return response()->json($headlines);
        }

        return response()->json(['error' => 'Unable to fetch news'], 500);
    }

    public function search(Request $request)
    {
        $query = $request->query('q', '');
        $country = $request->query('country', null);

        $countryCode = $country ? ($this->newsService->getCountryCodeFromName($country) ?? $country) : null;

        $results = $this->newsService->searchNews($query, $countryCode);

        if ($results) {
            return response()->json($results);
        }

        return response()->json(['error' => 'Unable to fetch news'], 500);
    }
}
