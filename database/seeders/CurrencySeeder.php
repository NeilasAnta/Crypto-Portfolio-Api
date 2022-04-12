<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert([
            'name' => "BTC",
            'slug' => "bitcoin"
        ]);

        DB::table('currencies')->insert([
            'name' => "ETH",
            'slug' => 'ethereum'
        ]);

        DB::table('currencies')->insert([
            'name' => "I0TA",
            'slug' => 'iota'
        ]);
    }
}
