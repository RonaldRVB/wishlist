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
            ['salutation_value' => 'Madame'],
            ['salutation_value' => 'Mademoiselle'],
            ['salutation_value' => 'Monsieur'],
            ['salutation_value' => 'Autre'],
        ]);
    }
}
