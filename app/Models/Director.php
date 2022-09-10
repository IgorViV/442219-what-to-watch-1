<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Director extends Model
{
    use HasFactory;

    /**
     * Attributes for which mass assignment of values is allowed.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];

    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class, 'film_directors');
    }
}
