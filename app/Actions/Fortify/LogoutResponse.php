<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;

class LogoutResponse implements LogoutResponseContract
{
    public function toResponse($request)
    {
        // Nettoyage de session des tokens liés aux invitations
        session()->forget('invitation_token');
        session()->forget('redirect_to_event');
        session()->forget('force_redirect_after_register');

        return redirect('/');
    }
}
