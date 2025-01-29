<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Planning extends Model
{
    /** @use HasFactory<\Database\Factories\PlanningFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'heure_debut',
        'heure_fin',
        'module',
        'type',
        'classe',
    ];


    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }
}
