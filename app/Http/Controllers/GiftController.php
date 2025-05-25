<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageUploadService;
use Inertia\Inertia;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;


class GiftController extends Controller
{
    public function index()
    {
        $gifts = Gift::with('wishlists.events') // pour accéder aux events liés via wishlist
            ->whereHas('wishlists', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        return Inertia::render('Gifts/Index', [
            'gifts' => $gifts
        ]);
    }

    public function show(Gift $gift)
    {
        return Inertia::render('Gifts/Show', [
            'gift' => $gift->load('wishlists'),
            'userWishlists' => auth()->user()
                ->wishlists()
                ->orderBy('title')
                ->get(),
        ]);
    }


    public function create()
    {
        $wishlists = auth()->user()->wishlists()->select('id', 'title')->get();

        return Inertia::render('Gifts/Create', [
            'wishlists' => $wishlists,
        ]);
    }
    public function edit(Gift $gift)
    {
        return inertia('Gifts/Edit', [
            'gift' => $gift,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'wishlist_id' => 'required|exists:wishlists,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'purchase_url' => 'nullable|url',
        ]);

        // Traitement de l'image si présente
        if ($request->hasFile('image')) {
            $path = ImageUploadService::uploadAndConvertToWebp(
                $request->file('image'),
                'images',
                'gift_'
            );

            $validated['image'] = $path;
        }

        // Création du cadeau
        $gift = Gift::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image' => $validated['image'] ?? null,
            'purchase_url' => $validated['purchase_url'] ?? null,
        ]);

        // Association à la wishlist
        $gift->wishlists()->attach($validated['wishlist_id']);

        return redirect()->back()->with('success', 'Cadeau ajouté à la wishlist');
    }

    public function update(Request $request, Gift $gift)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'url' => ['nullable', 'url', 'max:255'],
            'image' => ['nullable', 'image', 'max:5120'], // 5 Mo max
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($gift->image && Storage::disk('public')->exists($gift->image)) {
                Storage::disk('public')->delete($gift->image);
            }

            // Nouvelle image via le service centralisé
            $path = ImageUploadService::uploadAndConvertToWebp(
                $request->file('image'),
                'images',
                'gift_'
            );

            $validated['image'] = $path;
        } else {
            unset($validated['image']); // évite de mettre `image = null`
        }

        $gift->update($validated);

        return redirect()->route('gifts.index')->with('success', 'Cadeau mis à jour avec succès.');
    }

    public function attachWishlist(Request $request, Gift $gift)
    {
        $request->validate([
            'wishlist_id' => 'required|exists:wishlists,id',
        ]);

        $gift->wishlists()->syncWithoutDetaching([$request->wishlist_id]);

        return back()->with('success', 'Wishlist associée au cadeau.');
    }

    public function detachWishlist(Request $request, Gift $gift)
    {
        $request->validate([
            'wishlist_id' => 'required|exists:wishlists,id',
        ]);

        $gift->wishlists()->detach($request->wishlist_id);

        return back()->with('success', 'Wishlist dissociée du cadeau.');
    }

    public function updateWishlists(Request $request, Gift $gift)
    {
        $request->validate([
            'wishlist_ids' => 'array',
            'wishlist_ids.*' => 'exists:wishlists,id',
        ]);

        // On synchronise les wishlists associées au gift
        $gift->wishlists()->sync($request->wishlist_ids);

        return back()->with('success', 'Les wishlists ont été mises à jour.');
    }

    public function destroy(Gift $gift)
    {
        // Supprimer l’image si elle existe
        if ($gift->image && Storage::disk('public')->exists($gift->image)) {
            Storage::disk('public')->delete($gift->image);
        }

        // Détacher toutes les wishlists
        $gift->wishlists()->detach();

        // Supprimer le cadeau
        $gift->delete();

        return redirect()->route('gifts.index')->with('success', 'Cadeau supprimé avec succès.');
    }
}
