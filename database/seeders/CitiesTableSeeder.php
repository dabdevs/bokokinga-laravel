<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $capital_federal = City::whereName('Capital Federal')->first();

        if (!$capital_federal) $capital_federal = new City;
        $capital_federal->name = 'Capital Federal';
        $capital_federal->country_id = 1;
        $capital_federal->save();


        $buenos_aires = City::whereName('Buenos Aires')->first();

        if (!$buenos_aires) $buenos_aires = new City;
        $buenos_aires->name = 'Buenos Aires';
        $buenos_aires->country_id = 1;
        $buenos_aires->save();

        $cordoba = City::whereName('Córdoba')->first();

        if (!$cordoba) $cordoba = new City;
        $cordoba->name = 'Córdoba';
        $cordoba->country_id = 1;
        $cordoba->save();
    }
}
