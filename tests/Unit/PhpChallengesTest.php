<?php

namespace Tests\Unit;

use Database\Seeders\Data\PhpChallenges;
use PHPUnit\Framework\TestCase;

class PhpChallengesTest extends TestCase
{
    public function test_php_challenges_do_not_depend_on_web_or_database_runtime(): void
    {
        foreach (PhpChallenges::all() as $challenge) {
            $codeSamples = [
                $challenge['broken_code'],
                ...array_column($challenge['solutions'], 'solution_code'),
            ];

            foreach ($codeSamples as $code) {
                $this->assertStringNotContainsString('$_POST', $code);
                $this->assertStringNotContainsString('mysqli_', $code);
                $this->assertStringNotContainsString('$pdo', $code);
            }
        }
    }

    public function test_php_challenges_keep_expected_distribution(): void
    {
        $challenges = PhpChallenges::all();

        $this->assertCount(8, $challenges);
        $this->assertCount(
            3,
            array_filter(
                $challenges,
                fn (array $challenge): bool => $challenge['difficulty'] === 'mudah',
            ),
        );
        $this->assertCount(
            3,
            array_filter(
                $challenges,
                fn (array $challenge): bool => $challenge['difficulty'] === 'menengah',
            ),
        );
        $this->assertCount(
            2,
            array_filter(
                $challenges,
                fn (array $challenge): bool => $challenge['difficulty'] === 'sulit',
            ),
        );
    }
}
