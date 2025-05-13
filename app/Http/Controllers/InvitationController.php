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
        if ($event->wishlists()->count() === 0) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Impossible d’inviter des participants : aucune wishlist n’est liée à cet événement.');
        }

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

        if (auth()->check()) {
            $user = auth()->user();

            // Vérification stricte de l'email associé à l'invitation
            if (trim($invitation->email) !== trim($user->email)) {
                abort(403, 'Cette invitation ne correspond pas à votre compte.');
            }

            // Vérifie si l'utilisateur est déjà participant
            $alreadyParticipant = Participant::where([
                ['event_id', '=', $invitation->event_id],
                ['user_id', '=', $user->id],
            ])->exists();

            if (! $alreadyParticipant) {
                Participant::create([
                    'event_id' => $invitation->event_id,
                    'invitation_id' => $invitation->id,
                    'user_id' => $user->id,
                    'name' => $user->name,
                ]);
            }

            return redirect()->route('wishlists.byEvent', ['event' => $invitation->event_id]);
        }

        // Cas non connecté : affiche la vue explicative
        return Inertia::render('Participants/InvitationResponse', [
            'invitation' => $invitation,
            'status' => 'accepted',
            'requires_account' => $invitation->event->is_collaborative,
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

    public function storeTokenInSession(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string', 'exists:invitations,token'],
        ]);

        session(['invitation_token' => $request->token]);

        return back(); // ou return response()->noContent(); si tu préfères une réponse vide
    }

    public function handleAcceptedInvitation($token)
    {
        $invitation = Invitation::where('token', $token)->with('event.wishlists')->firstOrFail();
        $event = $invitation->event;

        // Cas 1 : une seule wishlist → redirection vers la vue publique de la wishlist
        if ($event->wishlists->count() === 1) {
            $wishlist = $event->wishlists->first();
            return redirect()->route('wishlists.public', ['slug' => $wishlist->slug]);
        }

        // Cas 2 : plusieurs wishlists → redirection vers la vue dédiée à la liste des wishlists de l'événement
        return redirect()->route('wishlists.byEvent', ['event' => $event->id]);
    }

    public function myInvitations()
    {
        $user = auth()->user();

        $participations = Participant::with('event.wishlists')
            ->where('user_id', $user->id)
            ->get();

        return Inertia::render('Invitations/MyInvitations', [
            'participations' => $participations,
        ]);
    }

    public function showWishlistsFromInvitation(string $token)
    {
        $invitation = Invitation::where('token', $token)
            ->with('event.wishlists.gifts')
            ->firstOrFail();

        $wishlists = $invitation->event->wishlists;

        return Inertia::render('Wishlists/GuestShow', [
            'wishlist' => $wishlists->first(),
            'gifts' => $wishlists->first()?->gifts ?? [],
        ]);
    }
}
