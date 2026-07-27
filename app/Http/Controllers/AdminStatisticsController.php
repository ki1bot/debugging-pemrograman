<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AdminStatisticsController extends Controller
{
    public function __invoke(): Response
    {
        $totalSubmissions =
            Submission::query()->count();

        $completedSubmissions =
            Submission::query()
                ->where(
                    'status',
                    'completed',
                )
                ->count();

        $completionRate =
            $totalSubmissions > 0
                ? round(
                    $completedSubmissions
                    / $totalSubmissions
                    * 100,
                    2,
                )
                : 0;

        $averageScore = round(
            (float) (
                Submission::query()
                    ->avg('final_score')
                ?? 0
            ),
            2,
        );

        $statusCounts =
            Submission::query()
                ->select(
                    'status',
                    DB::raw(
                        'COUNT(*) as total',
                    ),
                )
                ->groupBy('status')
                ->pluck('total', 'status');

        $statusChart = collect([
            'incorrect' => 'Belum tepat',
            'partially_correct' =>
                'Sebagian benar',
            'completed' => 'Selesai',
        ])->map(
            fn (
                string $label,
                string $status
            ) => [
                'name' => $label,
                'value' => (int) (
                    $statusCounts[$status]
                    ?? 0
                ),
            ],
        )->values();

        $startDate = Carbon::now()
            ->subDays(13)
            ->startOfDay();

        $dailyGroups =
            Submission::query()
                ->where(
                    'created_at',
                    '>=',
                    $startDate,
                )
                ->get([
                    'id',
                    'status',
                    'created_at',
                ])
                ->groupBy(
                    fn (Submission $submission) =>
                        $submission
                            ->created_at
                            ->format('Y-m-d'),
                );

        $dailySubmissions =
            collect(range(0, 13))
                ->map(
                    function (
                        int $offset
                    ) use (
                        $startDate,
                        $dailyGroups
                    ): array {
                        $date = $startDate
                            ->copy()
                            ->addDays($offset);

                        $items =
                            $dailyGroups->get(
                                $date->format(
                                    'Y-m-d',
                                ),
                                collect(),
                            );

                        return [
                            'date' =>
                                $date->format(
                                    'Y-m-d',
                                ),
                            'label' =>
                                $date->format(
                                    'd M',
                                ),
                            'total' =>
                                $items->count(),
                            'completed' =>
                                $items
                                    ->where(
                                        'status',
                                        'completed',
                                    )
                                    ->count(),
                        ];
                    },
                );

        $categoryPerformance =
            Submission::query()
                ->join(
                    'challenges',
                    'submissions.challenge_id',
                    '=',
                    'challenges.id',
                )
                ->join(
                    'categories',
                    'challenges.category_id',
                    '=',
                    'categories.id',
                )
                ->select(
                    'categories.id',
                    'categories.name',
                    DB::raw(
                        'COUNT(submissions.id) as total_submissions',
                    ),
                    DB::raw(
                        'AVG(submissions.final_score) as average_score',
                    ),
                )
                ->groupBy(
                    'categories.id',
                    'categories.name',
                )
                ->orderBy(
                    'categories.name',
                )
                ->get()
                ->map(
                    fn ($row) => [
                        'name' => $row->name,
                        'submissions' =>
                            (int) $row
                                ->total_submissions,
                        'average_score' =>
                            round(
                                (float) $row
                                    ->average_score,
                                2,
                            ),
                    ],
                );

        $difficultyPerformance =
            Submission::query()
                ->join(
                    'challenges',
                    'submissions.challenge_id',
                    '=',
                    'challenges.id',
                )
                ->join(
                    'difficulties',
                    'challenges.difficulty_id',
                    '=',
                    'difficulties.id',
                )
                ->select(
                    'difficulties.id',
                    'difficulties.name',
                    'difficulties.base_points',
                    DB::raw(
                        'COUNT(submissions.id) as total_submissions',
                    ),
                    DB::raw(
                        'AVG(submissions.final_score) as average_score',
                    ),
                )
                ->groupBy(
                    'difficulties.id',
                    'difficulties.name',
                    'difficulties.base_points',
                )
                ->orderBy(
                    'difficulties.base_points',
                )
                ->get()
                ->map(
                    fn ($row) => [
                        'name' => $row->name,
                        'base_points' =>
                            (int) $row
                                ->base_points,
                        'submissions' =>
                            (int) $row
                                ->total_submissions,
                        'average_score' =>
                            round(
                                (float) $row
                                    ->average_score,
                                2,
                            ),
                    ],
                );

        $topChallenges =
            Challenge::query()
                ->with([
                    'category:id,name,slug',
                    'difficulty:id,name,slug',
                ])
                ->withCount('submissions')
                ->withCount([
                    'submissions as completed_submissions_count' =>
                        fn ($query) =>
                            $query->where(
                                'status',
                                'completed',
                            ),
                ])
                ->orderByDesc(
                    'submissions_count',
                )
                ->limit(10)
                ->get()
                ->map(
                    function (
                        Challenge $challenge
                    ): array {
                        $completionRate =
                            $challenge
                                ->submissions_count
                            > 0
                                ? round(
                                    $challenge
                                        ->completed_submissions_count
                                    / $challenge
                                        ->submissions_count
                                    * 100,
                                    2,
                                )
                                : 0;

                        return [
                            'id' =>
                                $challenge->id,
                            'title' =>
                                $challenge->title,
                            'slug' =>
                                $challenge->slug,
                            'category' =>
                                $challenge
                                    ->category
                                    ->name,
                            'difficulty' =>
                                $challenge
                                    ->difficulty
                                    ->name,
                            'submissions_count' =>
                                $challenge
                                    ->submissions_count,
                            'completed_count' =>
                                $challenge
                                    ->completed_submissions_count,
                            'completion_rate' =>
                                $completionRate,
                        ];
                    },
                );

        $topUsers =
            User::query()
                ->where('role', 'user')
                ->withCount('submissions')
                ->withCount([
                    'challengeProgress as completed_challenges' =>
                        fn ($query) =>
                            $query->where(
                                'is_completed',
                                true,
                            ),
                ])
                ->orderByDesc(
                    'total_points',
                )
                ->orderByDesc(
                    'completed_challenges',
                )
                ->limit(10)
                ->get([
                    'id',
                    'name',
                    'email',
                    'total_points',
                ]);

        return Inertia::render(
            'Admin/Statistics/Index',
            [
                'summary' => [
                    'users' =>
                        User::query()
                            ->where(
                                'role',
                                'user',
                            )
                            ->count(),
                    'challenges' =>
                        Challenge::query()
                            ->count(),
                    'submissions' =>
                        $totalSubmissions,
                    'completedSubmissions' =>
                        $completedSubmissions,
                    'completionRate' =>
                        $completionRate,
                    'averageScore' =>
                        $averageScore,
                ],
                'statusChart' =>
                    $statusChart,
                'dailySubmissions' =>
                    $dailySubmissions,
                'categoryPerformance' =>
                    $categoryPerformance,
                'difficultyPerformance' =>
                    $difficultyPerformance,
                'topChallenges' =>
                    $topChallenges,
                'topUsers' => $topUsers,
            ],
        );
    }
}
