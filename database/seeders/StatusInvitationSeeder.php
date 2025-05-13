<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusInvitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('status_invitation')->insert([
            ['status_value' => 'En attente'],
            ['status_value' => 'Acceptée'],
            ['status_value' => 'Refusée'],
        ]);
    }
}
