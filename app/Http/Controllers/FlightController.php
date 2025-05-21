<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FlightService;

class FlightController extends Controller
{
    protected $flightService;

    public function __construct(FlightService $flightService)
    {
        $this->flightService = $flightService;
    }

    // JSON response
    public function searchFlightsJson(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
            'date' => 'required|date_format:Y-m-d',
        ]);

        try {
            $flights = $this->flightService->searchFlights(
                $request->input('origin'),
                $request->input('destination'),
                $request->input('date')
            );

            // Format output to desired JSON structure
            $formatted = collect($flights)->map(function ($flight, $index) {
                $itinerary = $flight['itineraries'][0] ?? [];
                $segments = $itinerary['segments'][0] ?? [];

                return [
                    'flightId' => (string) ($index + 1),
                    'price' => [
                        'total' => $flight['price']['total'],
                        'currency' => $flight['price']['currency'],
                    ],
                    'departure' => [
                        'iataCode' => $segments['departure']['iataCode'] ?? 'N/A',
                        'dateTime' => $segments['departure']['at'] ?? 'N/A',
                    ],
                    'arrival' => [
                        'iataCode' => $segments['arrival']['iataCode'] ?? 'N/A',
                        'dateTime' => $segments['arrival']['at'] ?? 'N/A',
                    ],
                ];
            });

            return response()->json($formatted);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch flights data.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    // Blade view response
    public function searchFlightsView(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
            'date' => 'required|date_format:Y-m-d',
        ]);

        try {
            $flights = $this->flightService->searchFlights(
                $request->input('origin'),
                $request->input('destination'),
                $request->input('date')
            );
            return view('flights.search', ['flights' => $flights]);
        } catch (\Exception $e) {
            return view('flights.search')->with('error', 'Failed to fetch flights data: ' . $e->getMessage());
        }
    }
}