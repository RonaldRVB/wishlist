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
use App\Notifications\WishlistInvitation;
use Illuminate\Support\Facades\Notification;
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
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'emails' => 'required|array|min:1',
            'emails.*' => 'required|email',
        ]);

        $event = Event::findOrFail($validated['event_id']);

        foreach ($validated['emails'] as $email) {
            // Vérifie si une invitation existe déjà pour cet event + email
            $exists = Invitation::where('event_id', $event->id)
                ->where('email', $email)
                ->exists();

            if ($exists) {
                return back()->withErrors([
                    'emails' => "L'adresse $email a déjà été invitée à cet événement.",
                ])->withInput();
            }

            // Crée l'invitation
            $invitation = Invitation::create([
                'event_id' => $event->id,
                'email' => $email,
                'token' => Str::uuid(),
            ]);

            // Envoie l'email juste après
            Notification::route('mail', $email)->notify(
                new WishlistInvitation(
                    $event->title,
                    route('invitations.accept', ['token' => $invitation->token]),
                    route('invitations.refuse', ['token' => $invitation->token])
                )
            );
        }

        return redirect()->route('events.show', $event->id)
            ->with('success', 'Invitations enregistrées et envoyées avec succès.');
    }


    public function destroy(Invitation $invitation)
    {
        $invitation->delete();

        return redirect()->back()->with('success', 'Invitation supprimée.');
    }


    public static function storeMultipleForEvent(array $emails, int $eventId): void
    {
        $event = Event::findOrFail($eventId);

        foreach ($emails as $email) {
            // On vérifie s'il existe déjà une invitation pour cet email + event
            $exists = Invitation::where('event_id', $eventId)
                ->where('email', $email)
                ->exists();

            if ($exists) {
                continue; // on skip les doublons
            }

            $invitation = Invitation::create([
                'event_id' => $eventId,
                'email' => $email,
                'token' => Str::uuid(),
            ]);

            // Envoi de l'email avec notification
            Notification::route('mail', $email)->notify(
                new WishlistInvitation(
                    $event->title,
                    route('invitations.accept', $invitation->token),
                    route('invitations.refuse', $invitation->token)
                )
            );
        }
    }

    public function send(Event $event)
    {
        $invitations = $event->invitations;

        foreach ($invitations as $invitation) {
            Notification::route('mail', $invitation->email)->notify(
                new WishlistInvitation(
                    $event->title,
                    route('invitations.accept', ['token' => $invitation->token]),
                    route('invitations.refuse', ['token' => $invitation->token])
                )
            );
        }

        return back()->with('success', 'Les invitations ont bien été envoyées.');
    }

    public function accept(string $token)
    {
        $invitation = Invitation::where('token', $token)->with('event')->firstOrFail();

        return Inertia::render('Participants/InvitationResponse', [
            'invitation' => $invitation,
            'status' => 'accepted',
        ]);
    }

    public function refuse(string $token)
    {
        $invitation = Invitation::where('token', $token)->with('event')->firstOrFail();

        return Inertia::render('Participants/InvitationResponse', [
            'invitation' => $invitation,
            'status' => 'refused',
        ]);
    }
}
