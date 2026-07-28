<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Submission;
use App\Models\User;
use App\Models\UserChallengeProgress;
use App\Services\ChallengePresenter;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BugHuntController extends Controller
{
    public function __construct(
        private readonly ChallengePresenter $presenter
    ) {}

    public function home(Request $request): Response
    {
        $featured = Challenge::query()
            ->with(['category', 'difficulty'])
            ->where('status', 'published')
            ->latest()
            ->limit(6)
            ->get();

        $progress = $request->user()
            ? UserChallengeProgress::query()
                ->where('user_id', $request->user()->id)
                ->whereIn(
                    'challenge_id',
                    $featured->pluck('id'),
                )
                ->get()
                ->keyBy('challenge_id')
            : collect();

        return Inertia::render('Home', [
            'stats' => [
                'challenges' => Challenge::query()
                    ->where('status', 'published')
                    ->count(),
                'hunters' => User::query()
                    ->where('role', 'user')
                    ->count(),
                'completedSubmissions' => Submission::query()
                    ->where('status', 'completed')
                    ->count(),
            ],
            'featuredChallenges' => $featured->map(
                fn (Challenge $challenge) => $this->presenter->challenge(
                    $challenge,
                    $progress->get($challenge->id),
                ),
            ),
        ]);
    }

    public function about(): Response
    {
        return Inertia::render('About');
    }

    public function leaderboard(): Response
    {
        $leaders = User::query()
            ->where('role', 'user')
            ->withCount([
                'challengeProgress as completed_challenges' => fn ($query) => $query
                    ->where('is_completed', true),
            ])
            ->orderByDesc('total_points')
            ->orderByDesc('completed_challenges')
            ->orderBy('name')
            ->limit(100)
            ->get([
                'id',
                'name',
                'total_points',
                'created_at',
            ])
            ->values()
            ->map(
                fn (User $user, int $index) => [
                    'rank' => $index + 1,
                    'id' => $user->id,
                    'name' => $user->name,
                    'total_points' => $user->total_points,
                    'completed_challenges' => $user->completed_challenges,
                    'joined_at' => $user->created_at,
                ],
            );

        return Inertia::render('Leaderboard', [
            'leaders' => $leaders,
        ]);
    }
}
