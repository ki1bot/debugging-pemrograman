<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Submission;
use App\Models\UserChallengeProgress;
use App\Services\ChallengePresenter;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserDashboardController extends Controller
{
    public function __construct(
        private readonly ChallengePresenter $presenter
    ) {}

    public function __invoke(Request $request): Response
    {
        $user = $request->user();

        $completedCount = UserChallengeProgress::query()
            ->where('user_id', $user->id)
            ->where('is_completed', true)
            ->count();

        $recentProgress = UserChallengeProgress::query()
            ->with([
                'challenge.category',
                'challenge.difficulty',
                'bestSubmission',
            ])
            ->where('user_id', $user->id)
            ->latest('updated_at')
            ->limit(6)
            ->get()
            ->map(
                fn (UserChallengeProgress $progress) => $this->presenter->progress($progress),
            );

        $completedChallengeIds =
            UserChallengeProgress::query()
                ->where('user_id', $user->id)
                ->where('is_completed', true)
                ->pluck('challenge_id');

        $recommended = Challenge::query()
            ->with(['category', 'difficulty'])
            ->where('status', 'published')
            ->whereNotIn('id', $completedChallengeIds)
            ->orderBy('base_points')
            ->limit(6)
            ->get();

        $recommendedProgress =
            UserChallengeProgress::query()
                ->where('user_id', $user->id)
                ->whereIn(
                    'challenge_id',
                    $recommended->pluck('id'),
                )
                ->get()
                ->keyBy('challenge_id');

        return Inertia::render('Dashboard', [
            'summary' => [
                'totalPoints' => $user->total_points,
                'completedChallenges' => $completedCount,
                'totalChallenges' => Challenge::query()
                    ->where('status', 'published')
                    ->count(),
                'totalAttempts' => Submission::query()
                    ->where('user_id', $user->id)
                    ->count(),
            ],
            'recentProgress' => $recentProgress,
            'recommendedChallenges' => $recommended->map(
                fn (Challenge $challenge) => $this->presenter->challenge(
                    $challenge,
                    $recommendedProgress->get(
                        $challenge->id,
                    ),
                ),
            ),
        ]);
    }
}
