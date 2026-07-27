<?php

namespace App\Services;

use App\Models\Challenge;
use Illuminate\Support\Collection;

class ChallengeEvaluationService
{
    public function evaluate(
        Challenge $challenge,
        int $selectedLine,
        string $submittedCode,
        string $submittedExplanation,
        int $hintPenalty
    ): array {
        $lineMaximum = (int) round(
            $challenge->base_points * 0.30,
        );

        $codeMaximum = (int) round(
            $challenge->base_points * 0.50,
        );

        $explanationMaximum =
            $challenge->base_points
            - $lineMaximum
            - $codeMaximum;

        $lineScore =
            $selectedLine ===
            $challenge->buggy_line
                ? $lineMaximum
                : 0;

        $codeCorrect = $this->isCodeCorrect(
            $submittedCode,
            $challenge->solutions,
        );

        $codeScore = $codeCorrect
            ? $codeMaximum
            : 0;

        $keywords = $this->requiredKeywords(
            $challenge,
        );

        $matchedKeywords =
            $this->matchedKeywords(
                $submittedExplanation,
                $keywords,
            );

        $keywordRatio =
            $keywords->isEmpty()
                ? 1
                : $matchedKeywords->count()
                    / $keywords->count();

        $explanationScore = (int) round(
            $explanationMaximum
            * $keywordRatio,
        );

        $rawScore =
            $lineScore
            + $codeScore
            + $explanationScore;

        $finalScore = (int) round(
            $rawScore
            * max(
                0,
                100 - $hintPenalty,
            )
            / 100,
        );

        $completed =
            $lineScore === $lineMaximum
            && $codeCorrect
            && $keywordRatio >= 0.60;

        $status = $completed
            ? 'completed'
            : (
                $rawScore > 0
                    ? 'partially_correct'
                    : 'incorrect'
            );

        return [
            'line_score' => $lineScore,
            'code_score' => $codeScore,
            'explanation_score' =>
                $explanationScore,
            'hint_penalty' => $hintPenalty,
            'final_score' => $finalScore,
            'status' => $status,
            'matched_keywords' =>
                $matchedKeywords
                    ->values()
                    ->all(),
            'missing_keywords' =>
                $keywords
                    ->diff(
                        $matchedKeywords,
                    )
                    ->values()
                    ->all(),
        ];
    }

    private function isCodeCorrect(
        string $submittedCode,
        Collection $solutions
    ): bool {
        $submissionHash = hash(
            'sha256',
            $this->normalizeCode(
                $submittedCode,
            ),
        );

        return $solutions->contains(
            function ($solution) use (
                $submissionHash
            ) {
                $solutionHash = hash(
                    'sha256',
                    $this->normalizeCode(
                        $solution->solution_code,
                    ),
                );

                return hash_equals(
                    $solutionHash,
                    $submissionHash,
                );
            },
        );
    }

    private function requiredKeywords(
        Challenge $challenge
    ): Collection {
        $primary =
            $challenge->solutions
                ->firstWhere(
                    'solution_type',
                    'primary',
                )
            ?? $challenge->solutions
                ->first();

        return collect(
            $primary?->required_keywords ?? [],
        )
            ->map(
                fn ($keyword) =>
                    mb_strtolower(
                        trim(
                            (string) $keyword,
                        ),
                    ),
            )
            ->filter()
            ->unique()
            ->values();
    }

    private function matchedKeywords(
        string $explanation,
        Collection $keywords
    ): Collection {
        $normalizedExplanation =
            mb_strtolower(
                preg_replace(
                    '/\s+/u',
                    ' ',
                    trim($explanation),
                ) ?? '',
            );

        return $keywords->filter(
            fn ($keyword) =>
                str_contains(
                    $normalizedExplanation,
                    $keyword,
                ),
        );
    }

    private function normalizeCode(
        string $code
    ): string {
        $code = str_replace(
            ["\r\n", "\r"],
            "\n",
            trim($code),
        );

        return collect(
            explode("\n", $code),
        )
            ->map(
                fn ($line) =>
                    rtrim($line),
            )
            ->filter(
                fn ($line) =>
                    trim($line) !== '',
            )
            ->implode("\n");
    }
}
