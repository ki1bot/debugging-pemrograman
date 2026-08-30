<?php

namespace Tests\Unit;

use Database\Seeders\Data\JavaChallenges;
use Database\Seeders\Data\JavascriptChallenges;
use PHPUnit\Framework\TestCase;

class RunnableChallengeDefinitionsTest extends TestCase
{
    public function test_javascript_challenges_that_require_runtime_feedback_are_self_contained(): void
    {
        $challenges = collect(JavascriptChallenges::all())->keyBy('slug');

        $assignment = $challenges->get('javascript-perbandingan-menggunakan-assignment');
        $scope = $challenges->get('javascript-kesalahan-scope-variabel');
        $promise = $challenges->get('javascript-promise-tidak-ditunggu');
        $asyncForEach = $challenges->get('javascript-async-foreach-tidak-ditunggu');

        $this->assertStringContainsString('let age = 20;', $assignment['broken_code']);
        $this->assertStringNotContainsString('const age = 20;', $assignment['broken_code']);
        $this->assertStringContainsString('console.log(calculateTotal());', $scope['broken_code']);
        $this->assertStringContainsString('fakeFetch()', $promise['broken_code']);
        $this->assertStringNotContainsString("fetch('/api/user')", $promise['broken_code']);
        $this->assertStringContainsString("saveAll(['A', 'B', 'C'])", $asyncForEach['broken_code']);
        $this->assertStringContainsString('setTimeout', $asyncForEach['broken_code']);
    }

    public function test_java_challenges_are_valid_single_file_judge0_sources(): void
    {
        foreach (JavaChallenges::all() as $challenge) {
            $codeSamples = [
                $challenge['broken_code'],
                ...array_column($challenge['solutions'], 'solution_code'),
            ];

            foreach ($codeSamples as $code) {
                $this->assertStringContainsString('public class Main', $code);
            }
        }
    }

    public function test_java_concurrent_modification_challenge_triggers_on_first_iteration(): void
    {
        $challenge = collect(JavaChallenges::all())
            ->firstWhere('slug', 'java-menghapus-elemen-saat-enhanced-for');

        $this->assertStringContainsString(
            'List.of("Rifqi", "Ana", "Budi")',
            $challenge['broken_code'],
        );
    }

    public function test_java_double_checked_locking_solution_uses_volatile(): void
    {
        $challenge = collect(JavaChallenges::all())
            ->firstWhere('slug', 'java-double-checked-locking-tanpa-volatile');

        $primary = collect($challenge['solutions'])
            ->firstWhere('solution_type', 'primary');

        $this->assertStringContainsString(
            'private static volatile Singleton instance;',
            $primary['solution_code'],
        );
    }
}
