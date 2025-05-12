<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use Illuminate\Http\Request;

class GiftReservationController extends Controller
{
    public function reserve(Request $request, Gift $gift)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->back()->with('error', 'Tu dois être connecté pour réserver un cadeau.');
        }

        if ($gift->is_reserved) {
            return redirect()->back()->with('error', 'Ce cadeau est déjà réservé.');
        }

        $gift->update([
            'is_reserved' => true,
            'reserved_by' => $user->id,
            'reserved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Le cadeau a bien été réservé.');
    }

    public function cancelReservation(Request $request, Gift $gift)
    {
        $user = auth()->user();

        // Vérifie que l'utilisateur est bien celui qui a réservé
        if (!$user || $gift->reserved_by !== $user->id) {
            abort(403, "Tu ne peux pas annuler cette réservation.");
        }

        // Réinitialise les champs
        $gift->update([
            'is_reserved' => false,
            'reserved_by' => null,
            'reserved_at' => null,
        ]);

        return redirect()->back()->with('success', 'Réservation annulée avec succès.');
    }
}
