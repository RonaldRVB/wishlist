<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Str;
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
            FakeUserSeeder::class
        ]);

        $admin = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'salutation_id' => 3,
            'status_user_id' => 3,
            'role' => UserRole::ADMIN,
        ]);

        // Wishlist::create([
        //     'title' => 'Ma liste personnelle',
        //     'description' => 'Cette liste est votre espace personnel pour ajouter des idées de cadeaux à partager par la suite...
        //      Ajoutez vos cadeaux personnels via le bouton \' Voir \' pour enrichir cette liste.',
        //     'user_id' => $admin->id,
        //     'is_public' => false,
        //     'slug' => Str::slug('liste-de-' . $admin->name) . '-' . uniqid(),
        // ]);

        User::factory(10)->create();
    }
}
