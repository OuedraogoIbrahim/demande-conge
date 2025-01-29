<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Niveau extends Model
{
    /** @use HasFactory<\Database\Factories\NiveauFactory> */
    use HasFactory, HasUuids;

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class);
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
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
