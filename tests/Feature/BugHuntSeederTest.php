<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Challenge;
use App\Models\Difficulty;
use App\Models\User;
use Database\Seeders\BugHuntSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BugHuntSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(
            BugHuntSeeder::class,
        );
    }

    public function test_demo_accounts_are_created(): void
    {
        $admin = User::query()
            ->where(
                'email',
                'admin@bughunt.test',
            )
            ->firstOrFail();

        $user = User::query()
            ->where(
                'email',
                'user@bughunt.test',
            )
            ->firstOrFail();

        $this->assertSame(
            'admin',
            $admin->role,
        );

        $this->assertSame(
            'user',
            $user->role,
        );

        $this->assertTrue(
            Hash::check(
                'password',
                $admin->password,
            ),
        );

        $this->assertTrue(
            Hash::check(
                'password',
                $user->password,
            ),
        );
    }

    public function test_initial_content_contains_three_categories_and_difficulties(): void
    {
        $this->assertSame(
            3,
            Category::query()->count(),
        );

        $this->assertSame(
            3,
            Difficulty::query()->count(),
        );

        $this->assertDatabaseHas(
            'difficulties',
            [
                'slug' => 'mudah',
                'base_points' => 50,
            ],
        );

        $this->assertDatabaseHas(
            'difficulties',
            [
                'slug' => 'menengah',
                'base_points' => 100,
            ],
        );

        $this->assertDatabaseHas(
            'difficulties',
            [
                'slug' => 'sulit',
                'base_points' => 150,
            ],
        );
    }

    public function test_initial_content_contains_twenty_four_challenges(): void
    {
        $this->assertSame(
            24,
            Challenge::query()->count(),
        );
    }

    public function test_each_category_has_correct_difficulty_distribution(): void
    {
        $expected = [
            'mudah' => 3,
            'menengah' => 3,
            'sulit' => 2,
        ];

        foreach (
            ['javascript', 'php', 'sql'] as $categorySlug
        ) {
            $category = Category::query()
                ->where(
                    'slug',
                    $categorySlug,
                )
                ->firstOrFail();

            foreach (
                $expected as $difficultySlug => $count
            ) {
                $difficulty =
                    Difficulty::query()
                        ->where(
                            'slug',
                            $difficultySlug,
                        )
                        ->firstOrFail();

                $actual =
                    Challenge::query()
                        ->where(
                            'category_id',
                            $category->id,
                        )
                        ->where(
                            'difficulty_id',
                            $difficulty->id,
                        )
                        ->count();

                $this->assertSame(
                    $count,
                    $actual,
                    "{$categorySlug} {$difficultySlug}",
                );
            }
        }
    }

    public function test_every_challenge_has_solution_and_hint(): void
    {
        Challenge::query()
            ->with([
                'solutions',
                'hints',
            ])
            ->each(
                function (
                    Challenge $challenge
                ): void {
                    $this->assertTrue(
                        $challenge
                            ->solutions
                            ->isNotEmpty(),
                    );

                    $this->assertSame(
                        1,
                        $challenge
                            ->solutions
                            ->where(
                                'solution_type',
                                'primary',
                            )
                            ->count(),
                    );

                    $this->assertTrue(
                        $challenge
                            ->hints
                            ->isNotEmpty(),
                    );
                },
            );
    }
}
