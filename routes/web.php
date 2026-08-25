<?php

use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminChallengeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDifficultyController;
use App\Http\Controllers\AdminStatisticsController;
use App\Http\Controllers\AdminSubmissionController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\BugHuntController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\CodeExecutionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;

Route::get(
    '/',
    [BugHuntController::class, 'home'],
)->name('home');

Route::get(
    '/about',
    [BugHuntController::class, 'about'],
)->name('about');

Route::get(
    '/challenges',
    [ChallengeController::class, 'index'],
)->name('challenges.index');

Route::get(
    '/leaderboard',
    [BugHuntController::class, 'leaderboard'],
)->name('leaderboard');

Route::middleware('auth')->group(
    function (): void {
        Route::get(
            '/dashboard',
            UserDashboardController::class,
        )->name('dashboard');

        Route::post(
            '/code-executions',
            [CodeExecutionController::class, 'store'],
        )
            ->middleware('throttle:10,1')
            ->name('code-executions.store');

        Route::get(
            '/code-executions/{token}',
            [CodeExecutionController::class, 'show'],
        )
            ->whereUuid('token')
            ->middleware('throttle:90,1')
            ->name('code-executions.show');

        Route::get(
            '/challenges/{challenge:slug}',
            [ChallengeController::class, 'show'],
        )->name('challenges.show');

        Route::post(
            '/challenges/{challenge:slug}/hints/{hint}',
            [ChallengeController::class, 'unlockHint'],
        )
            ->middleware('throttle:20,1')
            ->name('challenges.hints.store');

        Route::post(
            '/challenges/{challenge:slug}/submit',
            [ChallengeController::class, 'submit'],
        )
            ->middleware('throttle:10,1')
            ->name('challenges.submit');

        Route::get(
            '/submissions/{submission}',
            [SubmissionController::class, 'show'],
        )->name('submissions.show');

        Route::get(
            '/history',
            [SubmissionController::class, 'history'],
        )->name('history.index');

        Route::get(
            '/profile',
            [ProfileController::class, 'edit'],
        )->name('profile.edit');

        Route::patch(
            '/profile',
            [ProfileController::class, 'update'],
        )->name('profile.update');

        Route::delete(
            '/profile',
            [ProfileController::class, 'destroy'],
        )->name('profile.destroy');
    },
);

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function (): void {
        Route::get(
            '/',
            [AdminController::class, 'dashboard'],
        )->name('dashboard');

        Route::get(
            '/statistics',
            AdminStatisticsController::class,
        )->name('statistics.index');

        Route::get(
            '/categories',
            [AdminCategoryController::class, 'index'],
        )->name('categories.index');

        Route::post(
            '/categories',
            [AdminCategoryController::class, 'store'],
        )->name('categories.store');

        Route::put(
            '/categories/{category}',
            [AdminCategoryController::class, 'update'],
        )->name('categories.update');

        Route::delete(
            '/categories/{category}',
            [AdminCategoryController::class, 'destroy'],
        )->name('categories.destroy');

        Route::get(
            '/difficulties',
            [AdminDifficultyController::class, 'index'],
        )->name('difficulties.index');

        Route::post(
            '/difficulties',
            [AdminDifficultyController::class, 'store'],
        )->name('difficulties.store');

        Route::put(
            '/difficulties/{difficulty}',
            [AdminDifficultyController::class, 'update'],
        )->name('difficulties.update');

        Route::delete(
            '/difficulties/{difficulty}',
            [AdminDifficultyController::class, 'destroy'],
        )->name('difficulties.destroy');

        Route::get(
            '/challenges',
            [AdminChallengeController::class, 'index'],
        )->name('challenges.index');

        Route::get(
            '/challenges/create',
            [AdminChallengeController::class, 'create'],
        )->name('challenges.create');

        Route::post(
            '/challenges',
            [AdminChallengeController::class, 'store'],
        )->name('challenges.store');

        Route::get(
            '/challenges/{challenge:slug}/edit',
            [AdminChallengeController::class, 'edit'],
        )->name('challenges.edit');

        Route::put(
            '/challenges/{challenge:slug}',
            [AdminChallengeController::class, 'update'],
        )->name('challenges.update');

        Route::delete(
            '/challenges/{challenge:slug}',
            [AdminChallengeController::class, 'destroy'],
        )->name('challenges.destroy');

        Route::get(
            '/users',
            [AdminUserController::class, 'index'],
        )->name('users.index');

        Route::put(
            '/users/{user}',
            [AdminUserController::class, 'update'],
        )->name('users.update');

        Route::get(
            '/submissions',
            [AdminSubmissionController::class, 'index'],
        )->name('submissions.index');

        Route::get(
            '/submissions/{submission}',
            [AdminSubmissionController::class, 'show'],
        )->name('submissions.show');
    });

require __DIR__.'/auth.php';
