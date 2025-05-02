<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use App\Models\Wishlist;
use Illuminate\Support\Str;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'salutation_id' => ['required', 'exists:salutations,id'],
            // supprime 'terms' si tu n'en as pas besoin
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'salutation_id' => $input['salutation_id'],
            'status_user_id' => 1,
            'role' => 'user',
        ]);

        Wishlist::create([
            'title' => 'Ma liste personnelle',
            'description' => 'Cette liste est votre espace personnel pour ajouter des idées de cadeaux à partager par la suite...
             Ajoutez vos cadeaux personnels via le bouton \' Voir \' pour enrichir cette liste.',
            'user_id' => $user->id,
            'is_public' => false,
            'slug' => Str::slug('liste-de-' . $user->name) . '-' . uniqid(),
        ]);

        return $user;
    }
}
