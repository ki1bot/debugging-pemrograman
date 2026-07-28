<?php

namespace App\Services;

use App\Models\Challenge;
use App\Models\Submission;
use App\Models\SubmissionAttempt;
use App\Models\User;
use App\Models\UserChallengeProgress;
use Illuminate\Support\Facades\DB;

class ChallengeSubmissionService
{
    public function __construct(
        private readonly ChallengeEvaluationService $evaluationService
    ) {}

    public function submit(
        User $authenticatedUser,
        Challenge $challenge,
        array $validated
    ): Submission {
        return DB::transaction(
            function () use (
                $authenticatedUser,
                $challenge,
                $validated
            ) {
                $user = User::query()
                    ->lockForUpdate()
                    ->findOrFail(
                        $authenticatedUser->id,
                    );

                $progress =
                    UserChallengeProgress::query()
                        ->where(
                            'user_id',
                            $user->id,
                        )
                        ->where(
                            'challenge_id',
                            $challenge->id,
                        )
                        ->lockForUpdate()
                        ->first();

                if (! $progress) {
                    $progress =
                        UserChallengeProgress::query()
                            ->create([
                                'user_id' => $user->id,
                                'challenge_id' => $challenge->id,
                                'best_score' => 0,
                                'attempts_count' => 0,
                                'hints_used' => 0,
                                'hint_penalty' => 0,
                                'unlocked_hint_ids' => [],
                                'is_completed' => false,
                            ]);
                }

                $hintPenalty =
                    (int) (
                        $progress->hint_penalty
                        ?? 0
                    );

                $attemptsCount =
                    (int) (
                        $progress->attempts_count
                        ?? 0
                    );

                $previousBest =
                    (int) (
                        $progress->best_score
                        ?? 0
                    );

                $wasCompleted =
                    (bool) (
                        $progress->is_completed
                        ?? false
                    );

                $result =
                    $this->evaluationService
                        ->evaluate(
                            $challenge,
                            (int) $validated[
                                'selected_line'
                            ],
                            $validated[
                                'submitted_code'
                            ],
                            $validated[
                                'submitted_explanation'
                            ],
                            $hintPenalty,
                        );

                $submission =
                    Submission::query()->create([
                        'user_id' => $user->id,
                        'challenge_id' => $challenge->id,
                        'selected_line' => $validated[
                                'selected_line'
                            ],
                        'submitted_code' => $validated[
                                'submitted_code'
                            ],
                        'submitted_explanation' => $validated[
                                'submitted_explanation'
                            ],
                        'line_score' => $result['line_score'],
                        'code_score' => $result['code_score'],
                        'explanation_score' => $result[
                                'explanation_score'
                            ],
                        'hint_penalty' => $result['hint_penalty'],
                        'final_score' => $result['final_score'],
                        'status' => $result['status'],
                        'completed_at' => $result['status']
                                === 'completed'
                                ? now()
                                : null,
                    ]);

                SubmissionAttempt::query()
                    ->create([
                        'submission_id' => $submission->id,
                        'attempt_number' => $attemptsCount + 1,
                        'line_correct' => $result['line_score'] > 0,
                        'code_correct' => $result['code_score'] > 0,
                        'matched_keywords' => $result[
                                'matched_keywords'
                            ],
                        'missing_keywords' => $result[
                                'missing_keywords'
                            ],
                        'score_snapshot' => $result['final_score'],
                        'status_snapshot' => $result['status'],
                    ]);

                $newBest = max(
                    $previousBest,
                    (int) $result['final_score'],
                );

                $becameCompleted =
                    ! $wasCompleted
                    && $result['status']
                        === 'completed';

                $progress->update([
                    'best_submission_id' => $newBest > $previousBest
                            ? $submission->id
                            : $progress
                                ->best_submission_id,
                    'best_score' => $newBest,
                    'attempts_count' => $attemptsCount + 1,
                    'is_completed' => $wasCompleted
                        || $result['status']
                            === 'completed',
                    'completed_at' => $becameCompleted
                            ? now()
                            : $progress
                                ->completed_at,
                ]);

                if ($newBest > $previousBest) {
                    $user->increment(
                        'total_points',
                        $newBest - $previousBest,
                    );
                }

                return $submission;
            },
        );
    }
}
