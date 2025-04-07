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
        ], [
            'custom_image.max' => 'L’image ne doit pas dépasser 5 Mo.',
        ]);

        // Gérer l’upload si une image perso est fournie
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

        // Ajout de l'utilisateur actuel
        $validated['user_id'] = $request->user()->id;

        // Statut par défaut = "en préparation"
        $validated['status_event_id'] = 1;

        // Génération automatique du slug
        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();

        // Création de l’événement
        Event::create($validated);

        return redirect()->route('events.index')->with('success', 'Événement créé avec succès.');
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
            $manager = new ImageManager(new Driver());

            $image = $manager->read($request->file('custom_image')->getPathname())
                ->scale(width: 1000)
                ->toWebp(75);

            $filename = uniqid('event_') . '.webp';
            $path = 'events/' . $filename;

            Storage::disk('public')->put($path, (string) $image);
            $validated['custom_image'] = '/storage/' . $path;
        }
        // dd($validated); // juste avant $event->update(...)

        $event->update($validated);

        return redirect()->route('events.show', $event->id)->with('success', 'Événement mis à jour.');
    }
    /**
     * @param \App\Models\Event $event
     */
    public function destroy(Event $event)
    {
        /** @var \App\Models\Event $event */

        if (auth()->id() !== $event->user_id) {
            abort(403);
        }

        // Supprimer l'image personnalisée si elle existe
        if ($event->custom_image) {
            $imagePath = public_path($event->custom_image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Événement supprimé.');
    }
}
