<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Salutation;
use Inertia\Inertia;

class RegisterViewController extends Controller
{
    public function create()
    {
        $orderedValues = ['Madame', 'Mademoiselle', 'Monsieur', 'Autre'];

        $salutations = Salutation::whereIn('salutation_value', $orderedValues)
            ->get()
            ->sortBy(function ($item) use ($orderedValues) {
                return array_search($item->salutation_value, $orderedValues);
            })
            ->values(); // ← réindexe proprement le tableau

        return Inertia::render('Auth/Register', [
            'salutations' => $salutations,
        ]);
    }

}
