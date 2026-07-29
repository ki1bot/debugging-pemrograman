<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\UserChallengeProgress;
use App\Services\ChallengePresenter;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubmissionController extends Controller
{
    public function __construct(
        private readonly ChallengePresenter $presenter
    ) {}

    public function show(
        Request $request,
        Submission $submission
    ): Response {
        if (
            $submission->user_id
                !== $request->user()->id
            && ! $request->user()->isAdmin()
        ) {
            abort(403);
        }

        $submission->load([
            'challenge.category',
            'challenge.difficulty',
            'attempts',
        ]);

        $challengeCompleted =
            UserChallengeProgress::query()
                ->where(
                    'user_id',
                    $submission->user_id,
                )
                ->where(
                    'challenge_id',
                    $submission->challenge_id,
                )
                ->where('is_completed', true)
                ->exists();

        $canViewSolution =
            $request->user()->isAdmin()
            || $challengeCompleted;

        $primarySolution = null;
        $alternativeSolutions = [];

        if ($canViewSolution) {
            $submission->load(
                'challenge.solutions',
            );

            $primary =
                $submission
                    ->challenge
                    ->solutions
                    ->firstWhere(
                        'solution_type',
                        'primary',
                    )
                ?? $submission
                    ->challenge
                    ->solutions
                    ->first();

            $primarySolution =
                $primary?->solution_code;

            $alternativeSolutions =
                $submission
                    ->challenge
                    ->solutions
                    ->where(
                        'solution_type',
                        'alternative',
                    )
                    ->pluck('solution_code')
                    ->values()
                    ->all();
        }

        return Inertia::render(
            'Submissions/Detail',
            [
                'submission' => [
                    'id' => $submission->id,
                    'selected_line' => $submission->selected_line,
                    'submitted_code' => $submission->submitted_code,
                    'submitted_explanation' => $submission
                        ->submitted_explanation,
                    'line_score' => $submission->line_score,
                    'code_score' => $submission->code_score,
                    'explanation_score' => $submission
                        ->explanation_score,
                    'hint_penalty' => $submission->hint_penalty,
                    'final_score' => $submission->final_score,
                    'status' => $submission->status,
                    'completed_at' => $submission->completed_at,
                    'attempts_count' => (int) (
                        $submission
                            ->attempts
                            ->max('attempt_number')
                        ?? 1
                    ),
                    'challenge' => [
                    ...$this->presenter->challenge(
                        $submission->challenge,
                    ),
                    'broken_code' => $submission
                        ->challenge
                        ->broken_code,
                    'can_view_solution' => $canViewSolution,
                    'buggy_line' => $canViewSolution
                            ? $submission
                                ->challenge
                                ->buggy_line
                            : null,
                    'explanation' => $canViewSolution
                            ? $submission
                                ->challenge
                                ->explanation
                            : null,
                    'primary_solution' => $primarySolution,
                    'alternative_solutions' => $alternativeSolutions,
                    ],
                ],
            ],
        );
    }

    public function history(
        Request $request
    ): Response {
        $submissions = Submission::query()
            ->with([
                'challenge.category',
                'challenge.difficulty',
            ])
            ->where(
                'user_id',
                $request->user()->id,
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('History/Daftar', [
            'submissions' => $submissions,
        ]);
    }
}
