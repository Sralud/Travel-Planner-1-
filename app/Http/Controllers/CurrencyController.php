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
        $rates = $this->currencyService->getRatesByBase('USD');
        return response()->json($rates);
    }

    // GET /currency/{base}
    public function getRatesByBase($base)
    {
        $rates = $this->currencyService->getRatesByBase($base);
        return response()->json($rates);
    }

    // GET /currency/convert/{from}/{to}/{amount}
    public function convert($from, $to, $amount)
    {
        $result = $this->currencyService->convert($from, $to, floatval($amount));

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error'], 'message' => $result['message']], 404);
        }

        return response()->json($result);
    }
}
