<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    public function reservedBy()
    {
        return $this->belongsTo(User::class, 'reserved_by');
    }

    public function wishlists()
    {
        return $this->belongsToMany(Wishlist::class, 'gift_wishlist');
    }
}
