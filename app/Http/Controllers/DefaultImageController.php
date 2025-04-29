<?php

namespace App\Http\Controllers;

use App\Models\DefaultImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use App\Services\ImageUploadService;

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
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'max:5120'], // max 5MB
        ]);

        // Upload + conversion via le service
        $validated['path'] = ImageUploadService::uploadAndConvertToWebp(
            $request->file('image'),
            'images',
            'img_'
        );

        DefaultImage::create([
            'label' => $validated['label'],
            'path' => $validated['path'], // ← sans /storage/
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
    }

    public function storeReplacedImage(Request $request, DefaultImage $image)
    {
        $request->validate([
            'file' => ['required', 'image', 'max:5120'],
        ]);

        $newFile = $request->file('file');

        // Récupérer le chemin relatif sans /storage/
        $relativePath = $image->path; // ex: "images/img_xyz.webp"

        // Sécurité : vérifier que le chemin ne contient pas de choses interdites
        if (!str_starts_with($relativePath, 'images/')) {
            abort(403, 'Chemin d’image non autorisé.');
        }

        // Remplacer l’image existante avec le même nom
        ImageUploadService::replaceImageWithSameName($newFile, $relativePath);

        return redirect()->route('images.index')->with('success', 'Image remplacée avec succès.');
    }

    public function showReplaceForm(DefaultImage $image)
    {
        return Inertia::render('Images/Replace', [
            'image' => $image,
        ]);
    }
}
