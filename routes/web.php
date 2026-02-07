<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// для неавторизованного пользователя
Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.action');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.action');
});

// для админа
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::prefix('users')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.user.index');
        Route::get('/{user}/edit', [AdminController::class, 'edit'])->name('admin.user.edit');
        Route::put('/{user}', [AdminController::class, 'update'])->name('admin.user.update');
        Route::delete('/{user}', [AdminController::class, 'destroy'])->name('admin.user.destroy');
    });
});

// для авторизованных пользователей
Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [HomeController::class, 'myBooks'])->name('profile.mybooks');
    Route::put('/profile', [HomeController::class, 'profileUpdate'])->name('profile.update');

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/books/create', [HomeController::class, 'create'])->name('books.create');
    Route::post('/books', [HomeController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [HomeController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [HomeController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [HomeController::class, 'destroy'])->name('books.destroy');

    Route::post('/books/{book}/rate', [HomeController::class, 'rate'])->name('books.rate');
});

// не находится в общей навигации
Route::get('/test', [TestController::class, 'index'])->name('test');

Route::get('/about', function () {
    return Inertia::render('AboutUs');
})->name('about');

Route::get('/scroll-page', function () {
    return Inertia::render('ScrollPage');
})->name('scroll-page');
