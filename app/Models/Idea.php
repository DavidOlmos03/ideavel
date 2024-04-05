<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Idea extends Model
{
    use HasFactory;


    /**Para una relación de "una idea pertenece(belongsTo) a un usuario" */
    public function user(): BelongsTo
    {
        return $this->belongsTo( User::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany( User::class);
    }
}
