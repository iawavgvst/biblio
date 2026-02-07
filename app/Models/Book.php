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
            'average_rating' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
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
     * Показывает оценку (рейтинг), которую поставил текущий пользователь
     *
     * @param int|string $userId
     * @return BookRating|null
     */
    public function userRating($userId): ?BookRating
    {
        return $this->ratings()
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Проверка, проголосовал ли текущий пользователь
     *
     * @param $userId
     * @return bool
     */
    public function hasUserRated($userId): bool
    {
        return $this->ratings()
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Среднее значение рейтинга (исходя из всех оценок))
     *
     * @return void
     */
    public function updateAverageRating(): void
    {
        $average = $this->ratings()->avg('rating');
        $count = $this->ratings()->count();

        $this->update([
            'average_rating' => $average ?? 0,
            'ratings_count' => $count,
        ]);
    }
}
