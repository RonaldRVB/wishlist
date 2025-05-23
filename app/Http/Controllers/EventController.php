<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\DefaultImage;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
use App\Http\Controllers\InvitationController;
use App\Services\ImageUploadService;
use App\Models\Wishlist;
use Illuminate\Support\Facades\File;
use App\Models\Participant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('defaultImage')
            ->where('user_id', auth()->id()) // filtre par utilisateur
            ->orderBy('event_date')
            ->get();

        return Inertia::render('Events/Index', [
            'events' => $events,
        ]);
    }

    public function show(Event $event)
    {
        return Inertia::render('Events/Show', [
            'event' => $event->load('defaultImage'), // facultatif
            'invitations' => $event->invitations()->with('participant')->get(),
        ]);
    }

    public function create()
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->where('title', '!=', 'Ma liste personnelle')
            ->get();

        if ($wishlists->isEmpty()) {
            return redirect()->route('wishlists.index')
                ->with('error', 'Votre liste personnelle n\'est pas une wishlist valide pour la création d\'un événement.
                Vous devez d\'abord créer une autre wishlist qui, elle, sera valide pour un ou plusieurs événement(s).');
        }

        $defaultImages = DefaultImage::orderBy('label')->get();

        return Inertia::render('Events/Create', [
            'defaultImages' => $defaultImages,
            'wishlists' => $wishlists,
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'event_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:event_date'],
            'default_image_id' => ['nullable', 'exists:default_images,id'],
            'custom_image' => ['nullable', 'image', 'max:5120'],
            'emails' => ['nullable', 'array'],
            'emails.*' => ['nullable', 'email'],
            'is_collaborative' => ['required', 'boolean'],
        ], [
            'custom_image.max' => 'L’image ne doit pas dépasser 5 Mo.',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        $validated['status_event_id'] = 1;
        $validated['is_collaborative'] = filter_var($request->input('is_collaborative'), FILTER_VALIDATE_BOOLEAN);

        if ($request->hasFile('custom_image')) {
            $validated['custom_image'] = ImageUploadService::uploadAndConvertToWebp(
                $request->file('custom_image'),
                'events',
                'event_'
            );
        }

        $event = Event::create($validated);

        // Ajout automatique du créateur comme participant
        Participant::create([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'status' => 'accepted',
            'name' => auth()->user()->name,
        ]);

        // 🧩 Lier une wishlist si une ID a été envoyée
        if ($request->filled('wishlist_id')) {
            $event->wishlists()->attach($request->input('wishlist_id'));
        }

        if (!empty($validated['emails'])) {
            $emails = array_filter($validated['emails']); // Supprime les emails vides ou null
            if (!empty($emails)) {
                InvitationController::storeMultipleForEvent($emails, $event->id);
            }
        }

        return redirect()->route('events.index', $event->id)->with('success', 'Événement créé avec succès.');
    }


    public function edit(Event $event)
    {
        $defaultImages = DefaultImage::orderBy('label')->get();

        return Inertia::render('Events/Edit', [
            'event' => $event,
            'defaultImages' => $defaultImages,
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'event_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:event_date'],
            'default_image_id' => ['nullable', 'exists:default_images,id'],
            'custom_image' => ['nullable', 'image', 'max:5120'], // max 5MB
        ]);

        // Supprimer l'ancienne image personnalisée si une nouvelle est uploadée
        if ($request->hasFile('custom_image') && $event->custom_image) {
            $oldPath = public_path($event->custom_image);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        // Gérer la nouvelle image personnalisée
        if ($request->hasFile('custom_image')) {
            // Supprimer l’ancienne image personnalisée si elle existe
            if ($event->custom_image && Storage::disk('public')->exists(str_replace('/storage/', '', $event->custom_image))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $event->custom_image));
            }

            $manager = new ImageManager(new Driver());

            $image = $manager->read($request->file('custom_image')->getPathname())
                ->scale(width: 1000)
                ->toWebp(75);

            $filename = uniqid('event_') . '.webp';
            $path = 'events/' . $filename;

            Storage::disk('public')->put($path, (string) $image);
            $validated['custom_image'] = $path;
        }

        $event->update($validated);

        return redirect()->route('events.show', $event->id)->with('success', 'Événement mis à jour.');
    }

    public function destroy(Event $event)
    {
        if (auth()->id() !== $event->user_id) {
            abort(403);
        }

        // Supprimer l'image personnalisée si elle existe
        if ($event->custom_image) {
            // On retire le préfixe "/storage/" pour cibler le fichier réel dans storage/app/public
            $relativePath = str_replace('/storage/', '', $event->custom_image);

            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            }
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Événement supprimé.');
    }

    public function showWishlists(Event $event)
    {
        $user = auth()->user();

        // Recharger l’événement avec la relation defaultImage
        $event->load('defaultImage');

        // On récupère toutes les wishlists liées à l’événement
        $wishlists = $event->wishlists()
            ->with(['gifts', 'user'])
            ->get()
            // On filtre pour exclure la wishlist personnelle
            ->reject(fn($w) => $w->title === 'Ma liste personnelle');

        // On détecte si l'utilisateur connecté a une wishlist pour cet événement (pas la perso)
        $userWishlist = $wishlists->first(fn($w) => $w->user_id === $user->id);

        return Inertia::render('Wishlists/IndexForEvent', [
            'event' => $event,
            'wishlists' => $wishlists->values(), // reset des clés si besoin
            'userWishlist' => $userWishlist,
        ]);
    }
}
