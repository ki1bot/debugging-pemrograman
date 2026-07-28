<?php

namespace App\Services;

use App\Models\Challenge;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminChallengeService
{
    public function create(
        User $creator,
        array $validated
    ): Challenge {
        return DB::transaction(
            function () use ($creator, $validated) {
                $challenge = Challenge::query()->create([
                    'category_id' => $validated['category_id'],
                    'difficulty_id' => $validated['difficulty_id'],
                    'title' => $validated['title'],
                    'slug' => $validated['slug']
                        ?: $this->uniqueSlug(
                            $validated['title'],
                        ),
                    'description' => $validated['description'],
                    'broken_code' => $validated['broken_code'],
                    'buggy_line' => $validated['buggy_line'],
                    'explanation' => $validated['explanation'],
                    'base_points' => $validated['base_points'],
                    'status' => $validated['status'],
                    'created_by' => $creator->id,
                ]);

                $this->syncRelations(
                    $challenge,
                    $validated,
                );

                return $challenge;
            },
        );
    }

    public function update(
        Challenge $challenge,
        array $validated
    ): void {
        DB::transaction(
            function () use ($challenge, $validated) {
                $challenge->update([
                    'category_id' => $validated['category_id'],
                    'difficulty_id' => $validated['difficulty_id'],
                    'title' => $validated['title'],
                    'slug' => $validated['slug']
                        ?: $challenge->slug,
                    'description' => $validated['description'],
                    'broken_code' => $validated['broken_code'],
                    'buggy_line' => $validated['buggy_line'],
                    'explanation' => $validated['explanation'],
                    'base_points' => $validated['base_points'],
                    'status' => $validated['status'],
                ]);

                $this->syncRelations(
                    $challenge,
                    $validated,
                );
            },
        );
    }

    private function syncRelations(
        Challenge $challenge,
        array $validated
    ): void {
        $challenge->hints()->delete();
        $challenge->solutions()->delete();

        foreach (
            array_values($validated['hints']) as $index => $hint
        ) {
            $challenge->hints()->create([
                'hint_order' => $index + 1,
                'content' => $hint['content'],
                'point_penalty' => $hint['point_penalty'],
            ]);
        }

        foreach ($validated['solutions'] as $solution) {
            $challenge->solutions()->create([
                'solution_code' => $solution['solution_code'],
                'solution_type' => $solution['solution_type'],
                'required_keywords' => collect(
                    $solution['required_keywords'] ?? [],
                )
                    ->map(
                        fn ($keyword) => mb_strtolower(
                            trim((string) $keyword),
                        ),
                    )
                    ->filter()
                    ->unique()
                    ->values()
                    ->all(),
            ]);
        }
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 2;

        while (
            Challenge::withTrashed()
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
