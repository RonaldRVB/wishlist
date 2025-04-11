<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(Participant::class, 'assigned_to_id');
    }

    public function assignedFrom()
    {
        return $this->hasOne(Participant::class, 'assigned_to_id');
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

}
