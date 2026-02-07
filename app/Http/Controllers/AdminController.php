<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\User\UserUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Показать список пользователей
     *
     * Метод GET: /admin/users
     */
    public function index()
    {
        Gate::authorize('ViewAny', User::class);

        $users = User::query()
            ->select(['id', 'name', 'email', 'role'])
            ->withCount('books')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'books_count' => $user->books->count(),
                ];
            });

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'auth' => [
                'user' => auth()->user() ? [
                    'id' => auth()->user()->id,
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'role' => auth()->user()->role,
                ] : null,
            ]
        ]);
    }

    /**
     * Показать список пользователей
     *
     * Метод GET: /admin/users/{user}/edit
     */
    public function edit(User $user)
    {
        Gate::authorize('update', $user);

        return Inertia::render('Admin/Users/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'auth' => [
                'user' => auth()->user() ? [
                    'id' => auth()->user()->id,
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'role' => auth()->user()->role,
                ] : null,
            ]
        ]);
    }

    /**
     * Показать список пользователей
     *
     * Метод PUT: /admin/users/{user}
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        Gate::authorize('update', $user);

        $data = $request->validated();

        $authUser = auth()->user();

        if ($user->id === $authUser->id && isset($data['role']) && $data['role'] !== $authUser->role) {
            return back()->withErrors(['role' => 'Нельзя изменить свою собственную роль']);
        }

        $user->update($data);

        return redirect()->route('admin.user.index');
    }

    /**
     * Показать список пользователей
     *
     * Метод DELETE: /admin/users/{user}
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        if ($user->id === auth()->id()) {
            return back()->withErrors('error', 'Нельзя удалить свой собственный аккаунт');
        }

        $user->delete();

        return redirect()->route('admin.user.index');
    }

    /**
     * Панель управления (Dashboard) - главная страница админки
     *
     * Метод GET: /admin/dashboard
     */
    public function dashboard()
    {
        Gate::authorize('ViewAny', User::class);

        $totalUsers = User::count();

        $adminUsers = User::where('role', 'admin')->count();

        $regularUsers = User::where('role', 'user')->count();

        $recentUsers = User::oldest()->take(10)->get();

        return Inertia::render('Admin/Dashboard', [
            'isAdmin' => true,
            'stats' => [
                'totalUsers' => $totalUsers,
                'adminUsers' => $adminUsers,
                'regularUsers' => $regularUsers,
            ],
            'recentUsers' => $recentUsers,
            'users' => User::latest()->paginate(10),
        ]);
    }
}
