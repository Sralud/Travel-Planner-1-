<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CurrencyService;

class CurrencyController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    // GET /currency
    public function index()
    {
        $rates = $this->currencyService->getRates('USD');
        return response()->json($rates);
    }

    // GET /currency/{base}
    public function getRatesByBase($base)
    {
        $rates = $this->currencyService->getRates(strtoupper($base));
        return response()->json($rates);
    }

    // GET /currency/convert/{from}/{to}/{amount}
    public function convert($from, $to, $amount)
    {
        $rates = $this->currencyService->getRates(strtoupper($from));

        if (isset($rates['data'][$to])) {
            $rate = $rates['data'][$to]['value'];
            return response()->json([
                'from' => strtoupper($from),
                'to' => strtoupper($to),
                'amount' => floatval($amount),
                'converted' => floatval($amount) * $rate,
                'rate' => $rate,
            ]);
        }

        return response()->json(['error' => 'Currency not found'], 404);
    }
}
