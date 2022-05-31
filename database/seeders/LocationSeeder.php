<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->insert([
            ['city' => 'Wilmington'],
            ['city' => 'Portland'],
            ['city' => 'Toronto'],
            ['city' => 'Warsaw'],
            ['city' => 'Valencia'],
            ['city' => 'Shanghai'],
        ]);
    }
}
