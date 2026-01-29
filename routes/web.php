<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');

// не находится в общей навигации
Route::get('/test', [TestController::class, 'index'])->name('test');

Route::get('/about', function () {
    return Inertia::render('AboutUs');
})->name('about');

Route::get('/profile', function () {
    return Inertia::render('Profile');
})->name('profile');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/scroll-page', function () {
    return Inertia::render('ScrollPage');
})->name('scroll-page');
