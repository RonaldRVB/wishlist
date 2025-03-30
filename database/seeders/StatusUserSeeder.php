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
            ['status_value' => 'Invité'],
            ['status_value' => 'En validation'],
            ['status_value' => 'Actif'],
        ]);
    }
}
