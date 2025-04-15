<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\Event;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;


class InvitationController extends Controller
{

    public function index(Event $event)
    {
        $invitations = $event->invitations()->with('participant')->get();

        return Inertia::render('Invitations/Index', [
            'event' => $event,
            'invitations' => $invitations,
        ]);
    }


    public function create(Event $event)
    {
        return Inertia::render('Invitations/Create', [
            'eventId' => $event->id,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $validated = $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'email' => ['required', 'email'],
        ]);

        Invitation::create([
            'event_id' => $validated['event_id'],
            'email' => $validated['email'],
            'token' => Str::uuid(),
        ]);

        return redirect()->back()->with('success', 'Invitation envoyée !');
    }

    public function edit(Event $event)
    {
        return Inertia::render('Invitations/Edit', [
            'eventId' => $event->id,
            'event' => $event, // Ajout de l'objet complet
        ]);
    }


    public function storeMultiple(Request $request)
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable|null $user */
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $validated = $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'emails' => ['required', 'array', 'min:1'],
            'emails.*' => ['required', 'email'],
        ]);

        foreach ($validated['emails'] as $email) {
            // Vérifier si une invitation existe déjà pour cet event + email
            $exists = Invitation::where('event_id', $validated['event_id'])
                ->where('email', $email)
                ->exists();

            if ($exists) {
                return back()->withErrors([
                    'emails' => "L'adresse $email a déjà été invitée à cet événement.",
                ])->withInput();
            }

            // Créer l’invitation
            Invitation::create([
                'event_id' => $validated['event_id'],
                'email' => $email,
                'token' => Str::uuid(),
            ]);
        }

        return redirect()->route('events.show', $validated['event_id'])->with('success', 'Invitations envoyées !');
    }

    public function destroy(Invitation $invitation)
    {
        $invitation->delete();

        return redirect()->back()->with('success', 'Invitation supprimée.');
    }

    public static function storeMultipleForEvent(array $emails, int $eventId): void
    {
        foreach ($emails as $email) {
            // On vérifie s'il existe déjà une invitation pour cet email + event
            $exists = Invitation::where('event_id', $eventId)
                ->where('email', $email)
                ->exists();

            if ($exists) {
                continue; // on skip les doublons
            }

            Invitation::create([
                'event_id' => $eventId,
                'email' => $email,
                'token' => Str::uuid(),
            ]);
        }
    }
}
