<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Submission;
use App\Models\User;
use App\Models\UserChallengeProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class BugHuntController extends Controller
{
    public function home(Request $request): Response
    {
        $featured = Challenge::query()
            ->join(
                'categories',
                'categories.id',
                '=',
                'challenges.category_id',
            )
            ->join(
                'difficulties',
                'difficulties.id',
                '=',
                'challenges.difficulty_id',
            )
            ->where('challenges.status', 'published')
            ->latest('challenges.created_at')
            ->limit(6)
            ->get([
                'challenges.id',
                'challenges.title',
                'challenges.slug',
                'challenges.description',
                'challenges.base_points',
                'categories.id as category_record_id',
                'categories.name as category_name',
                'categories.slug as category_slug',
                'difficulties.id as difficulty_record_id',
                'difficulties.name as difficulty_name',
                'difficulties.slug as difficulty_slug',
                'difficulties.base_points as difficulty_base_points',
            ]);

        $progress = $request->user()
            ? UserChallengeProgress::query()
                ->where('user_id', $request->user()->id)
                ->whereIn(
                    'challenge_id',
                    $featured->pluck('id'),
                )
                ->get([
                    'challenge_id',
                    'best_score',
                    'attempts_count',
                    'is_completed',
                ])
                ->keyBy('challenge_id')
            : collect();

        $stats = DB::query()
            ->selectSub(
                Challenge::query()
                    ->selectRaw('count(*)')
                    ->where('status', 'published'),
                'challenges',
            )
            ->selectSub(
                User::query()
                    ->selectRaw('count(*)')
                    ->where('role', 'user'),
                'hunters',
            )
            ->selectSub(
                Submission::query()
                    ->selectRaw('count(*)')
                    ->where('status', 'completed'),
                'completed_submissions',
            )
            ->first();

        return Inertia::render('Home', [
            'stats' => [
                'challenges' => (int) $stats->challenges,
                'hunters' => (int) $stats->hunters,
                'completedSubmissions' => (int) $stats->completed_submissions,
            ],
            'featuredChallenges' => $featured->map(
                function (Challenge $challenge) use ($progress): array {
                    $challengeProgress = $progress->get($challenge->id);

                    return [
                        'id' => $challenge->id,
                        'title' => $challenge->title,
                        'slug' => $challenge->slug,
                        'description' => $challenge->description,
                        'base_points' => $challenge->base_points,
                        'category' => [
                            'id' => (int) $challenge->category_record_id,
                            'name' => $challenge->category_name,
                            'slug' => $challenge->category_slug,
                        ],
                        'difficulty' => [
                            'id' => (int) $challenge->difficulty_record_id,
                            'name' => $challenge->difficulty_name,
                            'slug' => $challenge->difficulty_slug,
                            'base_points' => (int) $challenge->difficulty_base_points,
                        ],
                        'progress' => $challengeProgress
                            ? [
                                'best_score' => (int) (
                                    $challengeProgress->best_score
                                    ?? 0
                                ),
                                'attempts_count' => (int) (
                                    $challengeProgress->attempts_count
                                    ?? 0
                                ),
                                'is_completed' => (bool) (
                                    $challengeProgress->is_completed
                                    ?? false
                                ),
                            ]
                            : null,
                    ];
                },
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
