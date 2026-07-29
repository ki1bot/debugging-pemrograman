<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AdminChallengeRequest;
use App\Models\Category;
use App\Models\Challenge;
use App\Models\Difficulty;
use App\Services\AdminChallengeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AdminChallengeController extends Controller
{
    public function __construct(
        private readonly AdminChallengeService $challengeService
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
                'integer',
                'exists:categories,id',
            ],
            'difficulty' => [
                'nullable',
                'integer',
                'exists:difficulties,id',
            ],
            'status' => [
                'nullable',
                Rule::in([
                    'draft',
                    'published',
                    'inactive',
                ]),
            ],
        ]);

        $challenges = Challenge::query()
            ->with([
                'category',
                'difficulty',
                'creator:id,name',
            ])
            ->withCount([
                'submissions',
                'solutions',
                'hints',
            ])
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
                fn ($query, int $id) => $query->where('category_id', $id),
            )
            ->when(
                $filters['difficulty'] ?? null,
                fn ($query, int $id) => $query->where('difficulty_id', $id),
            )
            ->when(
                $filters['status'] ?? null,
                fn ($query, string $status) => $query->where('status', $status),
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Challenges/Daftar', [
            'challenges' => $challenges,
            'categories' => Category::query()
                ->orderBy('name', 'asc')
                ->get(),
            'difficulties' => Difficulty::query()
                ->orderBy('base_points', 'asc')
                ->get(),
            'filters' => [
                'search' => $filters['search'] ?? '',
                'category' => $filters['category'] ?? '',
                'difficulty' => $filters['difficulty'] ?? '',
                'status' => $filters['status'] ?? '',
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render(
            'Admin/Challenges/Tambah',
            [
                'categories' => Category::query()
                    ->where('is_active', true)
                    ->orderBy('name', 'asc')
                    ->get(),
                'difficulties' => Difficulty::query()
                    ->where('is_active', true)
                    ->orderBy('base_points', 'asc')
                    ->get(),
            ],
        );
    }

    public function store(
        AdminChallengeRequest $request
    ): RedirectResponse {
        $challenge = $this->challengeService->create(
            $request->user(),
            $request->validated(),
        );

        return redirect()
            ->route('admin.challenges.edit', $challenge)
            ->with(
                'success',
                'Tantangan berhasil ditambahkan.',
            );
    }

    public function edit(
        Challenge $challenge
    ): Response {
        $challenge->load([
            'category',
            'difficulty',
            'hints',
            'solutions',
        ]);

        return Inertia::render('Admin/Challenges/Ubah', [
            'challenge' => $challenge,
            'categories' => Category::query()
                ->orderBy('name', 'asc')
                ->get(),
            'difficulties' => Difficulty::query()
                ->orderBy('base_points', 'asc')
                ->get(),
        ]);
    }

    public function update(
        AdminChallengeRequest $request,
        Challenge $challenge
    ): RedirectResponse {
        $this->challengeService->update(
            $challenge,
            $request->validated(),
        );

        return back()->with(
            'success',
            'Tantangan berhasil diperbarui.',
        );
    }

    public function destroy(
        Challenge $challenge
    ): RedirectResponse {
        Challenge::destroy((int) $challenge->getKey());

        return redirect()
            ->route('admin.challenges.index')
            ->with(
                'success',
                'Tantangan berhasil dinonaktifkan dan dipindahkan ke arsip.',
            );
    }
}
