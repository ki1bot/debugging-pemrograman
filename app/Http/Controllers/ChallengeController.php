<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Challenge;
use App\Models\ChallengeHint;
use App\Models\Difficulty;
use App\Models\UserChallengeProgress;
use App\Services\ChallengeAccessService;
use App\Services\ChallengePresenter;
use App\Services\ChallengeProgressService;
use App\Services\ChallengeSubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChallengeController extends Controller
{
    public function __construct(
        private readonly ChallengePresenter $presenter,
        private readonly ChallengeAccessService $accessService,
        private readonly ChallengeProgressService $progressService,
        private readonly ChallengeSubmissionService $submissionService
    ) {}

    public function index(Request $request): Response
    {
        $filters = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:100',
            ],
            'category' => [
                'nullable',
                'string',
                'max:100',
            ],
            'difficulty' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        $query = Challenge::query()
            ->with(['category', 'difficulty'])
            ->where('status', 'published')
            ->when(
                $filters['search'] ?? null,
                function ($query, string $search) {
                    $query->where(
                        function ($nested) use ($search) {
                            $nested
                                ->where(
                                    'title',
                                    'ilike',
                                    "%{$search}%",
                                )
                                ->orWhere(
                                    'description',
                                    'ilike',
                                    "%{$search}%",
                                );
                        },
                    );
                },
            )
            ->when(
                $filters['category'] ?? null,
                function ($query, string $category) {
                    $query->whereHas(
                        'category',
                        fn ($categoryQuery) => $categoryQuery->where(
                            'slug',
                            $category,
                        ),
                    );
                },
            )
            ->when(
                $filters['difficulty'] ?? null,
                function ($query, string $difficulty) {
                    $query->whereHas(
                        'difficulty',
                        fn ($difficultyQuery) => $difficultyQuery->where(
                            'slug',
                            $difficulty,
                        ),
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
                    collect(
                        $paginator->items(),
                    )->pluck('id'),
                )
                ->get()
                ->keyBy('challenge_id')
            : collect();

        $paginator->through(
            fn (Challenge $challenge) => $this->presenter->challenge(
                $challenge,
                $progress->get($challenge->id),
            ),
        );

        return Inertia::render('Challenges/Daftar', [
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

    public function show(
        Request $request,
        Challenge $challenge
    ): Response {
        $this->accessService->ensureAccessible(
            $request,
            $challenge,
        );

        $challenge->load([
            'category',
            'difficulty',
            'hints',
        ]);

        $progress = $this->progressService->findOrCreate(
            $request->user()->id,
            $challenge->id,
        );

        $unlockedIds = collect(
            $progress->unlocked_hint_ids ?? [],
        )->map(fn ($id) => (int) $id);

        return Inertia::render('Challenges/Detail', [
            'challenge' => [
                ...$this->presenter->challenge(
                    $challenge,
                    $progress,
                ),
                'broken_code' => $challenge->broken_code,
                'line_count' => count(
                    preg_split(
                        '/\r\n|\r|\n/',
                        $challenge->broken_code,
                    ),
                ),
                'hints' => $challenge->hints->map(
                    function (
                        ChallengeHint $hint
                    ) use ($unlockedIds) {
                        $unlocked = $unlockedIds
                            ->contains($hint->id);

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
                'best_score' => (int) ($progress->best_score ?? 0),
                'attempts_count' => (int) ($progress->attempts_count ?? 0),
                'hints_used' => (int) ($progress->hints_used ?? 0),
                'hint_penalty' => (int) ($progress->hint_penalty ?? 0),
                'is_completed' => (bool) ($progress->is_completed ?? false),
            ],
        ]);
    }

    public function unlockHint(
        Request $request,
        Challenge $challenge,
        ChallengeHint $hint
    ): RedirectResponse {
        $this->accessService->ensureAccessible(
            $request,
            $challenge,
        );

        if ($hint->challenge_id !== $challenge->id) {
            abort(404);
        }

        $progress = $this->progressService->findOrCreate(
            $request->user()->id,
            $challenge->id,
        );

        $unlockedIds = collect(
            $progress->unlocked_hint_ids ?? [],
        )->map(fn ($id) => (int) $id);

        if ($unlockedIds->contains($hint->id)) {
            return back()->with(
                'info',
                'Hint tersebut sudah dibuka.',
            );
        }

        $previousHintIds = $challenge
            ->hints()
            ->where(
                'hint_order',
                '<',
                $hint->hint_order,
            )
            ->pluck('id');

        if (
            $previousHintIds
                ->diff($unlockedIds)
                ->isNotEmpty()
        ) {
            return back()->with(
                'error',
                'Buka hint secara berurutan.',
            );
        }

        $unlockedIds->push($hint->id);

        $uniqueUnlockedIds = $unlockedIds
            ->unique()
            ->values();

        $progress->update([
            'unlocked_hint_ids' => $uniqueUnlockedIds->all(),
            'hints_used' => $uniqueUnlockedIds->count(),
            'hint_penalty' => min(
                100,
                (int) ($progress->hint_penalty ?? 0)
                    + (int) $hint->point_penalty,
            ),
        ]);

        return back()->with(
            'success',
            'Hint berhasil dibuka. Penalti poin telah diterapkan.',
        );
    }

    public function submit(
        Request $request,
        Challenge $challenge
    ): RedirectResponse {
        $this->accessService->ensureAccessible(
            $request,
            $challenge,
        );

        $challenge->load('solutions');

        $lineCount = count(
            preg_split(
                '/\r\n|\r|\n/',
                $challenge->broken_code,
            ),
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

        $submission = $this->submissionService->submit(
            $request->user(),
            $challenge,
            $validated,
        );

        return redirect()
            ->route('submissions.show', $submission)
            ->with(
                'success',
                'Jawaban berhasil diperiksa.',
            );
    }
}
