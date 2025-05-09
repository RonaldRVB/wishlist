<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventWishlist extends Model
{
    protected $table = 'event_wishlist'; // nom personnalisé de la table pivot

    protected $fillable = [
        'event_id',
        'wishlist_id',
    ];

    public $timestamps = false; // la table n’a probablement pas de timestamps
}
