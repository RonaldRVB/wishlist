<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SalutationSeeder::class,
            StatusUserSeeder::class,
            StatusEventSeeder::class,
            LegalDocumentSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'salutation_id' => 3,
            'status_user_id' => 3,
            'role' => UserRole::ADMIN,
        ]);

        User::factory(10)->create();
    }
}
