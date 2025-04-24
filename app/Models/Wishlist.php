<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'slug',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gifts()
    {
        return $this->belongsToMany(Gift::class, 'gift_wishlist');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_wishlist');
    }
}
