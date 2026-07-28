<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Difficulty;
use App\Models\User;
use Database\Seeders\Data\JavascriptChallenges;
use Database\Seeders\Data\PhpChallenges;
use Database\Seeders\Data\SqlChallenges;
use Database\Seeders\Support\ChallengeCatalogWriter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BugHuntSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $admin = User::query()
                ->updateOrCreate(
                    [
                        'email' => 'admin@bughunt.test',
                    ],
                    [
                        'name' => 'Administrator BugHunt',
                        'password' => Hash::make(
                            'password',
                        ),
                        'role' => 'admin',
                        'total_points' => 0,
                        'email_verified_at' => now(),
                    ],
                );

            User::query()->updateOrCreate(
                [
                    'email' => 'user@bughunt.test',
                ],
                [
                    'name' => 'Pengguna Demo',
                    'password' => Hash::make(
                        'password',
                    ),
                    'role' => 'user',
                    'total_points' => 0,
                    'email_verified_at' => now(),
                ],
            );

            $categories = collect([
                [
                    'name' => 'JavaScript',
                    'slug' => 'javascript',
                    'description' => 'Tantangan debugging sintaks, array, asynchronous programming, scope, dan referensi objek JavaScript.',
                ],
                [
                    'name' => 'PHP',
                    'slug' => 'php',
                    'description' => 'Tantangan debugging PHP dasar, form, session, function, database, dan keamanan input.',
                ],
                [
                    'name' => 'SQL',
                    'slug' => 'sql',
                    'description' => 'Tantangan debugging query SQL, JOIN, GROUP BY, subquery, agregasi, dan window function.',
                ],
            ])->mapWithKeys(
                function (
                    array $category
                ): array {
                    $model =
                        Category::query()
                            ->updateOrCreate(
                                [
                                    'slug' => $category[
                                            'slug'
                                        ],
                                ],
                                [
                                    ...$category,
                                    'is_active' => true,
                                ],
                            );

                    return [
                        $category['slug'] => $model,
                    ];
                },
            );

            $difficulties = collect([
                [
                    'name' => 'Mudah',
                    'slug' => 'mudah',
                    'base_points' => 50,
                ],
                [
                    'name' => 'Menengah',
                    'slug' => 'menengah',
                    'base_points' => 100,
                ],
                [
                    'name' => 'Sulit',
                    'slug' => 'sulit',
                    'base_points' => 150,
                ],
            ])->mapWithKeys(
                function (
                    array $difficulty
                ): array {
                    $model =
                        Difficulty::query()
                            ->updateOrCreate(
                                [
                                    'slug' => $difficulty[
                                            'slug'
                                        ],
                                ],
                                [
                                    ...$difficulty,
                                    'is_active' => true,
                                ],
                            );

                    return [
                        $difficulty['slug'] => $model,
                    ];
                },
            );

            app(
                ChallengeCatalogWriter::class,
            )->write(
                $categories,
                $difficulties,
                $admin,
                [
                    ...JavascriptChallenges::all(),
                    ...PhpChallenges::all(),
                    ...SqlChallenges::all(),
                ],
            );
        });
    }
}
