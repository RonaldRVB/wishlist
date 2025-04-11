<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;

class InvitationController extends Controller
{
    public function store(Request $request)
    {
                /** @var Authenticatable|null $user */
                $user = Auth::user();

                if (!Auth::check()) {
                    abort(403);
                }

                $creatorId = $user->id;

        $validated = $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'email' => ['required', 'email'],
        ]);

    $invitation = Invitation::create([
        'event_id' => $validated['event_id'],
        'email' => $validated['email'],
    ]);

    // Si un utilisateur est connecté, on crée un participant
    if (auth()->check()) {
        $participant = Participant::create([
            'user_id' => auth()->id(),
        ]);

        $invitation->participant()->associate($participant);
        $invitation->save();
    }

        return redirect()->back()->with('success', 'Invitation envoyée !');
    }

}
