<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    public function create()
    {
        $wishlists = auth()->user()->wishlists()->select('id', 'title')->get();

        return Inertia::render('Gifts/Create', [
            'wishlists' => $wishlists,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'wishlist_id'    => 'required|exists:wishlists,id',
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|max:5120', // image uploadée max 5 MB
            'purchase_url'   => 'nullable|url',
        ]);

        // Gérer l'image si présente
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('gifts', 'public');
        }

        // Créer le cadeau
        $gift = Gift::create([
            'name'         => $validated['name'],
            'description'  => $validated['description'] ?? null,
            'image'        => $validated['image'] ?? null,
            'purchase_url' => $validated['purchase_url'] ?? null,
        ]);

        if ($request->hasFile('image')) {
            $manager = new ImageManager(new Driver());

            $image = $manager->read($request->file('image')->getPathname())
                ->scale(width: 1000)
                ->toWebp(75);

            $filename = uniqid('gift_') . '.webp';
            $path = 'images/' . $filename;

            Storage::disk('public')->put($path, (string) $image);
            $validated['image'] = '/storage/' . $path;
        }

        // Lier à la wishlist
        $gift->wishlists()->attach($validated['wishlist_id']);

        return redirect()->back()->with('success', 'Cadeau ajouté à la wishlist');
    }
}
