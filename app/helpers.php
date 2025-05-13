<?php

use Illuminate\Support\Facades\Auth;

if (! function_exists('isGuestUser')) {
    function isGuestUser(): bool
    {
        return Auth::check() && Auth::user()->salutation_id === 5;
    }
}
