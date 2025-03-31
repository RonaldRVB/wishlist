<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalutationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('salutations')->insert([
            ['id' => 1, 'salutation_value' => 'Madame'],
            ['id' => 2, 'salutation_value' => 'Mademoiselle'],
            ['id' => 3, 'salutation_value' => 'Monsieur'],
            ['id' => 4, 'salutation_value' => 'Autre'],
        ]);
    }
}
