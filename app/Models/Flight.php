<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = [
        'origin_city',
        'origin_country',
        'destination_city',
        'destination_country',
        'departure_date',
        'return_date',
        'passengers',
        'price'
    ];
}
