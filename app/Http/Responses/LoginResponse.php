<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        // Si un token d'invitation a été transmis dans le formulaire de connexion
        if ($request->filled('invitation_token')) {
            return redirect()->route('invitations.accept', [
                'token' => $request->input('invitation_token'),
            ]);
        }

        // Sinon, redirection normale
        return redirect()->intended(config('fortify.home', '/dashboard'));
    }
}
