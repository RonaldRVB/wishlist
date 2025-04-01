<?php

use App\Http\Controllers\LegalDocumentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\LegalDocument;
use Inertia\Inertia;
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
});
