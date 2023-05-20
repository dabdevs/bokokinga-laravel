<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $argentina = Country::whereCode('AR')->first();

        if (!$argentina) $argentina = new Country;
        $argentina->name = 'Argentina';
        $argentina->code = 'AR';
        $argentina->save();
    }
}
