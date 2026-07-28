<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Challenge;
use App\Models\Difficulty;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdditionalProgrammingLanguageSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_additional_programming_language_categories_are_created(): void
    {
        $expectedCategories = [
            'c' => 'C',
            'cpp' => 'C++',
            'java' => 'Java',
            'python' => 'Python',
        ];

        foreach ($expectedCategories as $slug => $name) {
            $this->assertDatabaseHas('categories', [
                'slug' => $slug,
                'name' => $name,
                'is_active' => true,
            ]);
        }

        $this->assertSame(
            7,
            Category::query()->count(),
        );
    }

    public function test_each_additional_language_has_correct_difficulty_distribution(): void
    {
        $expectedDistribution = [
            'mudah' => 3,
            'menengah' => 3,
            'sulit' => 2,
        ];

        foreach (['c', 'cpp', 'java', 'python'] as $categorySlug) {
            $category = Category::query()
                ->where('slug', $categorySlug)
                ->firstOrFail();

            foreach ($expectedDistribution as $difficultySlug => $expectedCount) {
                $difficulty = Difficulty::query()
                    ->where('slug', $difficultySlug)
                    ->firstOrFail();

                $actualCount = Challenge::query()
                    ->where('category_id', $category->id)
                    ->where('difficulty_id', $difficulty->id)
                    ->where('status', 'published')
                    ->count();

                $this->assertSame(
                    $expectedCount,
                    $actualCount,
                    "{$categorySlug} {$difficultySlug}",
                );
            }
        }
    }

    public function test_each_additional_language_has_eight_published_challenges(): void
    {
        foreach (['c', 'cpp', 'java', 'python'] as $categorySlug) {
            $category = Category::query()
                ->where('slug', $categorySlug)
                ->firstOrFail();

            $this->assertSame(
                8,
                Challenge::query()
                    ->where('category_id', $category->id)
                    ->where('status', 'published')
                    ->count(),
                $categorySlug,
            );
        }
    }

    public function test_every_additional_language_challenge_has_solution_and_hint(): void
    {
        $categoryIds = Category::query()
            ->whereIn('slug', ['c', 'cpp', 'java', 'python'])
            ->pluck('id');

        Challenge::query()
            ->with([
                'hints',
                'solutions',
            ])
            ->whereIn('category_id', $categoryIds)
            ->each(function (Challenge $challenge): void {
                $this->assertTrue(
                    $challenge->hints->isNotEmpty(),
                    $challenge->slug,
                );

                $this->assertTrue(
                    $challenge->solutions->isNotEmpty(),
                    $challenge->slug,
                );

                $this->assertSame(
                    1,
                    $challenge->solutions
                        ->where('solution_type', 'primary')
                        ->count(),
                    $challenge->slug,
                );
            });
    }

    public function test_database_seeder_creates_fifty_six_challenges(): void
    {
        $this->assertSame(
            56,
            Challenge::query()->count(),
        );
    }
}
