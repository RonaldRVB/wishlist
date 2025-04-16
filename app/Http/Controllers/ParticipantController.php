<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Participant;
use Illuminate\Http\Request;
use App\Models\Invitation;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\StatusUser;

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

        $invitation = Invitation::where('token', $validated['token'])->with('event')->firstOrFail();

        // Vérifie si un utilisateur invité existe déjà
        $existing = User::where('email', 'guest_' . $invitation->token . '@guest.local')->first();

        if ($existing) {
            return back()->withErrors(['name' => 'Un accès invité existe déjà pour ce lien.']);
        }

        // ⚠️ Remplace la valeur 1 par l'ID correspondant à "Invité" dans ta table Status_user
        $invitedStatusId = StatusUser::where('status_value', 'Invité')->value('id');

        // Création d’un utilisateur invité temporaire
        $user = User::create([
            'name' => $validated['name'],
            'email' => 'guest_' . $invitation->token . '@guest.local',
            'password' => bcrypt(Str::random(32)),
            'status' => $invitedStatusId, // 🠔 statut invité
            'salutation_id' => 5,
        ]);

        // Création du participant lié
        Participant::create([
            'invitation_id' => $invitation->id,
            'user_id' => $user->id,
            'event_id' => $invitation->event_id,
            'name' => $validated['name'],
        ]);

        return redirect()->route('events.show', ['event' => $invitation->event->slug])
            ->with('message', 'Ton accès invité a bien été enregistré. Conserve ce lien pour revenir !');
    }
}
