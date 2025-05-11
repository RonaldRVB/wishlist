<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use App\Models\Wishlist;
use Illuminate\Support\Str;
use \App\Models\Participant;
use Illuminate\Support\Facades\Log;
use \App\Models\EventWishlist;

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

        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'salutation_id' => $input['salutation_id'],
            'status_user_id' => 1,
            'role' => 'user',
        ]);

        if (session()->has('invitation_token')) {
            $token = session()->get('invitation_token'); // on retire le token de la session
            $invitation = \App\Models\Invitation::where('token', $token)->first();

            if ($invitation?->event_id) {
                Participant::create([
                    'event_id' => $invitation->event_id,
                    'invitation_id' => $invitation->id,
                    'user_id' => $user->id,
                    'name' => $user->name,
                ]);

                session()->put('redirect_to_event', $invitation->event_id);
                Log::info('✅ redirect_to_event set', ['event_id' => $invitation->event_id]);
            }
        }

        Wishlist::create([
            'title' => 'Ma liste personnelle',
            'description' => 'Cette liste est votre espace personnel pour ajouter des idées de cadeaux à partager par la suite...
             Ajoutez vos cadeaux personnels via le bouton \' Voir \' pour enrichir cette liste.',
            'user_id' => $user->id,
            'is_public' => false,
            'slug' => Str::slug('liste-de-' . $user->name) . '-' . uniqid(),
        ]);

        if (isset($invitation) && $invitation->event_id) {
            $wishlist = Wishlist::where('user_id', $user->id)->latest()->first(); // ou utilise celle que tu viens de créer
            EventWishlist::create([
                'event_id' => $invitation->event_id,
                'wishlist_id' => $wishlist->id,
            ]);
        }


        session()->put('force_redirect_after_register', route('invitations.redirectAfterRegister', ['event' => $invitation->event_id]));

        return $user;
    }
}
