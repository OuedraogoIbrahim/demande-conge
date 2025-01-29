<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendingStudent extends Model
{
    use HasUuids, HasFactory;
    //

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }
}
