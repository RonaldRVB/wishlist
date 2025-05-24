<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{

    public function toResponse($request)
    {
        $user = auth()->user();

        // Debug : voir le contenu de la session
        logger('SESSION:', session()->all());

        // Gère la redirection spéciale suite à une invitation acceptée avant login
        if (session()->has('redirect_invitation_token')) {
            $token = session()->pull('redirect_invitation_token'); // on retire le token de la session

            return redirect()->route('invitations.accept', ['token' => $token]);
        }

        // Supprime l'URL forcée par Laravel
        session()->forget('url.intended');

        // Guest connecté
        if ($user->salutation_id === 5) {
            $token = $user->invitation_token;

            if ($token) {
                return redirect()->route('invitations.wishlists', ['token' => $token]);
            }

            return redirect('/');
        }

        // Utilisateur classique
        return redirect()->route('dashboard')->with('intended_skipped', true);
    }
}
