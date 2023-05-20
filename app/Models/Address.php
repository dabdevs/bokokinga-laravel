<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    public $fillable = [
        'state',
        'street',
        'number',
        'postal_code',
        'country_id',
        'city_id',
        'customer_id',
        'telephone'
    ];

    /**
     * Get the country that owns the Address
     *
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the city that owns the Address
     *
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
