<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Models\LegalDocument;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});


Route::get('/mockup', fn() => Inertia::render('Mockup/Mockup'))->name('mockup');
Route::get('/colors', fn() => Inertia::render('Mockup/ColorComparison'))->name('colors');


Route::get('/mentions-legales', function () {
    $document = LegalDocument::where('is_active', true)->latest()->first();

    return Inertia::render('Documents/LegalMentions', [
        'document' => $document,
    ]);
})->name('legal.mentions');
