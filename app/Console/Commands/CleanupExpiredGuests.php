<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class CleanupExpiredGuests extends Command
{
    protected $signature = 'guests:cleanup';
    protected $description = 'Supprime les comptes invités 10 jours après la date de début de leur événement.';

    public function handle()
    {
        $total = 0;

        // 🧹 Étape 1 : Supprimer les invités (salutation_id = 5) sans participant
        $guestsWithoutParticipant = User::where('salutation_id', 5)
            ->whereDoesntHave('participantEntries')
            ->get();

        foreach ($guestsWithoutParticipant as $guest) {
            $guest->delete();
            $total++;
        }

        // 🧼 Étape 2 : Supprimer les invités (salutation_id = 5) dont l'event est passé
        $limitDate = Carbon::now()->subDays(10);

        $expiredGuests = User::where('salutation_id', 5)
            ->whereHas('participantEntries', function ($query) use ($limitDate) {
                $query->whereHas('event', function ($q) use ($limitDate) {
                    $q->whereDate('event_date', '<=', $limitDate);
                });
            })
            ->get();

        foreach ($expiredGuests as $guest) {
            $guest->delete();
            $total++;
        }

        $this->info("🎯 Invités supprimés : $total");
    }
}
