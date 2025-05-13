<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Participant;
use Illuminate\Http\Request;
use App\Models\Invitation;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\StatusUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\GuestCredentialsMail;


class ParticipantController extends Controller
{
    public function guestAccess(string $token)
    {
        $invitation = Invitation::where('token', $token)->with('event')->firstOrFail();

        return Inertia::render('Participants/GuestAccess', [
            'invitation' => $invitation,
        ]);
    }

    public function storeGuest(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'token' => ['required', 'exists:invitations,token'],
        ]);

        $invitation = Invitation::where('token', $validated['token'])
            ->with('event')
            ->firstOrFail();

        // Vérifie si un invité a déjà été créé
        $existing = User::where('email', $invitation->email)->first();
        if ($existing) {
            return back()->withErrors(['name' => 'Un accès invité existe déjà pour cette invitation.']);
        }

        // 🛠 Mot de passe temporaire
        $tempPassword = Str::random(10);

        // ID du statut invité
        $invitedStatusId = StatusUser::where('status_value', 'Invité')->value('id');

        // Création du compte invité
        $user = User::create([
            'name' => $validated['name'],
            'email' => $invitation->email,
            'password' => bcrypt($tempPassword),
            'status_user_id' => $invitedStatusId,
            'salutation_id' => 5,
        ]);

        $user->invitation_token = $invitation->token;
        $user->save();

        // Création du participant
        Participant::create([
            'invitation_id' => $invitation->id,
            'user_id' => $user->id,
            'event_id' => $invitation->event_id,
            'name' => $validated['name'],
            'invitation_token' => $invitation->token,
        ]);

        // Envoie l’email avec les identifiants
        Mail::to($invitation->email)->send(
            new GuestCredentialsMail($validated['name'], $invitation->email, $tempPassword)
        );

        // Connexion automatique
        Auth::login($user);

        // 🔁 Redirection Inertia pour forcer le rechargement complet
        return Inertia::location(route('invitations.wishlists', ['token' => $request->token]));
    }
}
