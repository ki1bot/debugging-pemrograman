<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BugHuntController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BugHuntController::class, 'home'])->name('home');
Route::get('/about', [BugHuntController::class, 'about'])->name('about');
Route::get('/challenges', [BugHuntController::class, 'challenges'])->name('challenges.index');
Route::get('/leaderboard', [BugHuntController::class, 'leaderboard'])->name('leaderboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [BugHuntController::class, 'dashboard'])->name('dashboard');
    Route::get('/challenges/{challenge:slug}', [BugHuntController::class, 'showChallenge'])->name('challenges.show');
    Route::post('/challenges/{challenge:slug}/hints/{hint}', [BugHuntController::class, 'unlockHint'])
        ->middleware('throttle:20,1')
        ->name('challenges.hints.store');
    Route::post('/challenges/{challenge:slug}/submit', [BugHuntController::class, 'submitChallenge'])
        ->middleware('throttle:10,1')
        ->name('challenges.submit');
    Route::get('/submissions/{submission}', [BugHuntController::class, 'submission'])->name('submissions.show');
    Route::get('/history', [BugHuntController::class, 'history'])->name('history.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

        Route::get('/difficulties', [AdminController::class, 'difficulties'])->name('difficulties.index');
        Route::post('/difficulties', [AdminController::class, 'storeDifficulty'])->name('difficulties.store');
        Route::put('/difficulties/{difficulty}', [AdminController::class, 'updateDifficulty'])->name('difficulties.update');
        Route::delete('/difficulties/{difficulty}', [AdminController::class, 'destroyDifficulty'])->name('difficulties.destroy');

        Route::get('/challenges', [AdminController::class, 'challenges'])->name('challenges.index');
        Route::get('/challenges/create', [AdminController::class, 'createChallenge'])->name('challenges.create');
        Route::post('/challenges', [AdminController::class, 'storeChallenge'])->name('challenges.store');
        Route::get('/challenges/{challenge:slug}/edit', [AdminController::class, 'editChallenge'])->name('challenges.edit');
        Route::put('/challenges/{challenge:slug}', [AdminController::class, 'updateChallenge'])->name('challenges.update');
        Route::delete('/challenges/{challenge:slug}', [AdminController::class, 'destroyChallenge'])->name('challenges.destroy');

        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');

        Route::get('/submissions', [AdminController::class, 'submissions'])->name('submissions.index');
        Route::get('/submissions/{submission}', [AdminController::class, 'showSubmission'])->name('submissions.show');
    });

require __DIR__.'/auth.php';
