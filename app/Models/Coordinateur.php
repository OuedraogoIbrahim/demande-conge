<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coordinateur extends Model
{
    /** @use HasFactory<\Database\Factories\CoordinateurFactory> */
    use HasFactory, HasUuids;

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
