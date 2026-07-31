<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AdminStatisticsController extends Controller
{
    public function __invoke(): Response
    {
        $totalSubmissions = $this->getTotalSubmissions();
        $completedSubmissions = $this->getCompletedSubmissions();

        return Inertia::render(
            'Admin/Statistics/Index',
            [
                'summary' => [
                    'users' => User::query()
                        ->where('role', 'user')
                        ->count('*'),
                    'challenges' => Challenge::query()->count('*'),
                    'submissions' => $totalSubmissions,
                    'completedSubmissions' => $completedSubmissions,
                    'completionRate' => $this->calculateCompletionRate(
                        $completedSubmissions,
                        $totalSubmissions,
                    ),
                    'averageScore' => $this->getAverageScore(),
                ],
                'statusChart' => $this->getStatusChart(),
                'dailySubmissions' => $this->getDailySubmissions(),
                'categoryPerformance' => $this->getCategoryPerformance(),
                'difficultyPerformance' => $this->getDifficultyPerformance(),
                'topChallenges' => $this->getTopChallenges(),
                'topUsers' => $this->getTopUsers(),
            ],
        );
    }

    private function getTotalSubmissions(): int
    {
        return Submission::query()->count('*');
    }

    private function getCompletedSubmissions(): int
    {
        return Submission::query()
            ->where('status', 'completed')
            ->count('*');
    }

    private function calculateCompletionRate(
        int $completedSubmissions,
        int $totalSubmissions
    ): float {
        if ($totalSubmissions === 0) {
            return 0.0;
        }

        return round(
            $completedSubmissions / $totalSubmissions * 100,
            2,
        );
    }

    private function getAverageScore(): float
    {
        return round(
            (float) (Submission::query()->avg('final_score') ?? 0),
            2,
        );
    }

    private function getStatusChart(): Collection
    {
        $statusCounts = Submission::query()
            ->select([
                'status',
                DB::raw('COUNT(*) as total'),
            ])
            ->groupBy('status')
            ->pluck('total', 'status');

        return collect([
            'incorrect' => 'Belum tepat',
            'partially_correct' => 'Sebagian benar',
            'completed' => 'Selesai',
        ])->map(
            fn (string $label, string $status): array => [
                'name' => $label,
                'value' => (int) ($statusCounts[$status] ?? 0),
            ],
        )->values();
    }

    private function getDailySubmissions(): Collection
    {
        $startDate = Carbon::now()
            ->subDays(13)
            ->startOfDay();

        $dailyGroups = Submission::query()
            ->where('created_at', '>=', $startDate)
            ->get([
                'id',
                'status',
                'created_at',
            ])
            ->groupBy(
                fn (Submission $submission): string => $submission
                    ->created_at
                    ->format('Y-m-d'),
            );

        return collect(range(0, 13))->map(
            function (int $offset) use ($startDate, $dailyGroups): array {
                $date = $startDate
                    ->copy()
                    ->addDays($offset);

                $items = $dailyGroups->get(
                    $date->format('Y-m-d'),
                    collect(),
                );

                if (! $items instanceof Collection) {
                    $items = collect();
                }

                return [
                    'date' => $date->format('Y-m-d'),
                    'label' => $date->format('d M'),
                    'total' => $items->count(),
                    'completed' => $items
                        ->where('status', 'completed')
                        ->count(),
                ];
            },
        );
    }

    private function getCategoryPerformance(): Collection
    {
        return Submission::query()
            ->join(
                'challenges',
                'submissions.challenge_id',
                '=',
                'challenges.id',
                'inner',
                false,
            )
            ->join(
                'categories',
                'challenges.category_id',
                '=',
                'categories.id',
                'inner',
                false,
            )
            ->select([
                'categories.id',
                'categories.name',
                DB::raw('COUNT(submissions.id) as total_submissions'),
                DB::raw('AVG(submissions.final_score) as average_score'),
            ])
            ->groupBy(
                'categories.id',
                'categories.name',
            )
            ->orderBy('categories.name', 'asc')
            ->get()
            ->map(
                fn ($row): array => [
                    'name' => $row->name,
                    'submissions' => (int) $row->total_submissions,
                    'average_score' => round(
                        (float) $row->average_score,
                        2,
                    ),
                ],
            );
    }

    private function getDifficultyPerformance(): Collection
    {
        return Submission::query()
            ->join(
                'challenges',
                'submissions.challenge_id',
                '=',
                'challenges.id',
                'inner',
                false,
            )
            ->join(
                'difficulties',
                'challenges.difficulty_id',
                '=',
                'difficulties.id',
                'inner',
                false,
            )
            ->select([
                'difficulties.id',
                'difficulties.name',
                'difficulties.base_points',
                DB::raw('COUNT(submissions.id) as total_submissions'),
                DB::raw('AVG(submissions.final_score) as average_score'),
            ])
            ->groupBy(
                'difficulties.id',
                'difficulties.name',
                'difficulties.base_points',
            )
            ->orderBy('difficulties.base_points', 'asc')
            ->get()
            ->map(
                fn ($row): array => [
                    'name' => $row->name,
                    'base_points' => (int) $row->base_points,
                    'submissions' => (int) $row->total_submissions,
                    'average_score' => round(
                        (float) $row->average_score,
                        2,
                    ),
                ],
            );
    }

    private function getTopChallenges(): Collection
    {
        return Challenge::query()
            ->with([
                'category:id,name,slug',
                'difficulty:id,name,slug',
            ])
            ->withCount('submissions')
            ->withCount([
                'submissions as completed_submissions_count' => fn ($query) => $query->where(
                    'status',
                    'completed',
                ),
            ])
            ->orderByDesc('submissions_count')
            ->limit(10)
            ->get()
            ->map(
                function (Challenge $challenge): array {
                    $completionRate = $challenge->submissions_count > 0
                        ? round(
                            $challenge->completed_submissions_count
                            / $challenge->submissions_count
                            * 100,
                            2,
                        )
                        : 0;

                    return [
                        'id' => $challenge->id,
                        'title' => $challenge->title,
                        'slug' => $challenge->slug,
                        'category' => $challenge->category->name,
                        'difficulty' => $challenge->difficulty->name,
                        'submissions_count' => $challenge->submissions_count,
                        'completed_count' => $challenge->completed_submissions_count,
                        'completion_rate' => $completionRate,
                    ];
                },
            );
    }

    private function getTopUsers(): Collection
    {
        return User::query()
            ->where('role', 'user')
            ->withCount('submissions')
            ->withCount([
                'challengeProgress as completed_challenges' => fn ($query) => $query->where(
                    'is_completed',
                    true,
                ),
            ])
            ->orderByDesc('total_points')
            ->orderByDesc('completed_challenges')
            ->limit(10)
            ->get([
                'id',
                'name',
                'email',
                'total_points',
            ]);
    }
}
