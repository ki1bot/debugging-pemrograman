<?php

namespace Database\Seeders\Data;

use App\Models\Category;
use App\Models\Difficulty;
use App\Models\User;
use Database\Seeders\Support\ChallengeCatalogWriter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class GoProgrammingLanguageSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $admin = $this->getAdmin();
            $goCategory = $this->getOrCreateGoCategory();
            $difficulties = $this->getDifficulties();

            app(ChallengeCatalogWriter::class)->write(
                collect([
                    'go' => $goCategory,
                ]),
                $difficulties,
                $admin,
                GoChallenges::all(),
            );
        });
    }

    private function getAdmin(): User
    {
        $admin = User::query()
            ->where('role', 'admin')
            ->orderBy('id')
            ->first();

        if (! $admin instanceof User) {
            throw new RuntimeException(
                'Akun administrator belum tersedia.',
            );
        }

        return $admin;
    }

    private function getOrCreateGoCategory(): Category
    {
        $categoryBySlug = Category::query()
            ->where('slug', 'go')
            ->first();

        $categoryByName = Category::query()
            ->whereRaw(
                'LOWER(TRIM(name)) = ?',
                ['go'],
            )
            ->first();

        if (
            $categoryBySlug instanceof Category
            && $categoryByName instanceof Category
            && ! $categoryBySlug->is($categoryByName)
        ) {
            throw new RuntimeException(
                'Konflik kategori ditemukan: slug "go" dan nama "Go" digunakan oleh dua kategori yang berbeda.',
            );
        }

        $category = $categoryBySlug
            ?? $categoryByName
            ?? new Category();

        $category->fill([
            'name' => 'Go',
            'slug' => 'go',
            'description' => 'Tantangan debugging bahasa Go atau Golang yang mencakup slice, map, append, struct, JSON, scope, backing array, dan concurrency.',
            'is_active' => true,
        ]);

        $category->save();

        return $category;
    }

    private function getDifficulties(): Collection
    {
        $requiredSlugs = [
            'mudah',
            'menengah',
            'sulit',
        ];

        $difficulties = Difficulty::query()
            ->whereIn('slug', $requiredSlugs)
            ->get()
            ->keyBy('slug');

        foreach ($requiredSlugs as $difficultySlug) {
            if (! $difficulties->has($difficultySlug)) {
                throw new RuntimeException(
                    "Tingkat kesulitan {$difficultySlug} belum tersedia.",
                );
            }
        }

        return $difficulties;
    }
}
