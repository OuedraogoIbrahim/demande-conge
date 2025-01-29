<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $fillable = [
        'donnees',
        'user_id',
        'establishment_id',
    ];

    /** @use HasFactory<\Database\Factories\NoteFactory> */
    use HasFactory, HasUuids;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getNotes()
    {
        return json_decode($this->data, true);  // Retourne les données sous forme de tableau
    }
}
