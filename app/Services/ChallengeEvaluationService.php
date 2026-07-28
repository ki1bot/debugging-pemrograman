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
            $selectedLine === $challenge->buggy_line
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

        $matchedKeywords = $this->matchedKeywords(
            $submittedExplanation,
            $keywords,
        );

        $keywordRatio = $keywords->isEmpty()
            ? 1
            : $matchedKeywords->count()
                / $keywords->count();

        $explanationScore = (int) round(
            $explanationMaximum * $keywordRatio,
        );

        $rawScore =
            $lineScore
            + $codeScore
            + $explanationScore;

        $normalizedPenalty = min(
            100,
            max(0, $hintPenalty),
        );

        $finalScore = (int) round(
            $rawScore
            * (100 - $normalizedPenalty)
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
            'explanation_score' => $explanationScore,
            'hint_penalty' => $normalizedPenalty,
            'final_score' => $finalScore,
            'status' => $status,
            'matched_keywords' => $matchedKeywords
                ->values()
                ->all(),
            'missing_keywords' => $keywords
                ->diff($matchedKeywords)
                ->values()
                ->all(),
        ];
    }

    private function isCodeCorrect(
        string $submittedCode,
        Collection $solutions
    ): bool {
        $normalizedSubmission =
            $this->normalizeCode(
                $submittedCode,
            );

        if ($normalizedSubmission === '') {
            return false;
        }

        $submissionHash = hash(
            'sha256',
            $normalizedSubmission,
        );

        return $solutions->contains(
            function ($solution) use (
                $submissionHash
            ): bool {
                $normalizedSolution =
                    $this->normalizeCode(
                        $solution->solution_code,
                    );

                if ($normalizedSolution === '') {
                    return false;
                }

                return hash_equals(
                    hash(
                        'sha256',
                        $normalizedSolution,
                    ),
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
            ?? $challenge->solutions->first();

        return collect(
            $primary?->required_keywords ?? [],
        )
            ->map(
                fn ($keyword) => mb_strtolower(
                    trim((string) $keyword),
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
            fn ($keyword) => str_contains(
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

        $code = preg_replace(
            '/^[\t ]+|[\t ]+$/m',
            '',
            $code,
        ) ?? $code;

        $code = preg_replace(
            '/^\s*$(?:\n|$)/m',
            '',
            $code,
        ) ?? $code;

        $normalized = '';
        $quote = null;
        $escaped = false;
        $pendingWhitespace = false;
        $length = strlen($code);

        for ($index = 0; $index < $length; $index++) {
            $character = $code[$index];

            if ($quote !== null) {
                $normalized .= $character;

                if ($escaped) {
                    $escaped = false;

                    continue;
                }

                if ($character === '\\') {
                    $escaped = true;

                    continue;
                }

                if ($character === $quote) {
                    $quote = null;
                }

                continue;
            }

            if (
                $character === "'"
                || $character === '"'
                || $character === '`'
            ) {
                if (
                    $pendingWhitespace
                    && $this->requiresSeparator(
                        $normalized,
                        $character,
                    )
                ) {
                    $normalized .= ' ';
                }

                $pendingWhitespace = false;
                $quote = $character;
                $normalized .= $character;

                continue;
            }

            if (ctype_space($character)) {
                $pendingWhitespace = true;

                continue;
            }

            if (
                $pendingWhitespace
                && $this->requiresSeparator(
                    $normalized,
                    $character,
                )
            ) {
                $normalized .= ' ';
            }

            $pendingWhitespace = false;
            $normalized .= $character;
        }

        return trim($normalized);
    }

    private function requiresSeparator(
        string $normalized,
        string $nextCharacter
    ): bool {
        if ($normalized === '') {
            return false;
        }

        $previousCharacter =
            $normalized[
                strlen($normalized) - 1
            ];

        return $this->isWordCharacter(
            $previousCharacter,
        ) && $this->isWordCharacter(
            $nextCharacter,
        );
    }

    private function isWordCharacter(
        string $character
    ): bool {
        return preg_match(
            '/[A-Za-z0-9_$]/',
            $character,
        ) === 1;
    }
}
