<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CountryService
{
    protected $baseUrl = 'https://restcountries.com/v3.1';

    public function getAllCountries()
    {
        return Http::get("{$this->baseUrl}/all");
    }

    public function getCountryByName($name)
    {
        return Http::get("{$this->baseUrl}/name/" . urlencode($name));
    }
}