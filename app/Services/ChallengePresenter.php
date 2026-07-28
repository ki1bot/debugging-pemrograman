<?php

namespace App\Services;

use App\Models\Challenge;
use App\Models\UserChallengeProgress;

class ChallengePresenter
{
    public function challenge(
        Challenge $challenge,
        ?UserChallengeProgress $progress = null
    ): array {
        return [
            'id' => $challenge->id,
            'title' => $challenge->title,
            'slug' => $challenge->slug,
            'description' => $challenge->description,
            'base_points' => $challenge->base_points,
            'category' => [
                'id' => $challenge->category->id,
                'name' => $challenge->category->name,
                'slug' => $challenge->category->slug,
            ],
            'difficulty' => [
                'id' => $challenge->difficulty->id,
                'name' => $challenge->difficulty->name,
                'slug' => $challenge->difficulty->slug,
                'base_points' => $challenge
                    ->difficulty
                    ->base_points,
            ],
            'progress' => $progress
                ? [
                    'best_score' => (int) (
                        $progress->best_score
                        ?? 0
                    ),
                    'attempts_count' => (int) (
                        $progress->attempts_count
                        ?? 0
                    ),
                    'is_completed' => (bool) (
                        $progress->is_completed
                        ?? false
                    ),
                ]
                : null,
        ];
    }

    public function progress(
        UserChallengeProgress $progress
    ): array {
        return [
            'id' => $progress->id,
            'best_score' => (int) ($progress->best_score ?? 0),
            'attempts_count' => (int) (
                $progress->attempts_count
                ?? 0
            ),
            'hints_used' => (int) ($progress->hints_used ?? 0),
            'is_completed' => (bool) (
                $progress->is_completed
                ?? false
            ),
            'completed_at' => $progress->completed_at,
            'updated_at' => $progress->updated_at,
            'submission_id' => $progress->bestSubmission?->id,
            'latest_status' => $progress->bestSubmission?->status,
            'latest_score' => $progress
                ->bestSubmission
                ?->final_score,
            'challenge' => $this->challenge(
                $progress->challenge,
                $progress,
            ),
        ];
    }
}
