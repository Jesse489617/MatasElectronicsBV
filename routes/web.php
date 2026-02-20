<?php

use App\Http\Middleware\AdminWebMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Home')->name('home');

//Route::get('/', function () {
//    return Inertia::render('Home');
//})->name('home');

// Auth pages
Route::get('/login', function () {
    return Inertia::render('auth/Login');
})->name('login');

Route::get('/register', function () {
    return Inertia::render('auth/Register');
})->name('register');

// Component pages
Route::get('/components', function () {
    return Inertia::render('components/Index');
})->name('components.index');

Route::middleware(AdminWebMiddleware::class)->group(function () {
    Route::get('/components/create', function () {
        return Inertia::render('components/Create');
    })->name('components.create');

    Route::get('/components/{id}/edit', function ($id) {
        return Inertia::render('components/Edit', ['id' => $id]);
    })->name('components.edit');
});

Route::get('/components/{id}', function ($id) {
    return Inertia::render('components/Show', ['id' => $id]);
})->name('components.show');

// Assemblies pages
Route::get('/assemblies', function () {
    return Inertia::render('assemblies/Index');
})->name('assemblies.index');

Route::get('/assemblies/custom', function () {
    return Inertia::render('assemblies/Custom');
})->name('assemblies.custom');

Route::middleware(AdminWebMiddleware::class)->group(function () {
    Route::get('/assemblies/create', function () {
        return Inertia::render('assemblies/Create');
    })->name('assemblies.create');

    Route::get('/assemblies/{id}/edit', function ($id) {
        return Inertia::render('assemblies/Edit', ['id' => $id]);
    })->name('assemblies.edit');
});

Route::get('/assemblies/{id}', function ($id) {
    return Inertia::render('assemblies/Show', ['id' => $id]);
})->name('assemblies.show');

// History page
Route::get('/history', function () {
    return Inertia::render('History');
})->name('history');

// Cart page
Route::get('/cart', function () {
    return Inertia::render('Cart');
})->name('cart');



