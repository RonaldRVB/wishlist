<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultImage extends Model
{
    protected $fillable = [
        'label',
        'path',
    ];
}
