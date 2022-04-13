<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Neilas',
            'email' => 'neilas.antanavicius@teltonika.lt',
            'password' => bcrypt('teltonika'),
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
