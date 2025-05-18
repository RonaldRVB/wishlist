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
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\EventWishlistController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\Auth\RegisterViewController;
use App\Http\Controllers\GiftReservationController;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DrawController;


Route::get('/post-register-redirect', function () {
    Log::info('🌀 Accès à la route post-register');

    if (session()->has('force_redirect_after_register')) {
        $url = session()->pull('force_redirect_after_register');
        Log::info('✅ Redirection vers : ' . $url);
        return redirect($url);
    }

    // Log::warning('⚠️ Aucune redirection en session → Dashboard');
    // return redirect()->route('dashboard');
})->name('post.register.redirect');

Route::get('/register', [RegisterViewController::class, 'create'])
    ->middleware(['guest'])
    ->name('register');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'title' => 'Wishlist - Bienvenue',
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()?->salutation_id === 5) {
            abort(403, 'Accès réservé aux membres inscrits.');
        }

        return Inertia::render('Dashboard');
    })->name('dashboard');
});


// Route::get('/mockup', fn() => Inertia::render('Mockup/Mockup'))->name('mockup');
// Route::get('/colors', fn() => Inertia::render('Mockup/ColorComparison'))->name('colors');


// ✅ Route publique
Route::get('/mentions-legales', function () {
    $document = LegalDocument::first();

    return Inertia::render('Documents/PublicMentions', [
        'document' => $document,
    ]);
})->name('legal.public');

// ✅ Route privée, pour utilisateurs connectés
Route::get('/documents/mentions-legales', function () {
    $document = LegalDocument::where('is_active', true)->latest()->first();

    return Inertia::render('Documents/LegalMentions', [
        'document' => $document,
    ]);
})->middleware('auth')->name('legal.mentions');



Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {
    // Routes User
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

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

Route::get('/events/{event}/invitations/create', [InvitationController::class, 'create'])->name('invitations.create');
Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');

Route::get('/events/{event}/invitations', [InvitationController::class, 'index'])->name('invitations.index');
Route::get('/events/{event}/invitations/edit', [InvitationController::class, 'edit'])->name('invitations.edit');
Route::post('/invitations/multiple', [InvitationController::class, 'storeMultiple'])->name('invitations.storeMultiple');
Route::delete('/invitations/{invitation}', [InvitationController::class, 'destroy'])->name('invitations.destroy');

Route::post('/invitations/{event}/send', [InvitationController::class, 'send'])
    ->name('invitations.send');

Route::post('/invitations/store-token', [InvitationController::class, 'storeTokenInSession'])
    ->name('invitations.storeTokenInSession');

Route::get('/invitations/{token}/accept', function ($token) {
    return "Invitation acceptée pour token : $token";
})->name('invitations.accept');

Route::get('/invitations/{token}/refuse', function ($token) {
    return "Invitation refusée pour token : $token";
})->name('invitations.refuse');

Route::get('/invitations/{token}/accept', [InvitationController::class, 'accept'])->name('invitations.accept');
Route::get('/invitations/{token}/refuse', [InvitationController::class, 'refuse'])->name('invitations.refuse');

Route::get('/participants/guest/{token}', [ParticipantController::class, 'guestAccess'])
    ->name('participants.guest.access');
Route::post('/participants/guest', [ParticipantController::class, 'storeGuest'])
    ->name('participants.storeGuest');

Route::get('/invitation/{token}/wishlists', [InvitationController::class, 'showWishlistsFromInvitation'])
    ->name('invitations.wishlists');

Route::get('/events/{event}/wishlists', [EventController::class, 'showWishlists'])->name('wishlists.byEvent');

// 1. Index général
Route::get('/wishlists', [WishlistController::class, 'index'])->name('wishlists.index');

// 2. Formulaire de création (important avant {wishlist})
Route::get('/wishlists/create', [WishlistController::class, 'create'])->name('wishlists.create');

// 3. Enregistrement
Route::post('/wishlists', [WishlistController::class, 'store'])->name('wishlists.store');

// 4. Édition (important avant {wishlist})
Route::get('/wishlists/{wishlist}/edit', [WishlistController::class, 'edit'])->name('wishlists.edit');

// 5. Mise à jour
Route::put('/wishlists/{wishlist}', [WishlistController::class, 'update'])->name('wishlists.update');

// 6. Suppression
Route::delete('/wishlists/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlists.destroy');

// 7. Affichage d’une wishlist privée
Route::get('/wishlists/{wishlist}', [WishlistController::class, 'show'])->name('wishlists.show');

// 8. Affichage d’une wishlist publique (slug unique)
Route::get('/w/{slug}', [WishlistController::class, 'public'])->name('wishlists.public');

Route::post('/wishlists/{wishlist}/events/attach', [WishlistController::class, 'attachEvent'])->name('wishlists.events.attach');
Route::post('/wishlists/{wishlist}/events/detach', [WishlistController::class, 'detachEvent'])->name('wishlists.events.detach');

Route::get('/gifts', [GiftController::class, 'index'])->name('gifts.index');
Route::get('/gifts/create', [GiftController::class, 'create'])->name('gifts.create');
Route::post('/gifts', [GiftController::class, 'store'])->name('gifts.store');
Route::get('/gifts/{gift}', [GiftController::class, 'show'])->name('gifts.show');
Route::get('/gifts/{gift}/edit', [GiftController::class, 'edit'])->name('gifts.edit');
Route::post('/gifts/{gift}/update', [GiftController::class, 'update'])->name('gifts.update');
Route::post('/gifts/{gift}/wishlists/attach', [GiftController::class, 'attachWishlist'])->name('gifts.wishlists.attach');
Route::post('/gifts/{gift}/wishlists/detach', [GiftController::class, 'detachWishlist'])->name('gifts.wishlists.detach');
Route::put('/gifts/{gift}/wishlists', [GiftController::class, 'updateWishlists'])->name('gifts.updateWishlists');
Route::delete('/gifts/{gift}', [GiftController::class, 'destroy'])->name('gifts.destroy');
Route::post('/gifts/{gift}/reserve', [GiftReservationController::class, 'reserve'])->name('gifts.reserve');
Route::post('/gifts/{gift}/cancel', [GiftReservationController::class, 'cancelReservation'])->name('gifts.cancelReservation');


Route::get('/invitations/{token}/accepted', [InvitationController::class, 'handleAcceptedInvitation'])
    ->name('invitations.handle.accepted');

Route::get('/mes-invitations', [InvitationController::class, 'myInvitations'])->name('invitations.mine');


Route::get('/invitations/after-register/{event}', function (Event $event) {
    // S’il n’y a qu’une wishlist liée
    if ($event->wishlists->count() === 1) {
        return redirect()->route('wishlists.public', ['slug' => $event->wishlists->first()->slug]);
    }

    // S’il y en a plusieurs → page de sélection
    return redirect()->route('wishlists.byEvent', $event);
})->name('invitations.redirectAfterRegister');

Route::get('/draw', [DrawController::class, 'index'])->name('draw.index');
Route::post('/draw', [DrawController::class, 'store'])->name('draw.store');
Route::get('/events/{event}/draw/test', [DrawController::class, 'drawFromInvitations'])->name('draw.fromInvitations');
Route::get('/events/{event}/draw/final', [DrawController::class, 'drawFromParticipants'])->name('draw.fromParticipants');


// Route::get('/tests', function () {
//     return Inertia::render('Tests');
// })->name('tests');
