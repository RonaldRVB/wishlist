<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Wishlist;


class EventWishlistController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'wishlist_id' => 'required|exists:wishlists,id',
        ]);

        $event = Event::findOrFail($validated['event_id']);
        $event->wishlists()->syncWithoutDetaching([$validated['wishlist_id']]);

        return back()->with('message', 'La wishlist a bien été liée à l’événement.');
    }
}
