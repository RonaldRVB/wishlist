<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

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
}
