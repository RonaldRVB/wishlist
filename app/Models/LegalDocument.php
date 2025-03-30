<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalDocument extends Model
{
    public function userAcceptances()
    {
        return $this->hasMany(UserLegalDocument::class);
    }
}
