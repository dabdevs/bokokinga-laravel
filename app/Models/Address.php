<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    public $fillable = [
        'province',
        'city',
        'street',
        'number',
        'apt',
        'postal_code',
        'customer_id',
        'cellphone'
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
