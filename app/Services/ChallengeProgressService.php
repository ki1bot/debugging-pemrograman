<?php

namespace App\Services;

use App\Models\UserChallengeProgress;

class ChallengeProgressService
{
    public function findOrCreate(
        int $userId,
        int $challengeId
    ): UserChallengeProgress {
        return UserChallengeProgress::query()
            ->firstOrCreate(
                [
                    'user_id' => $userId,
                    'challenge_id' => $challengeId,
                ],
                [
                    'best_score' => 0,
                    'attempts_count' => 0,
                    'hints_used' => 0,
                    'hint_penalty' => 0,
                    'unlocked_hint_ids' => [],
                    'is_completed' => false,
                ],
            );
    }
}
