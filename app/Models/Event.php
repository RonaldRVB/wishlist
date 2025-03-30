<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(StatusEvent::class, 'status_event_id');
    }

    public function eventImage()
    {
        return $this->belongsTo(EventImage::class);
    }

    public function wishlists()
    {
        return $this->belongsToMany(Wishlist::class, 'event_wishlist');
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
