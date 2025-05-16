<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\CountryService;
use App\Models\Country;

class CountryController extends Controller
{
    protected $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    /**
     * Create and store a new country in the local database
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
     * Update an existing country in the local database
     */
    public function update(Request $request, $code)
    {
        $country = Country::where('code', $code)->firstOrFail();

        $country->update($request->only('name', 'region', 'capital'));
        return response()->json($country);
    }

    /**
     * Delete a country from the local database
     */
    public function destroy($code)
    {
        $country = Country::where('code', $code)->firstOrFail();
        $country->delete();

        return response()->json(['message' => 'Country deleted successfully']);
    }

    /**
     * Get all countries from the external API
     */
    public function index()
    {
        $response = $this->countryService->getAllCountries();

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Unable to fetch countries'], 500);
    }

    /**
     * Return hardcoded sample country data
     */
    public function show()
    {
        $countries = [
            ['name' => 'Japan', 'code' => 'JP', 'region' => 'Asia', 'capital' => 'Tokyo'],
            ['name' => 'France', 'code' => 'FR', 'region' => 'Europe', 'capital' => 'Paris'],
            ['name' => 'Brazil', 'code' => 'BR', 'region' => 'South America', 'capital' => 'Brasília'],
        ];

        return response()->json($countries);
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

        return view('countries', ['countries' => []]);
    }

    /**
     * Get a single country's details by name
     */
    public function getCountry($name)
    {
        $response = $this->countryService->getCountryByName($name);

        if ($response->successful()) {
            $data = $response->json();

            if (!empty($data)) {
                $country = $data[0];

                return response()->json([
                    'country' => [
                        'name' => $country['name']['common'] ?? 'Unknown',
                        'flag' => $country['flag'] ?? '🏳️',
                        'details' => [
                            'capital' => $country['capital'][0] ?? 'Unknown',
                            'population' => number_format($country['population'] ?? 0),
                            'region' => $country['region'] ?? 'Unknown',
                        ]
                    ]
                ]);
            }

            return response()->json(['error' => 'Country not found'], 404);
        }

        return response()->json(['error' => 'Unable to fetch country data'], 500);
    }
}
 