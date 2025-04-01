<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalDocument extends Model
{

    protected $fillable = [
        'title',
        'version',
        'content',
        'is_active',
    ];

    public function userAcceptances()
    {
        return $this->hasMany(UserLegalDocument::class);
    }
}
