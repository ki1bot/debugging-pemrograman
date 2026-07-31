<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Challenge;
use App\Models\Difficulty;
use App\Models\User;
use App\Models\UserChallengeProgress;
use Database\Seeders\Data\JavascriptChallenges;
use Database\Seeders\Data\PhpChallenges;
use Database\Seeders\Data\SqlChallenges;
use Database\Seeders\Support\ChallengeCatalogWriter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class BugHuntSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $isTesting = app()->environment('testing');
            $isLocal = app()->environment('local');

            if ($isTesting) {
                $adminEmail = 'admin@bughunt.test';
                $adminPassword = 'password';
            } else {
                $adminEmail = trim(
                    (string) env(
                        'BUGHUNT_ADMIN_EMAIL',
                        $isLocal ? 'admin@bughunt.test' : '',
                    ),
                );

                $adminPassword = (string) env(
                    'BUGHUNT_ADMIN_PASSWORD',
                    $isLocal ? 'password' : '',
                );
            }

            if (
                $adminEmail === ''
                || filter_var(
                    $adminEmail,
                    FILTER_VALIDATE_EMAIL,
                ) === false
            ) {
                throw new RuntimeException(
                    'BUGHUNT_ADMIN_EMAIL wajib diisi dengan alamat email yang valid.',
                );
            }

            if (
                ! $isTesting
                && ! $isLocal
                && strlen($adminPassword) < 16
            ) {
                throw new RuntimeException(
                    'BUGHUNT_ADMIN_PASSWORD wajib diisi minimal 16 karakter.',
                );
            }

            $admin = User::query()->updateOrCreate(
                [
                    'email' => $adminEmail,
                ],
                [
                    'name' => 'Administrator Debugging Pemrograman',
                    'password' => Hash::make(
                        $adminPassword,
                    ),
                    'role' => 'admin',
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
                function (array $category): array {
                    $model = Category::query()->updateOrCreate(
                        [
                            'slug' => $category['slug'],
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
                function (array $difficulty): array {
                    $model = Difficulty::query()->updateOrCreate(
                        [
                            'slug' => $difficulty['slug'],
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

            if ($isTesting || $isLocal) {
                $demoUser = $this->seedDemoUser(
                    $isTesting,
                );

                if ($isLocal) {
                    $this->seedDemoProgress(
                        $demoUser,
                    );
                }
            }
        });
    }

    private function seedDemoUser(
        bool $isTesting
    ): User {
        $legacyEmail = 'user@bughunt.test';
        $demoEmail = 'rifqiuser@bughunt.test';

        $demoUser = User::query()
            ->where(
                'email',
                $demoEmail,
            )
            ->first();

        $legacyUser = User::query()
            ->where(
                'email',
                $legacyEmail,
            )
            ->first();

        if (
            $demoUser !== null
            && $legacyUser !== null
            && $demoUser->id !== $legacyUser->id
        ) {
            throw new RuntimeException(
                'Ditemukan dua akun demo: user@bughunt.test dan rifqiuser@bughunt.test. Gabungkan atau hapus salah satunya sebelum menjalankan seeder kembali.',
            );
        }

        $user = $demoUser
            ?? $legacyUser
            ?? new User();

        $user->forceFill([
            'name' => 'Rifqi',
            'email' => $demoEmail,
            'password' => Hash::make(
                'password',
            ),
            'role' => 'user',
            'total_points' => $isTesting ? 0 : 500,
            'email_verified_at' => now(),
        ]);

        $user->save();

        return $user;
    }

    private function seedDemoProgress(
        User $user
    ): void {
        $easyDifficulty = Difficulty::query()
            ->where(
                'slug',
                'mudah',
            )
            ->firstOrFail();

        $mediumDifficulty = Difficulty::query()
            ->where(
                'slug',
                'menengah',
            )
            ->firstOrFail();

        $easyChallenges = Challenge::query()
            ->where(
                'difficulty_id',
                $easyDifficulty->id,
            )
            ->where(
                'status',
                'published',
            )
            ->orderBy('id')
            ->limit(9)
            ->get();

        $mediumChallenge = Challenge::query()
            ->where(
                'difficulty_id',
                $mediumDifficulty->id,
            )
            ->where(
                'status',
                'published',
            )
            ->orderBy('id')
            ->first();

        if (
            $easyChallenges->count() !== 9
            || $mediumChallenge === null
        ) {
            throw new RuntimeException(
                'Seeder progres demo membutuhkan sembilan tantangan mudah dan satu tantangan menengah yang berstatus published.',
            );
        }

        $completedChallenges = $easyChallenges
            ->concat([
                $mediumChallenge,
            ])
            ->values();

        $scores = [
            46,
            46,
            46,
            46,
            46,
            46,
            46,
            46,
            40,
            92,
        ];

        UserChallengeProgress::query()
            ->where(
                'user_id',
                $user->id,
            )
            ->whereNotIn(
                'challenge_id',
                $completedChallenges->pluck('id'),
            )
            ->update([
                'is_completed' => false,
                'completed_at' => null,
            ]);

        foreach (
            $completedChallenges as $index => $challenge
        ) {
            UserChallengeProgress::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'challenge_id' => $challenge->id,
                ],
                [
                    'best_submission_id' => null,
                    'best_score' => $scores[$index],
                    'attempts_count' => 1,
                    'hints_used' => 0,
                    'hint_penalty' => 0,
                    'unlocked_hint_ids' => [],
                    'is_completed' => true,
                    'completed_at' => now(),
                ],
            );
        }

        $user->forceFill([
            'total_points' => array_sum(
                $scores,
            ),
        ]);

        $user->save();
    }
}
