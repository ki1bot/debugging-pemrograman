<?php

namespace Database\Seeders\Support;

use App\Models\Challenge;
use App\Models\User;
use Illuminate\Support\Collection;
use RuntimeException;

class ChallengeCatalogWriter
{
    public function write(
        Collection $categories,
        Collection $difficulties,
        User $admin,
        array $challenges
    ): void {
        foreach ($challenges as $data) {
            $category = $categories->get(
                $data['category'],
            );

            $difficulty = $difficulties->get(
                $data['difficulty'],
            );

            if (! $category) {
                throw new RuntimeException(
                    "Kategori {$data['category']} belum tersedia.",
                );
            }

            if (! $difficulty) {
                throw new RuntimeException(
                    "Tingkat kesulitan {$data['difficulty']} belum tersedia.",
                );
            }

            $challenge =
                Challenge::withTrashed()
                    ->updateOrCreate(
                        [
                            'slug' => $data['slug'],
                        ],
                        [
                            'category_id' => $category->id,
                            'difficulty_id' => $difficulty->id,
                            'title' => $data['title'],
                            'description' => $data[
                                    'description'
                                ],
                            'broken_code' => $data[
                                    'broken_code'
                                ],
                            'buggy_line' => $data[
                                    'buggy_line'
                                ],
                            'explanation' => $data[
                                    'explanation'
                                ],
                            'base_points' => $difficulty
                                ->base_points,
                            'status' => 'published',
                            'created_by' => $admin->id,
                            'deleted_at' => null,
                        ],
                    );

            $challenge->hints()->delete();
            $challenge->solutions()->delete();

            foreach (
                $data['hints'] as $index => $hint
            ) {
                $challenge
                    ->hints()
                    ->create([
                        'hint_order' => $index + 1,
                        'content' => $hint['content'],
                        'point_penalty' => $hint[
                                'point_penalty'
                            ],
                    ]);
            }

            foreach (
                $data['solutions'] as $solution
            ) {
                $challenge
                    ->solutions()
                    ->create($solution);
            }
        }
    }
}
