<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Определить, может ли пользователь просматривать список пользователей
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Определить, может ли пользователь просматривать другого пользователя
     */
    public function view(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->id === $model->id;
    }

    /**
     * Определить, может ли пользователь создавать другого пользователя
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Определить, может ли пользователь обновлять другого пользователя
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->id === $model->id;
    }

    /**
     * Определить, может ли пользователь удалять другого пользователя
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->id === $model->id;
    }

    /**
     * Определить, может ли пользователь восстанавливать удаленного пользователя
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Определить, может ли пользователь окончательно удалить другого пользователя
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }
}
