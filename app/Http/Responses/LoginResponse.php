<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Route;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        if (session()->has('invitation_token')) {
            $token = session()->pull('invitation_token');

            return redirect()->route('invitations.accept', ['token' => $token]);
        }

        return redirect()->intended(config('fortify.home'));
    }
}
