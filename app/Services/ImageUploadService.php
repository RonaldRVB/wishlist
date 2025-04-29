<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\UploadedFile;


class ImageUploadService
{
    public static function uploadAndConvertToWebp($file, $folder, $prefix = 'img_', $width = 1000, $quality = 75)
    {
        $manager = new ImageManager(new Driver());

        try {
            $image = $manager->read($file->getPathname())
                ->scale(width: $width)
                ->toWebp($quality);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'image' => 'Le fichier uploadé n\'est pas une image valide.',
            ]);
        }

        $filename = uniqid($prefix) . '.webp';
        $path = $folder . '/' . $filename;

        Storage::disk('public')->put($path, (string) $image);

        return $path;
    }

    public static function replaceImageWithSameName(UploadedFile $file, string $relativePath): void
    {
        $manager = new ImageManager(new Driver());

        $converted = $manager->read($file->getPathname())
            ->scale(width: 1000)
            ->toWebp(75);

        Storage::disk('public')->put($relativePath, (string) $converted);
    }

}
