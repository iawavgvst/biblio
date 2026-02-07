<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookPolicy
{
    /**
     * Определить, может ли пользователь просматривать все книги
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Определить, может ли пользователь просматривать конкретную книгу
     */
    public function view(User $user, Book $book): bool
    {
        return true;
    }

    /**
     * Определить, может ли пользователь создавать книги
     */
    public function create(User $user): bool
    {
        if(auth()->check()) {
            return true;
        }

        return false;
    }

    /**
     * Определить, может ли пользователь обновлять книгу
     */
    public function update(User $user, Book $book): bool
    {
        return $user->isAdmin() || $user->id === $book->user_id;
    }

    /**
     * Определить, может ли пользователь удалять книгу
     */
    public function delete(User $user, Book $book): bool
    {
        return $user->isAdmin() || $user->id === $book->user_id;
    }

    /**
     * Определить, может ли пользователь восстанавливать удаленные книги
     */
    public function restore(User $user, Book $book): bool
    {
        return $user->isAdmin();
    }

    /**
     * Определить, может ли пользователь окончательно удалить книгу
     */
    public function forceDelete(User $user, Book $book): bool
    {
        return $user->isAdmin();
    }

    /**
     * Определить, может ли пользователь ставить рейтинг книге
     */
    public function rate(User $user, Book $book): bool
    {
        if (!auth()->check()) {
            return false;
        }

        if ($user->hasRated($book)) {
            return false;
        }

        return true;
    }
}
