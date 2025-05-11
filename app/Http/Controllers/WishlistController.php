<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Inertia\Inertia;
use App\Models\Wishlist;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;


class WishlistController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $wishlists = Wishlist::where('user_id', auth()->id())->latest()->get();

        return Inertia::render('Wishlists/Index', [
            'wishlists' => $wishlists,
            'flashError' => session('error'),
        ]);
    }

    public function public(string $slug)
    {
        $wishlist = Wishlist::where('slug', $slug)
            ->with(['gifts', 'events']) // charge les events liés
            ->firstOrFail();

        $eventId = $wishlist->events->first()?->id ?? null; // si lié à plusieurs events, on prend le premier

        return Inertia::render('Wishlists/PublicShow', [
            'wishlist' => $wishlist,
            'eventId' => $eventId,
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
            'event_id' => 'nullable|exists:events,id',
        ]);

        $wishlist = Wishlist::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'slug' => Str::slug($validated['title']) . '-' . uniqid(),
        ]);

        if (!empty($validated['event_id'])) {
            $wishlist->events()->attach($validated['event_id']);
        }

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

        $user = Auth::user();

        return Inertia::render('Wishlists/Show', [
            'wishlist' => $wishlist->load('events'),
            'gifts' => $wishlist->gifts()
                ->select('gifts.id', 'name', 'description', 'image', 'purchase_url', 'is_reserved', 'reserved_by', 'reserved_at')
                ->orderBy('name')
                ->get(),
            'userEvents' => $user->events()->get(),
        ]);
    }


    public function create(Request $request)
    {
        $this->authorize('create', Wishlist::class);

        $eventId = $request->query('event_id');

        return Inertia::render('Wishlists/Create', [
            'eventId' => $eventId,
        ]);
    }


    public function attachEvent(Request $request, Wishlist $wishlist)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        // On interdit de lier la liste perso
        if ($wishlist->title === 'Ma liste personnelle') {
            return back()->with('error', 'Impossible de lier la liste personnelle à un événement.');
        }

        $wishlist->events()->syncWithoutDetaching([$request->event_id]);

        return back()->with('success', 'Événement associé à la wishlist.');
    }

    public function detachEvent(Request $request, Wishlist $wishlist)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        if ($wishlist->title === 'Ma liste personnelle') {
            return back()->with('error', 'Impossible de dissocier un événement de la liste personnelle.');
        }

        $wishlist->events()->detach($request->event_id);

        return back()->with('success', 'Événement dissocié de la wishlist.');
    }
}
