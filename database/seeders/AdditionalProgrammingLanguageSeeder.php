<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Difficulty;
use App\Models\User;
use Database\Seeders\Data\CChallenges;
use Database\Seeders\Data\CppChallenges;
use Database\Seeders\Data\JavaChallenges;
use Database\Seeders\Data\PythonChallenges;
use Database\Seeders\Support\ChallengeCatalogWriter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AdditionalProgrammingLanguageSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $admin = User::query()
                ->where('role', 'admin')
                ->orderBy('id')
                ->firstOrFail();

            $categories = collect([
                [
                    'name' => 'C',
                    'slug' => 'c',
                    'description' => 'Tantangan debugging bahasa C yang mencakup array, pointer, function, alokasi memory, input, dan pengelolaan resource.',
                ],
                [
                    'name' => 'C++',
                    'slug' => 'cpp',
                    'description' => 'Tantangan debugging C++ yang mencakup reference, object, STL, iterator, polymorphism, lambda, dan concurrency.',
                ],
                [
                    'name' => 'Java',
                    'slug' => 'java',
                    'description' => 'Tantangan debugging Java yang mencakup String, array, collection, object, exception, concurrency, dan thread safety.',
                ],
                [
                    'name' => 'Python',
                    'slug' => 'python',
                    'description' => 'Tantangan debugging Python yang mencakup list, dictionary, function, closure, salinan data, dan asynchronous programming.',
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
                                    'name' => $category[
                                            'name'
                                        ],
                                    'description' => $category[
                                            'description'
                                        ],
                                    'is_active' => true,
                                ],
                            );

                    return [
                        $category['slug'] => $model,
                    ];
                },
            );

            $difficulties =
                Difficulty::query()
                    ->whereIn(
                        'slug',
                        [
                            'mudah',
                            'menengah',
                            'sulit',
                        ],
                    )
                    ->get()
                    ->keyBy('slug');

            foreach (
                [
                    'mudah',
                    'menengah',
                    'sulit',
                ] as $difficultySlug
            ) {
                if (
                    ! $difficulties->has(
                        $difficultySlug,
                    )
                ) {
                    throw new RuntimeException(
                        "Tingkat kesulitan {$difficultySlug} belum tersedia.",
                    );
                }
            }

            app(
                ChallengeCatalogWriter::class,
            )->write(
                $categories,
                $difficulties,
                $admin,
                [
                    ...CChallenges::all(),
                    ...CppChallenges::all(),
                    ...JavaChallenges::all(),
                    ...PythonChallenges::all(),
                ],
            );
        });
    }
}
