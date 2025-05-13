<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Event;

class DrawController extends Controller
{
    public function index()
    {
        return Inertia::render('Draws/Index');
    }

    public function store(Request $request)
    {
        $rawInput = collect($request->input('names'))->map(fn($n) => trim($n));

        $uniqueNames = $rawInput
            ->filter()
            ->unique()
            ->values();

        if ($uniqueNames->count() < 2) {
            return redirect()->route('draw.index')
                ->with('error', 'Il faut au moins deux noms valides et uniques pour effectuer le tirage.')
                ->withInput(['names' => $rawInput]);
        }

        if ($uniqueNames->count() > 100) {
            return redirect()->route('draw.index')
                ->with('error', 'Le nombre maximum de participants est limité à 100.')
                ->withInput(['names' => $rawInput]);
        }

        $shuffled = $uniqueNames->shuffle()->values();
        $assignments = [];

        for ($i = 0; $i < $shuffled->count(); $i++) {
            $giver = $shuffled[$i];
            $receiver = $shuffled[($i + 1) % $shuffled->count()];
            $assignments[] = [
                'from' => $giver,
                'to' => $receiver,
            ];
        }

        return Inertia::render('Draws/Index', [
            'assignments' => $assignments,
            'originalInput' => $rawInput,
        ]);
    }

    public function drawFromInvitations(Event $event)
    {
        $emails = $event->invitations->pluck('email')->filter()->unique()->values();

        $assignments = $this->generateAssignments($emails);

        if ($emails->count() <= 2) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Il faut au moins deux participants pour lancer le tirage.');
        }
        return Inertia::render('Events/Show', [
            'event' => $event->load('defaultImage', 'invitations.participant'),
            'invitations' => $event->invitations,
            'drawResult' => $assignments,
            'drawType' => 'invitations',
        ]);
    }

    public function drawFromParticipants(Event $event)
    {
        // Récupère les invitations avec l'utilisateur lié par email
        $invitations = $event->invitations()->with('user')->get();

        // Filtre les noms valides
        $names = $invitations->filter(function ($invitation) {
            return $invitation->status_invitation_id === 2
                && $invitation->user
                && $invitation->user->status_user_id === 1
                && !empty($invitation->user->name);
        })->pluck('user.name')->unique()->values();

        if ($names->count() < 3) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Il faut au moins trois participants acceptés pour un tirage valide.');
        }

        $assignments = $this->generateAssignments($names);

        return Inertia::render('Events/Show', [
            'event' => $event->load('defaultImage', 'invitations.participant'),
            'invitations' => $event->invitations,
            'drawResult' => $assignments,
            'drawType' => 'participants',
        ]);
    }





    private function generateAssignments($list)
    {
        if ($list->count() < 2) {
            return null;
        }

        $shuffled = $list->shuffle()->values();
        $assignments = [];

        for ($i = 0; $i < $shuffled->count(); $i++) {
            $giver = $shuffled[$i];
            $receiver = $shuffled[($i + 1) % $shuffled->count()];
            $assignments[] = [
                'from' => $giver,
                'to' => $receiver,
            ];
        }

        return $assignments;
    }
}
