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
            ->with('event.wishlists.gifts')
            ->firstOrFail();

        $existing = User::where('email', 'guest_' . $invitation->token . '@guest.local')->first();
        if ($existing) {
            return back()->withErrors(['name' => 'Un accès invité existe déjà pour ce lien.']);
        }

        if ($invitation->event->wishlists->isEmpty()) {
            return back()->withErrors(['name' => 'Aucune wishlist n’est liée à cet événement.']);
        }

        try {
            DB::beginTransaction();

            $invitedStatusId = StatusUser::where('status_value', 'Invité')->value('id');

            $user = User::create([
                'name' => $validated['name'],
                'email' => 'guest_' . $invitation->token . '@guest.local',
                'password' => bcrypt(Str::random(32)),
                'status' => $invitedStatusId,
                'salutation_id' => 5,
            ]);

            Participant::create([
                'invitation_id' => $invitation->id,
                'user_id' => $user->id,
                'event_id' => $invitation->event_id,
                'name' => $validated['name'],
            ]);

            DB::commit();

            Auth::login($user);

            return redirect()->route('invitations.wishlists', ['token' => $request->token]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors([
                'name' => "Une erreur s’est produite : " . $e->getMessage(),
            ]);
        }
    }
}
