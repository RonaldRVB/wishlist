<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class LoginResponse implements LoginResponseContract
{

    public function toResponse($request)
    {
        $user = auth()->user();

        logger('🔍 SESSION:', session()->all());

        // Supprime l'URL forcée par Laravel
        session()->forget('url.intended');

        if ($user->salutation_id === 5) {
            $token = $user->invitation_token;

            if ($token) {
                return redirect()->route('invitations.wishlists', ['token' => $token]);
            }

            return redirect('/');
        }

        // ✅ Utilisateur classique
        return redirect()->route('dashboard')->with('intended_skipped', true);
    }
}
