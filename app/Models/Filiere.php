<?php

namespace App\Models;

use Faker\Core\Coordinates;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filiere extends Model
{
    /** @use HasFactory<\Database\Factories\FiliereFactory> */
    use HasFactory, HasUuids;

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(establishment::class);
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }

    public function niveaux(): HasMany
    {
        return $this->hasMany(Niveau::class);
    }

    public function coordinateur(): HasMany
    {
        return $this->hasMany(Coordinateur::class);
    }

    public function poll(): HasMany
    {
        return $this->hasMany(Poll::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
