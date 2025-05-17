<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightSearch extends Model
{
    protected $table = 'flight_searches';

    // Fillable fields for mass assignment (like create(), update())
    protected $fillable = [
        'origin',
        'destination',
        'departure_date',
    ];
}