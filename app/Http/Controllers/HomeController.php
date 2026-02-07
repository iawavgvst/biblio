<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\BookStoreRequest;
use App\Http\Requests\Book\BookUpdateRequest;
use App\Models\Book;
use App\Models\BookRating;
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

        $userId = Auth::id();

        $books = Book::with(['user:id,name'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->latest()
            ->get()
            ->map(function ($book) use ($userId) {
                $userRating = null;
                if ($userId) {
                    $rating = $book->ratings()
                        ->where('user_id', $userId)
                        ->first();
                    $userRating = $rating ? $rating->rating : null;
                }

                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'description' => $book->description,
                    'genre' => $book->genre,
                    'cover' => $book->cover,
                    'is18Plus' => $book->is18Plus,
                    'rating' => (float) $book->ratings_avg_rating ?? 0,
                    'rating_count' => $book->ratings_count ?? 0,
                    'user_rating' => $userRating, // Оценка текущего пользователя
                    'has_rated' => $userRating !== null,
                    'user' => $book->user,
                    'user_id' => $book->user_id,
                    'created_at' => $book->created_at,
                ];
            });

        return Inertia::render('Home', [
            'books' => $books,
            'canCreate' => Auth::check(),
            'auth' => [
                'user' => Auth::user() ? [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'role' => Auth::user()->role,
                ] : null
            ]
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

        $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);

        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Please login to rate books');
        }

        if ($book->user_id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot rate your own book');
        }

        BookRating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'book_id' => $book->id,
            ],
            ['rating' => $request->rating]
        );

        $book->updateRatingStats();

        return redirect()->back()->with('success', 'Rating saved!');
    }

    /**
     * Профиль и страница книг текущего пользователя
     *
     * Метод GET: /profile
     */
    public function myBooks()
    {
        $books = Book::query()
            ->where('user_id', Auth::id())
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'description' => $book->description,
                    'genre' => $book->genre,
                    'cover' => $book->cover,
                    'is18Plus' => $book->is18Plus,
                    'rating' => $book->average_rating ?? 0,
                    'rating_count' => $book->ratings_count,
                    'created_at' => $book->created_at->format('d.m.Y'),
                    'updated_at' => $book->updated_at->format('d.m.Y'),
                ];
            });

        return Inertia::render('Book/Profile', [
            'books' => $books,
            'user' => Auth::user(),
        ]);
    }
}
