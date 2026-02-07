<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'author',
        'description',
        'genre',
        'cover',
        'is18Plus',
        'average_rating',
        'ratings_count',
        'user_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is18Plus' => 'boolean',
            'average_rating' => 'float',
            'ratings_count' => 'integer',
        ];
    }

    /**
     * Получить пользователя-владельца книги (1 владелец)
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить все рейтинги книги (имеет много рейтингов)
     *
     * @return HasMany
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(BookRating::class);
    }

    /**
     * Получить средний рейтинг книги
     *
     * @return float
     */
    public function getCalculatedAverageRatingAttribute(): float
    {
        // Если уже загружено через withAvg
        if (isset($this->attributes['ratings_avg_rating'])) {
            return (float) $this->attributes['ratings_avg_rating'];
        }

        // Иначе вычисляем на лету
        return $this->ratings()->avg('rating') ?? 0;
    }

    /**
     * Получить количество оценок (рейтинг)
     *
     * @return int
     */
    public function getCalculatedRatingsCountAttribute(): int
    {
        if (isset($this->attributes['ratings_count'])) {
            return (int) $this->attributes['ratings_count'];
        }

        return $this->ratings()->count();
    }

    /**
     * Обновить статистику рейтингов
     *
     * @return void
     */
    public function updateRatingStats(): void
    {
        $stats = $this->ratings()
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as count')
            ->first();

        $this->update([
            'average_rating' => $stats->avg_rating ?? 0,
            'ratings_count' => $stats->count ?? 0
        ]);
    }
}
