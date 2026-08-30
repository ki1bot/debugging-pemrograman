<?php

use Database\Seeders\Data\JavaChallenges;
use Database\Seeders\Data\JavascriptChallenges;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $slugs = [
            'javascript-perbandingan-menggunakan-assignment',
            'javascript-kesalahan-scope-variabel',
            'javascript-promise-tidak-ditunggu',
            'javascript-async-foreach-tidak-ditunggu',
            'java-menghapus-elemen-saat-enhanced-for',
            'java-double-checked-locking-tanpa-volatile',
        ];

        $definitions = collect([
            ...JavascriptChallenges::all(),
            ...JavaChallenges::all(),
        ])->whereIn('slug', $slugs);

        DB::transaction(function () use ($definitions): void {
            foreach ($definitions as $definition) {
                $this->syncChallenge($definition);
            }
        });
    }

    public function down(): void
    {
    }

    private function syncChallenge(array $definition): void
    {
        $challenge = DB::table('challenges')
            ->where('slug', $definition['slug'])
            ->first(['id']);

        if ($challenge === null) {
            return;
        }

        $now = now();

        DB::table('challenges')
            ->where('id', $challenge->id)
            ->update([
                'description' => $definition['description'],
                'broken_code' => $definition['broken_code'],
                'buggy_line' => $definition['buggy_line'],
                'explanation' => $definition['explanation'],
                'updated_at' => $now,
            ]);

        foreach ($definition['hints'] as $index => $hint) {
            $hintOrder = $index + 1;

            $existingHint = DB::table('challenge_hints')
                ->where('challenge_id', $challenge->id)
                ->where('hint_order', $hintOrder)
                ->first(['id']);

            $values = [
                'content' => $hint['content'],
                'point_penalty' => $hint['point_penalty'],
                'updated_at' => $now,
            ];

            if ($existingHint === null) {
                DB::table('challenge_hints')->insert([
                    'challenge_id' => $challenge->id,
                    'hint_order' => $hintOrder,
                    ...$values,
                    'created_at' => $now,
                ]);

                continue;
            }

            DB::table('challenge_hints')
                ->where('id', $existingHint->id)
                ->update($values);
        }

        foreach ($definition['solutions'] as $solution) {
            $existingSolution = DB::table('challenge_solutions')
                ->where('challenge_id', $challenge->id)
                ->where('solution_type', $solution['solution_type'])
                ->first(['id']);

            $values = [
                'solution_code' => $solution['solution_code'],
                'required_keywords' => json_encode(
                    $solution['required_keywords'],
                    JSON_THROW_ON_ERROR,
                ),
                'updated_at' => $now,
            ];

            if ($existingSolution === null) {
                DB::table('challenge_solutions')->insert([
                    'challenge_id' => $challenge->id,
                    'solution_type' => $solution['solution_type'],
                    ...$values,
                    'created_at' => $now,
                ]);

                continue;
            }

            DB::table('challenge_solutions')
                ->where('id', $existingSolution->id)
                ->update($values);
        }
    }
};
