<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusInvitation extends Model
{
    protected $table = 'status_invitation';

    public $timestamps = true;

    protected $fillable = ['status_value'];
}
