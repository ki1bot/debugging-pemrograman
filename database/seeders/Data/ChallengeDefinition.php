<?php

namespace Database\Seeders\Data;

class ChallengeDefinition
{
    public static function make(
        string $category,
        string $difficulty,
        string $title,
        string $slug,
        string $description,
        string $brokenCode,
        int $buggyLine,
        string $explanation,
        string $firstHint,
        string $secondHint,
        string $primarySolutionCode,
        array $requiredKeywords,
        ?string $alternativeSolutionCode = null
    ): array {
        $solutions = [
            [
                'solution_code' => $primarySolutionCode,
                'solution_type' => 'primary',
                'required_keywords' => $requiredKeywords,
            ],
        ];

        if (
            $alternativeSolutionCode !== null
        ) {
            $solutions[] = [
                'solution_code' => $alternativeSolutionCode,
                'solution_type' => 'alternative',
                'required_keywords' => [],
            ];
        }

        return [
            'category' => $category,
            'difficulty' => $difficulty,
            'title' => $title,
            'slug' => $slug,
            'description' => $description,
            'broken_code' => $brokenCode,
            'buggy_line' => $buggyLine,
            'explanation' => $explanation,
            'hints' => [
                [
                    'content' => $firstHint,
                    'point_penalty' => 10,
                ],
                [
                    'content' => $secondHint,
                    'point_penalty' => 20,
                ],
            ],
            'solutions' => $solutions,
        ];
    }
}
