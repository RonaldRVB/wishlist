<?php

use App\Http\Controllers\LegalDocumentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\LegalDocument;
use Inertia\Inertia;
use App\Http\Controllers\DefaultImageController;
use App\Http\Controllers\EventController;
use App\Http\Middleware\IsAdmin;

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


Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {
    // Routes User
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    // Routes LegalDocument
    Route::get('/documents', [LegalDocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [LegalDocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [LegalDocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/edit', [LegalDocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [LegalDocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [LegalDocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/documents/{document}', [LegalDocumentController::class, 'show'])->name('documents.show');

    // Routes DefaultImage
    Route::get('/images', [DefaultImageController::class, 'index'])->name('images.index');
    Route::get('/images/create', [DefaultImageController::class, 'create'])->name('images.create');
    Route::post('/images', [DefaultImageController::class, 'store'])->name('images.store');
    Route::get('/images/{defaultImage}', [DefaultImageController::class, 'show'])->name('images.show');
    Route::get('/images/{defaultImage}/edit', [DefaultImageController::class, 'edit'])->name('images.edit');
    Route::put('/images/{defaultImage}', [DefaultImageController::class, 'update'])->name('images.update');
    Route::get('/images/{image}/replace', [DefaultImageController::class, 'showReplaceForm'])->name('images.editReplace');
    Route::post('/images/{image}/replace', [DefaultImageController::class, 'storeReplacedImage'])->name('images.replace');
    Route::delete('/images/{defaultImage}', [DefaultImageController::class, 'destroy'])->name('images.destroy');
});

Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
