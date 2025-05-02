<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Inertia\Inertia;
use App\Models\Wishlist;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class WishlistController extends Controller
{
    use AuthorizesRequests;
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

    public function edit(Wishlist $wishlist)
    {
        // Vérifie que l'utilisateur est bien propriétaire de la liste
        if ($wishlist->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        return Inertia::render('Wishlists/Edit', [
            'wishlist' => $wishlist,
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

    public function destroy(Wishlist $wishlist)
    {
        // Vérification que l'utilisateur est bien propriétaire
        if ($wishlist->user_id !== auth()->id()) {
            abort(403);
        }

        // Empêcher la suppression de la liste personnelle
        if ($wishlist->title === 'Ma liste personnelle') {
            return redirect()->back()->with('error', 'La liste personnelle ne peut pas être supprimée.');
        }

        // On détache tous les gifts avant suppression
        $wishlist->gifts()->detach();

        $wishlist->delete();

        return redirect()->route('wishlists.index')->with('success', 'Wishlist supprimée avec succès.');
    }

    public function update(Request $request, Wishlist $wishlist)
    {
        // Vérifie que l'utilisateur est bien le propriétaire
        if ($wishlist->user_id !== auth()->id()) {
            abort(403);
        }

        // Protection spéciale : la wishlist personnelle ne peut être modifiée que partiellement
        if ($wishlist->title === 'Ma liste personnelle') {
            $request->validate([
                'description' => ['nullable', 'string', 'max:255'],
            ]);
        } else {
            $request->validate([
                'description' => ['nullable', 'string', 'max:255'],
            ]);
        }

        $wishlist->update([
            'description' => $request->description,
        ]);

        return redirect()->route('wishlists.index')->with('success', 'Liste mise à jour.');
    }

    public function show(Wishlist $wishlist)
    {
        $this->authorize('view', $wishlist);

        $gifts = $wishlist->gifts()
            ->select('gifts.id', 'gifts.name', 'gifts.description', 'gifts.image', 'gifts.purchase_url', 'gifts.is_reserved', 'gifts.reserved_by', 'gifts.reserved_at')
            ->orderBy('gifts.name')
            ->get();

        return Inertia::render('Wishlists/Show', [
            'wishlist' => $wishlist,
            'gifts' => $wishlist->gifts()->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Wishlist::class);
        return Inertia::render('Wishlists/Create');
    }
}
