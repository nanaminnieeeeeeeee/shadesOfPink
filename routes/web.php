<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShadeController;  
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard Route
Route::get('/dashboard', [ShadeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Shades Routes
    Route::get('/shades-of-pink', [ShadeController::class, 'shadesOfPink'])
        ->middleware(['verified'])
        ->name('shades.index');

    // Shades CRUD routes
    Route::post('/shades', [ShadeController::class, 'store'])->name('shades.store');
    Route::put('/shades/{shade}', [ShadeController::class, 'update'])->name('shades.update');
    Route::delete('/shades/{shade}', [ShadeController::class, 'destroy'])->name('shades.destroy');
});

require __DIR__.'/auth.php';