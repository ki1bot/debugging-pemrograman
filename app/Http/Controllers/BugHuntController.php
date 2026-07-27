<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Challenge;
use App\Models\ChallengeHint;
use App\Models\Difficulty;
use App\Models\Submission;
use App\Models\SubmissionAttempt;
use App\Models\User;
use App\Models\UserChallengeProgress;
use App\Services\ChallengeEvaluationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class BugHuntController extends Controller
{
    public function __construct(
        private readonly ChallengeEvaluationService $evaluationService
    ) {
    }

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
                ->whereIn('challenge_id', $featured->pluck('id'))
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
                fn (Challenge $challenge) => $this->challengeCard(
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

    public function challenges(Request $request): Response
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'difficulty' => ['nullable', 'string', 'max:100'],
        ]);

        $query = Challenge::query()
            ->with(['category', 'difficulty'])
            ->where('status', 'published')
            ->when(
                $filters['search'] ?? null,
                function ($query, string $search) {
                    $query->where(function ($nested) use ($search) {
                        $nested
                            ->where('title', 'ilike', "%{$search}%")
                            ->orWhere('description', 'ilike', "%{$search}%");
                    });
                },
            )
            ->when(
                $filters['category'] ?? null,
                function ($query, string $category) {
                    $query->whereHas(
                        'category',
                        fn ($categoryQuery) => $categoryQuery
                            ->where('slug', $category),
                    );
                },
            )
            ->when(
                $filters['difficulty'] ?? null,
                function ($query, string $difficulty) {
                    $query->whereHas(
                        'difficulty',
                        fn ($difficultyQuery) => $difficultyQuery
                            ->where('slug', $difficulty),
                    );
                },
            )
            ->latest();

        $paginator = $query
            ->paginate(12)
            ->withQueryString();

        $progress = $request->user()
            ? UserChallengeProgress::query()
                ->where('user_id', $request->user()->id)
                ->whereIn(
                    'challenge_id',
                    collect($paginator->items())->pluck('id'),
                )
                ->get()
                ->keyBy('challenge_id')
            : collect();

        $paginator->through(
            fn (Challenge $challenge) => $this->challengeCard(
                $challenge,
                $progress->get($challenge->id),
            ),
        );

        return Inertia::render('Challenges/Index', [
            'challenges' => $paginator,
            'categories' => Category::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
            'difficulties' => Difficulty::query()
                ->where('is_active', true)
                ->orderBy('base_points')
                ->get(),
            'filters' => [
                'search' => $filters['search'] ?? '',
                'category' => $filters['category'] ?? '',
                'difficulty' => $filters['difficulty'] ?? '',
            ],
        ]);
    }

    public function dashboard(Request $request): Response
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
                fn (UserChallengeProgress $progress) => $this
                    ->progressCard($progress),
            );

        $completedChallengeIds = UserChallengeProgress::query()
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

        $recommendedProgress = UserChallengeProgress::query()
            ->where('user_id', $user->id)
            ->whereIn('challenge_id', $recommended->pluck('id'))
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
                fn (Challenge $challenge) => $this->challengeCard(
                    $challenge,
                    $recommendedProgress->get($challenge->id),
                ),
            ),
        ]);
    }

    public function showChallenge(
        Request $request,
        Challenge $challenge
    ): Response {
        $this->ensureChallengeAccessible($request, $challenge);

        $challenge->load(['category', 'difficulty', 'hints']);

        $progress = UserChallengeProgress::query()->firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'challenge_id' => $challenge->id,
            ],
            [
                'unlocked_hint_ids' => [],
            ],
        );

        $unlockedIds = collect($progress->unlocked_hint_ids ?? [])
            ->map(fn ($id) => (int) $id);

        return Inertia::render('Challenges/Show', [
            'challenge' => [
                ...$this->challengeCard($challenge, $progress),
                'broken_code' => $challenge->broken_code,
                'line_count' => count(
                    preg_split(
                        '/\r\n|\r|\n/',
                        $challenge->broken_code,
                    ),
                ),
                'hints' => $challenge->hints->map(
                    function (ChallengeHint $hint) use ($unlockedIds) {
                        $unlocked = $unlockedIds->contains($hint->id);

                        return [
                            'id' => $hint->id,
                            'hint_order' => $hint->hint_order,
                            'point_penalty' => $hint->point_penalty,
                            'unlocked' => $unlocked,
                            'content' => $unlocked
                                ? $hint->content
                                : null,
                        ];
                    },
                ),
            ],
            'progress' => [
                'best_score' => $progress->best_score,
                'attempts_count' => $progress->attempts_count,
                'hints_used' => $progress->hints_used,
                'hint_penalty' => $progress->hint_penalty,
                'is_completed' => $progress->is_completed,
            ],
        ]);
    }

    public function unlockHint(
        Request $request,
        Challenge $challenge,
        ChallengeHint $hint
    ): RedirectResponse {
        $this->ensureChallengeAccessible($request, $challenge);

        if ($hint->challenge_id !== $challenge->id) {
            abort(404);
        }

        $progress = UserChallengeProgress::query()->firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'challenge_id' => $challenge->id,
            ],
            [
                'unlocked_hint_ids' => [],
            ],
        );

        $unlockedIds = collect($progress->unlocked_hint_ids ?? [])
            ->map(fn ($id) => (int) $id);

        if ($unlockedIds->contains($hint->id)) {
            return back()->with(
                'info',
                'Hint tersebut sudah dibuka.',
            );
        }

        $previousHintIds = $challenge
            ->hints()
            ->where('hint_order', '<', $hint->hint_order)
            ->pluck('id');

        if ($previousHintIds->diff($unlockedIds)->isNotEmpty()) {
            return back()->with(
                'error',
                'Buka hint secara berurutan.',
            );
        }

        $unlockedIds->push($hint->id);

        $progress->update([
            'unlocked_hint_ids' => $unlockedIds
                ->unique()
                ->values()
                ->all(),
            'hints_used' => $unlockedIds->unique()->count(),
            'hint_penalty' => min(
                100,
                $progress->hint_penalty + $hint->point_penalty,
            ),
        ]);

        return back()->with(
            'success',
            'Hint berhasil dibuka. Penalti poin telah diterapkan.',
        );
    }

    public function submitChallenge(
        Request $request,
        Challenge $challenge
    ): RedirectResponse {
        $this->ensureChallengeAccessible($request, $challenge);

        $challenge->load('solutions');

        $lineCount = count(
            preg_split('/\r\n|\r|\n/', $challenge->broken_code),
        );

        $validated = $request->validate([
            'selected_line' => [
                'required',
                'integer',
                'min:1',
                "max:{$lineCount}",
            ],
            'submitted_code' => [
                'required',
                'string',
                'max:20000',
            ],
            'submitted_explanation' => [
                'required',
                'string',
                'min:20',
                'max:3000',
            ],
        ]);

        $submission = DB::transaction(
            function () use ($request, $challenge, $validated) {
                $user = User::query()
                    ->lockForUpdate()
                    ->findOrFail($request->user()->id);

                $progress = UserChallengeProgress::query()
                    ->where('user_id', $user->id)
                    ->where('challenge_id', $challenge->id)
                    ->lockForUpdate()
                    ->first();

                if (! $progress) {
                    $progress = UserChallengeProgress::query()->create([
                        'user_id' => $user->id,
                        'challenge_id' => $challenge->id,
                        'unlocked_hint_ids' => [],
                    ]);
                }

                $result = $this->evaluationService->evaluate(
                    $challenge,
                    (int) $validated['selected_line'],
                    $validated['submitted_code'],
                    $validated['submitted_explanation'],
                    $progress->hint_penalty,
                );

                $submission = Submission::query()->create([
                    'user_id' => $user->id,
                    'challenge_id' => $challenge->id,
                    'selected_line' => $validated['selected_line'],
                    'submitted_code' => $validated['submitted_code'],
                    'submitted_explanation' =>
                        $validated['submitted_explanation'],
                    'line_score' => $result['line_score'],
                    'code_score' => $result['code_score'],
                    'explanation_score' =>
                        $result['explanation_score'],
                    'hint_penalty' => $result['hint_penalty'],
                    'final_score' => $result['final_score'],
                    'status' => $result['status'],
                    'completed_at' => $result['status'] === 'completed'
                        ? now()
                        : null,
                ]);

                SubmissionAttempt::query()->create([
                    'submission_id' => $submission->id,
                    'attempt_number' =>
                        $progress->attempts_count + 1,
                    'line_correct' => $result['line_score'] > 0,
                    'code_correct' => $result['code_score'] > 0,
                    'matched_keywords' =>
                        $result['matched_keywords'],
                    'missing_keywords' =>
                        $result['missing_keywords'],
                    'score_snapshot' => $result['final_score'],
                    'status_snapshot' => $result['status'],
                ]);

                $previousBest = $progress->best_score;
                $newBest = max(
                    $previousBest,
                    $result['final_score'],
                );

                $becameCompleted =
                    ! $progress->is_completed
                    && $result['status'] === 'completed';

                $progress->update([
                    'best_submission_id' =>
                        $newBest > $previousBest
                            ? $submission->id
                            : $progress->best_submission_id,
                    'best_score' => $newBest,
                    'attempts_count' =>
                        $progress->attempts_count + 1,
                    'is_completed' =>
                        $progress->is_completed
                        || $result['status'] === 'completed',
                    'completed_at' => $becameCompleted
                        ? now()
                        : $progress->completed_at,
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

        return redirect()
            ->route('submissions.show', $submission)
            ->with('success', 'Jawaban berhasil diperiksa.');
    }

    public function submission(
        Request $request,
        Submission $submission
    ): Response {
        if (
            $submission->user_id !== $request->user()->id
            && ! $request->user()->isAdmin()
        ) {
            abort(403);
        }

        $submission->load([
            'challenge.category',
            'challenge.difficulty',
            'challenge.solutions',
            'attempts',
        ]);

        $primary = $submission->challenge->solutions
            ->firstWhere('solution_type', 'primary')
            ?? $submission->challenge->solutions->first();

        return Inertia::render('Submissions/Show', [
            'submission' => [
                'id' => $submission->id,
                'selected_line' => $submission->selected_line,
                'submitted_code' => $submission->submitted_code,
                'submitted_explanation' =>
                    $submission->submitted_explanation,
                'line_score' => $submission->line_score,
                'code_score' => $submission->code_score,
                'explanation_score' =>
                    $submission->explanation_score,
                'hint_penalty' => $submission->hint_penalty,
                'final_score' => $submission->final_score,
                'status' => $submission->status,
                'completed_at' => $submission->completed_at,
                'attempts_count' =>
                    $submission->attempts->first()?->attempt_number
                    ?? 1,
                'challenge' => [
                    ...$this->challengeCard(
                        $submission->challenge,
                    ),
                    'broken_code' =>
                        $submission->challenge->broken_code,
                    'buggy_line' =>
                        $submission->challenge->buggy_line,
                    'explanation' =>
                        $submission->challenge->explanation,
                    'primary_solution' =>
                        $primary?->solution_code,
                    'alternative_solutions' =>
                        $submission->challenge->solutions
                            ->where(
                                'solution_type',
                                'alternative',
                            )
                            ->pluck('solution_code')
                            ->values(),
                ],
            ],
        ]);
    }

    public function history(Request $request): Response
    {
        $submissions = Submission::query()
            ->with([
                'challenge.category',
                'challenge.difficulty',
            ])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('History/Index', [
            'submissions' => $submissions,
        ]);
    }

    public function leaderboard(): Response
    {
        $leaders = User::query()
            ->where('role', 'user')
            ->withCount([
                'challengeProgress as completed_challenges' =>
                    fn ($query) => $query
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
                    'completed_challenges' =>
                        $user->completed_challenges,
                    'joined_at' => $user->created_at,
                ],
            );

        return Inertia::render('Leaderboard', [
            'leaders' => $leaders,
        ]);
    }

    private function ensureChallengeAccessible(
        Request $request,
        Challenge $challenge
    ): void {
        if (
            $challenge->status !== 'published'
            && ! $request->user()?->isAdmin()
        ) {
            abort(404);
        }
    }

    private function challengeCard(
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
                'base_points' =>
                    $challenge->difficulty->base_points,
            ],
            'progress' => $progress
                ? [
                    'best_score' => $progress->best_score,
                    'attempts_count' =>
                        $progress->attempts_count,
                    'is_completed' =>
                        $progress->is_completed,
                ]
                : null,
        ];
    }

    private function progressCard(
        UserChallengeProgress $progress
    ): array {
        return [
            'id' => $progress->id,
            'best_score' => $progress->best_score,
            'attempts_count' => $progress->attempts_count,
            'hints_used' => $progress->hints_used,
            'is_completed' => $progress->is_completed,
            'completed_at' => $progress->completed_at,
            'updated_at' => $progress->updated_at,
            'submission_id' =>
                $progress->bestSubmission?->id,
            'latest_status' =>
                $progress->bestSubmission?->status,
            'latest_score' =>
                $progress->bestSubmission?->final_score,
            'challenge' => $this->challengeCard(
                $progress->challenge,
                $progress,
            ),
        ];
    }
}
