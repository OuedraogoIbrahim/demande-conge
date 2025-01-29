<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class establishment extends Model
{
    /** @use HasFactory<\Database\Factories\EstablishmentFactory> */
    use HasFactory, HasUuids;

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function filieres(): HasMany
    {
        return $this->hasMany(Filiere::class);
    }
}
