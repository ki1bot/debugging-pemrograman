<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AdminCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class AdminCategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Categories/Daftar', [
            'categories' => Category::query()
                ->withCount('challenges')
                ->orderBy('name', 'asc')
                ->get(),
        ]);
    }

    public function store(
        AdminCategoryRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        Category::query()->create([
            'name' => $validated['name'],
            'slug' => $validated['slug']
                ?: Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'],
        ]);

        return back()->with(
            'success',
            'Kategori berhasil ditambahkan.',
        );
    }

    public function update(
        AdminCategoryRequest $request,
        Category $category
    ): RedirectResponse {
        $validated = $request->validated();

        $category->update([
            'name' => $validated['name'],
            'slug' => $validated['slug']
                ?: Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'],
        ]);

        return back()->with(
            'success',
            'Kategori berhasil diperbarui.',
        );
    }

    public function destroy(
        Category $category
    ): RedirectResponse {
        if ($category->challenges()->exists()) {
            return back()->with(
                'error',
                'Kategori tidak dapat dihapus karena masih digunakan oleh tantangan.',
            );
        }

        Category::destroy((int) $category->getKey());

        return back()->with(
            'success',
            'Kategori berhasil dihapus.',
        );
    }
}
