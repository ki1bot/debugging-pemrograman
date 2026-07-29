<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AdminDifficultyRequest;
use App\Models\Difficulty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AdminDifficultyController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Difficulties/Daftar', [
            'difficulties' => Difficulty::query()
                ->withCount('challenges')
                ->orderBy('base_points')
                ->get(),
        ]);
    }

    public function store(
        AdminDifficultyRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        Difficulty::query()->create([
            'name' => $validated['name'],
            'slug' => $validated['slug']
                ?: Str::slug($validated['name']),
            'base_points' => $validated['base_points'],
            'is_active' => $validated['is_active'],
        ]);

        return back()->with(
            'success',
            'Tingkat kesulitan berhasil ditambahkan.',
        );
    }

    public function update(
        AdminDifficultyRequest $request,
        Difficulty $difficulty
    ): RedirectResponse {
        $validated = $request->validated();

        $difficulty->update([
            'name' => $validated['name'],
            'slug' => $validated['slug']
                ?: Str::slug($validated['name']),
            'base_points' => $validated['base_points'],
            'is_active' => $validated['is_active'],
        ]);

        return back()->with(
            'success',
            'Tingkat kesulitan berhasil diperbarui.',
        );
    }

    public function destroy(
        Difficulty $difficulty
    ): RedirectResponse {
        if ($difficulty->challenges()->exists()) {
            return back()->with(
                'error',
                'Tingkat kesulitan tidak dapat dihapus karena masih digunakan oleh tantangan.',
            );
        }

        $difficulty->delete();

        return back()->with(
            'success',
            'Tingkat kesulitan berhasil dihapus.',
        );
    }
}
