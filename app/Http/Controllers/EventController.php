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

use Illuminate\Support\Facades\File;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('defaultImage') // si tu veux charger les images liées
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
        ]);
    }

    public function create()
    {
        $defaultImages = DefaultImage::orderBy('label')->get();

        return Inertia::render('Events/Create', [
            'defaultImages' => $defaultImages,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'event_date' => ['required', 'date'],
            'default_image_id' => ['nullable', 'exists:default_images,id'],
            'custom_image' => ['nullable', 'image', 'max:5120'], // 5MB max
            'emails' => ['nullable', 'array'],
            'emails.*' => ['nullable', 'email'],
        ], [
            'custom_image.max' => 'L’image ne doit pas dépasser 5 Mo.',
        ]);

        $validated['user_id'] = auth()->id();

        // Gestion de l'image personnalisée
        if ($request->hasFile('custom_image')) {
            $manager = new ImageManager(new Driver());

            $image = $manager->read($request->file('custom_image')->getPathname())
                ->scale(width: 1000)
                ->toWebp(75);

            $filename = uniqid('event_') . '.webp';
            $path = 'events/' . $filename;

            Storage::disk('public')->put($path, (string) $image);
            $validated['custom_image'] = '/storage/' . $path;
        }

        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        $validated['user_id'] = $request->user()->id;
        $validated['status_event_id'] = 1;


        // 🔹 Création de l’événement
        $event = Event::create($validated);

        // 🔸 Ajout des invitations s’il y en a
        if (!empty($validated['emails'])) {
            InvitationController::storeMultipleForEvent($validated['emails'], $event->id);
        }

        return redirect()->route('events.show', $event->id)->with('success', 'Événement créé avec succès.');
    }


    public function edit(Event $event)
    {
        $defaultImages = \App\Models\DefaultImage::orderBy('label')->get();

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
            'default_image_id' => ['nullable', 'exists:default_images,id'],
            'custom_image' => ['nullable', 'image', 'max:5120'], // max 5MB
        ]);

        // Gérer l’image personnalisée
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
            $validated['custom_image'] = '/storage/' . $path;
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
}
