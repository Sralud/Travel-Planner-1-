<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CountryService;
use App\Models\Country;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CountryController extends Controller
{
    protected $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    /**
     * Store a new country in the local database
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:countries,code',
            'region' => 'nullable|string',
            'capital' => 'nullable|string',
        ]);

        $country = Country::create($data);
        return response()->json($country, 201);
    }

    /**
     * Update an existing country
     */
    public function update(Request $request, $code)
    {
        $country = Country::where('code', $code)->firstOrFail();
        $country->update($request->only('name', 'region', 'capital'));

        return response()->json($country);
    }

    /**
     * Delete a country by code
     */
    public function destroy($code)
    {
        $country = Country::where('code', $code)->firstOrFail();
        $country->delete();

        return response()->json(['message' => 'Country deleted successfully']);
    }

    /**
     * Get all countries from external API
     */
    public function index()
    {
        $response = $this->countryService->getAllCountries();

        if ($response->successful()) {
            return response()->json($response->json());
        }

        Log::error('Failed to fetch all countries', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        return response()->json(['error' => 'Unable to fetch countries'], 500);
    }

    /**
     * Show countries in a Blade view (optional)
     */
    public function showCountries()
    {
        $response = Http::get('https://restcountries.com/v3.1/all');

        if ($response->successful()) {
            $countries = $response->json();
            return view('countries', ['countries' => $countries]);
        }

        Log::error('Failed to fetch countries for view', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        return view('countries', ['countries' => []]);
    }

    /**
     * Get country details using CountryService
     */
    public function getCountry($name)
    {
        $result = $this->countryService->getCountryByName($name);

        if ($result['success']) {
            $data = $result['country'];

            // Optional: you can add custom or dynamic tags here
            $tags = match (strtolower($data['name'])) {
                'japan' => ['island', 'tech', 'sushi'],
                'philippines' => ['beaches', 'islands', 'culture'],
                'italy' => ['pizza', 'fashion', 'history'],
                'new zealand' => ['kiwi', 'nature', 'rugby'],
                default => ['travel', 'explore']
            };

            return response()->json([
                'name' => $data['name'],
                'capital' => $data['details']['capital'],
                'population' => $data['details']['population'],
                'region' => $data['details']['region'],
                'flag' => $data['flag'],
                'tags' => $tags
            ]);
        }

        return response()->json(['error' => $result['error']], $result['status']);
    }
}
