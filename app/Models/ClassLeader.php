<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassLeader extends Model
{
    /** @use HasFactory<\Database\Factories\ClassLeaderFactory> */
    use HasFactory, HasUuids;
}
