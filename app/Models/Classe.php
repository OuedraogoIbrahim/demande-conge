<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classe extends Model
{
    /** @use HasFactory<\Database\Factories\ClassesFactory> */
    use HasFactory, HasUuids;

    public function plannings(): HasMany
    {
        return $this->hasMany(Planning::class);
    }
}
