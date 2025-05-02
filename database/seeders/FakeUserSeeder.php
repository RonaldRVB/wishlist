<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class FakeUserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(5)->create()->each(function ($user) {
            \App\Models\Wishlist::create([
                'title' => 'Ma liste personnelle',
                'description' => "Cette liste est votre espace personnel pour ajouter des idées de cadeaux.",
                'user_id' => $user->id,
                'is_public' => false,
                'slug' => \Illuminate\Support\Str::slug('liste-de-' . $user->name) . '-' . uniqid(),
            ]);
        });
    }
}
