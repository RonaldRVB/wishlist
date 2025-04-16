<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'end_date',
        'is_public',
        'default_image_id',
        'custom_image',
        'user_id',
        'status_event_id',
        'slug',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(StatusEvent::class, 'status_event_id');
    }

    public function defaultImage()
    {
        return $this->belongsTo(DefaultImage::class);
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
