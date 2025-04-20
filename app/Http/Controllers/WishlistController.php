<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Inertia\Inertia;
use App\Models\Wishlist;
use Illuminate\Support\Str;


class WishlistController extends Controller
{

    public function index()
    {
        $wishlists = Wishlist::where('user_id', auth()->id())->latest()->get();

        return Inertia::render('Wishlists/Index', [
            'wishlists' => $wishlists
        ]);
    }

    public function public(string $slug)
    {
        $event = Event::where('slug', $slug)->with('wishlists.gifts')->firstOrFail();

        return Inertia::render('Wishlists/PublicShow', [
            'event' => $event,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        Wishlist::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'slug' => Str::slug($validated['title']) . '-' . uniqid(), // ✅ unique + lisible
        ]);

        return redirect()->route('wishlists.index')->with('success', 'Wishlist créée avec succès.');
    }
}
