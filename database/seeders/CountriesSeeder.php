<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webpatser\Countries\CountriesFacade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Empty the countries table
        DB::table('countries')->delete();
        $countries = CountriesFacade::getList();
        foreach ($countries as $countryId => $country){
            DB::table('countries')->insert(array(
                'name' => $country['name'],
                'currency' => $country['currency_name'] ?? null,
                'currency_symbol' => $country['currency_symbol'] ?? null,
                'currency_code' => $country['currency_code'] ?? null,
                'iso_3166_2' => $country['iso_3166_2'],
                'iso_3166_3' => $country['iso_3166_3'],
                'calling_code' => $country['calling_code'] ?? null,
                'flag' => $country['flag'] ?? null,
            ));
        }
    }
}
