<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;

Route::get('/', [PostController::class, 'index'])->name('root');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('posts', PostController::class)
        ->except(['show', 'index']);
});

Route::resource('posts', PostController::class)
    ->only(['show', 'index']);

Route::get('/search', [SearchController::class, 'search'])->name('search');

require __DIR__.'/auth.php';
