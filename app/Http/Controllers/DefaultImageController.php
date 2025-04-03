<?php

namespace App\Http\Controllers;

use App\Models\DefaultImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class DefaultImageController extends Controller
{
    public function index()
    {
        $images = DefaultImage::orderBy('label')->get();

        return Inertia::render('Images/Index', [
            'images' => $images,
        ]);
    }

    public function create()
    {
        return Inertia::render('Images/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'max:5120'], // max 5MB
        ]);

        $file = $request->file('image');

        // On instancie l’image avec le manager (version 3)
        $manager = new ImageManager(new Driver());

        $image = $manager->read($file->getPathname())
            ->scale(width: 1000) // ← conserve le ratio
            ->toWebp(75); // WebP avec 75% qualité

        $filename = uniqid('img_') . '.webp';
        $path = 'images/' . $filename;

        // Stocker dans le disque public
        Storage::disk('public')->put($path, (string) $image);

        // Enregistrement en base
        DefaultImage::create([
            'label' => $request->label,
            'path' => '/storage/' . $path,
        ]);

        return redirect()->route('images.index');
    }

    public function show(DefaultImage $defaultImage)
    {
        return Inertia::render('Images/Show', [
            'image' => $defaultImage,
        ]);
    }

    public function edit(DefaultImage $defaultImage)
    {
        return Inertia::render('Images/Edit', [
            'defaultImage' => $defaultImage,
        ]);
    }

    public function update(Request $request, DefaultImage $defaultImage)
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'image_path' => ['required', 'string', 'max:255'],
        ]);

        $defaultImage->update($validated);

        return redirect()->route('images.index');
    }

    public function destroy(DefaultImage $defaultImage)
    {
        $imagePath = public_path($defaultImage->path);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        $defaultImage->delete();

        return redirect()->route('images.index')->with('success', 'Image supprimée.');

        return redirect()->route('images.index')->with('success', 'Image supprimée.');
    }

    public function storeReplacedImage(Request $request, DefaultImage $image)
    {
        $request->validate([
            'file' => 'required|image|max:5120', // tu peux adapter la taille max
        ]);

        $newFile = $request->file('file');

        // Lire le fichier avec Intervention Image
        $manager = new ImageManager(new Driver());

        $converted = $manager->read($newFile->getPathname())
            ->scale(width: 1000)
            ->toWebp(75);

        // Remplacer physiquement l’ancien fichier
        $oldPath = public_path($image->path);

        if (File::exists($oldPath)) {
            File::delete($oldPath);
        }

        // Réécriture du fichier .webp avec le même nom
        file_put_contents($oldPath, (string) $converted);

        return redirect()->route('images.index')->with('success', 'Image remplacée avec succès.');
    }

    public function showReplaceForm(DefaultImage $image)
    {
        return Inertia::render('Images/Replace', [
            'image' => $image,
        ]);
    }
}
