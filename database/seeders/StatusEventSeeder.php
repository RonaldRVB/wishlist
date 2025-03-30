<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('status_events')->insert([
            ['status_value' => 'En préparation'],
            ['status_value' => 'Actif'],
            ['status_value' => 'Clôturé'],
        ]);
    }
}
