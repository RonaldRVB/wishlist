<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('status_users')->insert([
            ['id' => 1, 'status_value' => 'Invité'],
            ['id' => 2, 'status_value' => 'En validation'],
            ['id' => 3, 'status_value' => 'Actif'],
        ]);
    }
}
