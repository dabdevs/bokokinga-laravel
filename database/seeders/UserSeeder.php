<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Inserting data to users table
        DB::table('users')->insert([
            'firstname' => 'Alain',
            'lastname'  => 'Jean',
            'email'     => 'dabdevs@bokokinga.com',
            'password'  => Hash::make('1234')
        ]);
    }
}
