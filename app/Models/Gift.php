<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'purchase_url',
        'quantity',
        'is_reserved',
        'reserved_by',
        'reserved_at',
    ];

    public function reservedBy()
    {
        return $this->belongsTo(User::class, 'reserved_by');
    }

    public function wishlists()
    {
        return $this->belongsToMany(Wishlist::class, 'gift_wishlist');
    }
}
