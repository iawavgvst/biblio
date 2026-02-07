<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\User\UserUpdateRequest;
use App\Http\Requests\Book\BookStoreRequest;
use App\Http\Requests\Book\BookUpdateRequest;
use App\Models\Book;
use App\Models\BookRating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Вывод списка всех книг
     *
     * Метод GET: /books
     */
    public function index()
    {
        Gate::authorize('viewAny', Book::class);

        $user = Auth::user();
        $userId = $user ? $user->id : null;

        $books = Book::query()
            ->with(['user', 'ratings'])
            ->withCount('ratings')
            ->selectRaw('books.*')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($book) use ($userId) {
                $userRating = null;
                $hasRated = false;

                if ($userId) {
                    $rating = BookRating::where('user_id', $userId)
                        ->where('book_id', $book->id)
                        ->first();

                    if ($rating) {
                        $userRating = $rating->rating;
                        $hasRated = true;
                    }
                }

                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'description' => $book->description,
                    'genre' => $book->genre,
                    'cover' => $book->cover,
                    'is18Plus' => (bool)$book->is18Plus,
                    'average_rating' => (float)$book->average_rating,
                    'ratings_count' => $book->ratings_count,
                    'rating' => (float)$book->average_rating,
                    'rating_count' => $book->ratings_count,
                    'user_id' => $book->user_id,
                    'created_at' => $book->created_at,
                    'updated_at' => $book->updated_at,
                    'has_rated' => $hasRated,
                    'user_rating' => $userRating, // null если пользователь не голосовал
                    'user' => $book->user ? [
                        'id' => $book->user->id,
                        'name' => $book->user->name,
                        'email' => $book->user->email,
                    ] : null,
                ];
            });

        return Inertia::render('Home', [
            'books' => $books,
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ] : null,
            ],
        ]);
    }

    /**
     * Форма для создания книги
     *
     * Метод GET: /books/create
     */
    public function create()
    {
        Gate::authorize('create', Book::class);

        return Inertia::render('Book/Create');
    }

    /**
     * Создание книги
     *
     * Метод POST: /books
     */
    public function store(BookStoreRequest $request)
    {
        Gate::authorize('create', Book::class);

        $data = $request->validated();

        $book = new Book();
        $book->user_id = Auth::id();
        $book->title = $data['title'];
        $book->author = $data['author'];
        $book->description = $data['description'] ?? null;
        $book->genre = $data['genre'];
        $book->is18Plus = $data['is18plus'] ?? false;
        $book->cover = $data['cover'] ?? null;

        $book->save();

        return redirect()->route('home');
    }

    /**
     * Форма для редактирования книги
     *
     * Метод GET: /books/{book}/edit
     */
    public function edit(Book $book)
    {
        Gate::authorize('update', $book);

        return Inertia::render('Book/Edit', [
            'book' => $book,
        ]);
    }

    /**
     * Внесение изменений в определенную книгу
     *
     * Метод PUT: /books/{book}
     */
    public function update(BookUpdateRequest $request, Book $book)
    {
        Gate::authorize('update', $book);

        $data = $request->validated();

        $book->update($data);

        return redirect()->route('home', $book);
    }

    /**
     * Удаление определенной книги
     *
     * Метод DELETE: /books/{book}
     */
    public function destroy(Book $book)
    {
        Gate::authorize('delete', $book);

        $book->forceDelete();

        return redirect()->route('home');
    }

    /**
     * Рейтинг книг
     *
     * Метод POST: /books/{book}/rate
     */
    public function rate(Request $request, Book $book)
    {
        Gate::authorize('rate', $book);

        $user = Auth::user();

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $existingRating = BookRating::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->first();

        if ($existingRating) {
            $existingRating->update(['rating' => $validated['rating']]);
        } else {
            BookRating::query()->create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'rating' => $validated['rating'],
            ]);
        }

        $ratings = BookRating::query()->where('book_id', $book->id)->get();
        $averageRating = $ratings->avg('rating');
        $ratingsCount = $ratings->count();

        $book->update([
            'average_rating' => $averageRating ?? 0,
            'ratings_count' => $ratingsCount,
        ]);

        return redirect()->route('home');
    }

    /**
     * Профиль и страница книг текущего пользователя
     *
     * Метод GET: /profile
     */
    public function myBooks()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $books = Book::query()
            ->with(['user', 'ratings'])
            ->withCount('ratings')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($book) use ($user) {
                $userRating = null;
                $hasRated = false;

                $rating = BookRating::where('user_id', $user->id)
                    ->where('book_id', $book->id)
                    ->first();

                if ($rating) {
                    $userRating = $rating->rating;
                    $hasRated = true;
                }

                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'description' => $book->description,
                    'genre' => $book->genre,
                    'cover' => $book->cover,
                    'is18Plus' => (bool)$book->is18Plus,
                    'average_rating' => (float)$book->average_rating,
                    'ratings_count' => $book->ratings_count,
                    'rating' => (float)$book->average_rating,
                    'rating_count' => $book->ratings_count,
                    'user_id' => $book->user_id,
                    'created_at' => $book->created_at,
                    'updated_at' => $book->updated_at,
                    'has_rated' => $hasRated,
                    'user_rating' => $userRating,
                    'user' => $book->user ? [
                        'id' => $book->user->id,
                        'name' => $book->user->name,
                        'email' => $book->user->email,
                    ] : null,
                ];
            });

        return Inertia::render('Book/Profile', [
            'books' => $books,
            'auth' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
            ],
        ]);
    }

    /**
     * Обновление данных профиля
     *
     * Метод PUT: /profile
     */
    public function profileUpdate(UserUpdateRequest $request, User $user)
    {
        $user = auth()->user();

        Gate::authorize('update', $user);

        $data = $request->validated();

        $emailChanged = $request->filled('email') && $data['email'] !== $user->email;

        $user->update($data);

        if ($emailChanged) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('status', 'Email updated. Please login with your new email.');
        }

        return redirect()->route('profile.mybooks');
    }
}
