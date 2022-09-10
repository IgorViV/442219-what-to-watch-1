<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Film extends Model
{
    use HasFactory;

    /**
     * Film status
     */
    public const FILM_PENDING = 0;
    public const FILM_MODERATE = 1;
    public const FILM_READY = 2;

    /**
     * Default film status.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::FILM_PENDING,
    ];

    /**
     * Attributes for which mass assignment of values is allowed.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'poster_image',
        'preview_image',
        'background_image',
        'background_color',
        'video_link',
        'description',
        'run_time',
        'released',
        'imdb_id',
        'status',
    ];

    public function promo(): HasOne
    {
        return $this->hasOne(Promo::class);
    }

    public function favorite(): HasOne
    {
        return $this->hasOne(Favorite::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_films');
    }

    public function directors(): BelongsToMany
    {
        return $this->belongsToMany(Director::class, 'film_directors');
    }

    public function stars(): BelongsToMany
    {
        return $this->belongsToMany(Star::class, 'film_stars');
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'film_genres');
    }

    /**
     * Calculates the rating of the film.
     *
     * @return float
     */
    public function getTotalRating(): float
    {
        $comments = $this->comments();
        return (float) round($comments->sum('rating') / $comments->count(), 1);
    }

    /**
     * Film status On moderation.
     *
     * @return bool
     */
    public function isModerate()
    {
        return $this->status === self::FILM_MODERATE;
    }

    /**
     * Film status Is ready.
     *
     * @return bool
     */
    public function isReady()
    {
        return $this->status === self::FILM_READY;
    }
}
