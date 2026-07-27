<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Challenge;
use App\Models\Difficulty;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function dashboard(): Response
    {
        $statusChart = Submission::query()
            ->select(
                'status',
                DB::raw('COUNT(*) as total'),
            )
            ->groupBy('status')
            ->orderBy('status')
            ->get()
            ->map(
                fn ($row) => [
                    'name' => str_replace(
                        '_',
                        ' ',
                        ucfirst($row->status),
                    ),
                    'value' => (int) $row->total,
                ],
            );

        $categoryChart = Category::query()
            ->withCount('challenges')
            ->orderBy('name')
            ->get()
            ->map(
                fn (Category $category) => [
                    'name' => $category->name,
                    'value' => $category->challenges_count,
                ],
            );

        $recentSubmissions = Submission::query()
            ->with([
                'user:id,name,email',
                'challenge:id,title,slug',
            ])
            ->latest()
            ->limit(8)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'summary' => [
                'users' => User::query()
                    ->where('role', 'user')
                    ->count(),
                'admins' => User::query()
                    ->where('role', 'admin')
                    ->count(),
                'challenges' => Challenge::query()->count(),
                'publishedChallenges' => Challenge::query()
                    ->where('status', 'published')
                    ->count(),
                'submissions' => Submission::query()->count(),
                'completedSubmissions' => Submission::query()
                    ->where('status', 'completed')
                    ->count(),
            ],
            'statusChart' => $statusChart,
            'categoryChart' => $categoryChart,
            'recentSubmissions' => $recentSubmissions,
        ]);
    }

    public function categories(): Response
    {
        return Inertia::render('Admin/Categories/Index', [
            'categories' => Category::query()
                ->withCount('challenges')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function storeCategory(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:categories,name',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:100',
                'alpha_dash',
                'unique:categories,slug',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        Category::query()->create([
            'name' => $validated['name'],
            'slug' => $validated['slug']
                ?: Str::slug($validated['name']),
            'description' =>
                $validated['description'] ?? null,
            'is_active' => $validated['is_active'],
        ]);

        return back()->with(
            'success',
            'Kategori berhasil ditambahkan.',
        );
    }

    public function updateCategory(
        Request $request,
        Category $category
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name')
                    ->ignore($category->id),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('categories', 'slug')
                    ->ignore($category->id),
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => $validated['slug']
                ?: Str::slug($validated['name']),
            'description' =>
                $validated['description'] ?? null,
            'is_active' => $validated['is_active'],
        ]);

        return back()->with(
            'success',
            'Kategori berhasil diperbarui.',
        );
    }

    public function destroyCategory(
        Category $category
    ): RedirectResponse {
        if ($category->challenges()->exists()) {
            return back()->with(
                'error',
                'Kategori tidak dapat dihapus karena masih digunakan oleh tantangan.',
            );
        }

        $category->delete();

        return back()->with(
            'success',
            'Kategori berhasil dihapus.',
        );
    }

    public function difficulties(): Response
    {
        return Inertia::render('Admin/Difficulties/Index', [
            'difficulties' => Difficulty::query()
                ->withCount('challenges')
                ->orderBy('base_points')
                ->get(),
        ]);
    }

    public function storeDifficulty(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:difficulties,name',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:100',
                'alpha_dash',
                'unique:difficulties,slug',
            ],
            'base_points' => [
                'required',
                'integer',
                'min:10',
                'max:1000',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        Difficulty::query()->create([
            'name' => $validated['name'],
            'slug' => $validated['slug']
                ?: Str::slug($validated['name']),
            'base_points' => $validated['base_points'],
            'is_active' => $validated['is_active'],
        ]);

        return back()->with(
            'success',
            'Tingkat kesulitan berhasil ditambahkan.',
        );
    }

    public function updateDifficulty(
        Request $request,
        Difficulty $difficulty
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('difficulties', 'name')
                    ->ignore($difficulty->id),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('difficulties', 'slug')
                    ->ignore($difficulty->id),
            ],
            'base_points' => [
                'required',
                'integer',
                'min:10',
                'max:1000',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        $difficulty->update([
            'name' => $validated['name'],
            'slug' => $validated['slug']
                ?: Str::slug($validated['name']),
            'base_points' => $validated['base_points'],
            'is_active' => $validated['is_active'],
        ]);

        return back()->with(
            'success',
            'Tingkat kesulitan berhasil diperbarui.',
        );
    }

    public function destroyDifficulty(
        Difficulty $difficulty
    ): RedirectResponse {
        if ($difficulty->challenges()->exists()) {
            return back()->with(
                'error',
                'Tingkat kesulitan tidak dapat dihapus karena masih digunakan oleh tantangan.',
            );
        }

        $difficulty->delete();

        return back()->with(
            'success',
            'Tingkat kesulitan berhasil dihapus.',
        );
    }

    public function challenges(Request $request): Response
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
                fn ($query, int $id) =>
                    $query->where('category_id', $id),
            )
            ->when(
                $filters['difficulty'] ?? null,
                fn ($query, int $id) =>
                    $query->where('difficulty_id', $id),
            )
            ->when(
                $filters['status'] ?? null,
                fn ($query, string $status) =>
                    $query->where('status', $status),
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Challenges/Index', [
            'challenges' => $challenges,
            'categories' => Category::query()
                ->orderBy('name')
                ->get(),
            'difficulties' => Difficulty::query()
                ->orderBy('base_points')
                ->get(),
            'filters' => [
                'search' => $filters['search'] ?? '',
                'category' => $filters['category'] ?? '',
                'difficulty' =>
                    $filters['difficulty'] ?? '',
                'status' => $filters['status'] ?? '',
            ],
        ]);
    }

    public function createChallenge(): Response
    {
        return Inertia::render(
            'Admin/Challenges/Create',
            [
                'categories' => Category::query()
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get(),
                'difficulties' => Difficulty::query()
                    ->where('is_active', true)
                    ->orderBy('base_points')
                    ->get(),
            ],
        );
    }

    public function storeChallenge(
        Request $request
    ): RedirectResponse {
        $validated = $this->validateChallenge($request);

        $challenge = DB::transaction(
            function () use ($request, $validated) {
                $challenge = Challenge::query()->create([
                    'category_id' =>
                        $validated['category_id'],
                    'difficulty_id' =>
                        $validated['difficulty_id'],
                    'title' => $validated['title'],
                    'slug' => $validated['slug']
                        ?: $this->uniqueChallengeSlug(
                            $validated['title'],
                        ),
                    'description' =>
                        $validated['description'],
                    'broken_code' =>
                        $validated['broken_code'],
                    'buggy_line' =>
                        $validated['buggy_line'],
                    'explanation' =>
                        $validated['explanation'],
                    'base_points' =>
                        $validated['base_points'],
                    'status' => $validated['status'],
                    'created_by' =>
                        $request->user()->id,
                ]);

                $this->syncChallengeRelations(
                    $challenge,
                    $validated,
                );

                return $challenge;
            },
        );

        return redirect()
            ->route('admin.challenges.edit', $challenge)
            ->with(
                'success',
                'Tantangan berhasil ditambahkan.',
            );
    }

    public function editChallenge(
        Challenge $challenge
    ): Response {
        $challenge->load([
            'category',
            'difficulty',
            'hints',
            'solutions',
        ]);

        return Inertia::render('Admin/Challenges/Edit', [
            'challenge' => $challenge,
            'categories' => Category::query()
                ->orderBy('name')
                ->get(),
            'difficulties' => Difficulty::query()
                ->orderBy('base_points')
                ->get(),
        ]);
    }

    public function updateChallenge(
        Request $request,
        Challenge $challenge
    ): RedirectResponse {
        $validated = $this->validateChallenge(
            $request,
            $challenge,
        );

        DB::transaction(
            function () use ($challenge, $validated) {
                $challenge->update([
                    'category_id' =>
                        $validated['category_id'],
                    'difficulty_id' =>
                        $validated['difficulty_id'],
                    'title' => $validated['title'],
                    'slug' => $validated['slug']
                        ?: $challenge->slug,
                    'description' =>
                        $validated['description'],
                    'broken_code' =>
                        $validated['broken_code'],
                    'buggy_line' =>
                        $validated['buggy_line'],
                    'explanation' =>
                        $validated['explanation'],
                    'base_points' =>
                        $validated['base_points'],
                    'status' => $validated['status'],
                ]);

                $this->syncChallengeRelations(
                    $challenge,
                    $validated,
                );
            },
        );

        return back()->with(
            'success',
            'Tantangan berhasil diperbarui.',
        );
    }

    public function destroyChallenge(
        Challenge $challenge
    ): RedirectResponse {
        $challenge->delete();

        return redirect()
            ->route('admin.challenges.index')
            ->with(
                'success',
                'Tantangan berhasil dinonaktifkan dan dipindahkan ke arsip.',
            );
    }

    public function users(Request $request): Response
    {
        $filters = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:100',
            ],
            'role' => [
                'nullable',
                Rule::in(['user', 'admin']),
            ],
        ]);

        $users = User::query()
            ->withCount([
                'submissions',
                'challengeProgress as completed_challenges' =>
                    fn ($query) => $query
                        ->where('is_completed', true),
            ])
            ->when(
                $filters['search'] ?? null,
                function ($query, string $search) {
                    $query->where(
                        function ($nested) use ($search) {
                            $nested
                                ->where(
                                    'name',
                                    'ilike',
                                    "%{$search}%",
                                )
                                ->orWhere(
                                    'email',
                                    'ilike',
                                    "%{$search}%",
                                );
                        },
                    );
                },
            )
            ->when(
                $filters['role'] ?? null,
                fn ($query, string $role) =>
                    $query->where('role', $role),
            )
            ->orderByDesc('total_points')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => [
                'search' => $filters['search'] ?? '',
                'role' => $filters['role'] ?? '',
            ],
        ]);
    }

    public function updateUser(
        Request $request,
        User $user
    ): RedirectResponse {
        $validated = $request->validate([
            'role' => [
                'required',
                Rule::in(['user', 'admin']),
            ],
        ]);

        if (
            $request->user()->id === $user->id
            && $validated['role'] !== 'admin'
        ) {
            return back()->with(
                'error',
                'Anda tidak dapat menurunkan role akun admin yang sedang digunakan.',
            );
        }

        $user->update([
            'role' => $validated['role'],
        ]);

        return back()->with(
            'success',
            'Role pengguna berhasil diperbarui.',
        );
    }

    public function submissions(
        Request $request
    ): Response {
        $filters = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:100',
            ],
            'status' => [
                'nullable',
                Rule::in([
                    'incorrect',
                    'partially_correct',
                    'completed',
                ]),
            ],
            'challenge' => [
                'nullable',
                'integer',
                'exists:challenges,id',
            ],
        ]);

        $submissions = Submission::query()
            ->with([
                'user:id,name,email,role,total_points',
                'challenge.category',
                'challenge.difficulty',
            ])
            ->when(
                $filters['search'] ?? null,
                function ($query, string $search) {
                    $query->whereHas(
                        'user',
                        function ($userQuery) use ($search) {
                            $userQuery
                                ->where(
                                    'name',
                                    'ilike',
                                    "%{$search}%",
                                )
                                ->orWhere(
                                    'email',
                                    'ilike',
                                    "%{$search}%",
                                );
                        },
                    );
                },
            )
            ->when(
                $filters['status'] ?? null,
                fn ($query, string $status) =>
                    $query->where('status', $status),
            )
            ->when(
                $filters['challenge'] ?? null,
                fn ($query, int $id) =>
                    $query->where('challenge_id', $id),
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render(
            'Admin/Submissions/Index',
            [
                'submissions' => $submissions,
                'challenges' => Challenge::query()
                    ->orderBy('title')
                    ->get(['id', 'title']),
                'filters' => [
                    'search' => $filters['search'] ?? '',
                    'status' => $filters['status'] ?? '',
                    'challenge' =>
                        $filters['challenge'] ?? '',
                ],
            ],
        );
    }

    public function showSubmission(
        Submission $submission
    ): Response {
        $submission->load([
            'user',
            'challenge.category',
            'challenge.difficulty',
            'challenge.solutions',
            'attempts',
        ]);

        return Inertia::render(
            'Admin/Submissions/Show',
            [
                'submission' => $submission,
            ],
        );
    }

    private function validateChallenge(
        Request $request,
        ?Challenge $challenge = null
    ): array {
        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'difficulty_id' => [
                'required',
                'integer',
                'exists:difficulties,id',
            ],
            'title' => [
                'required',
                'string',
                'max:150',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:180',
                'alpha_dash',
                Rule::unique('challenges', 'slug')
                    ->ignore($challenge?->id),
            ],
            'description' => [
                'required',
                'string',
                'max:2000',
            ],
            'broken_code' => [
                'required',
                'string',
                'max:20000',
            ],
            'buggy_line' => [
                'required',
                'integer',
                'min:1',
            ],
            'explanation' => [
                'required',
                'string',
                'max:5000',
            ],
            'base_points' => [
                'required',
                'integer',
                'min:10',
                'max:1000',
            ],
            'status' => [
                'required',
                Rule::in([
                    'draft',
                    'published',
                    'inactive',
                ]),
            ],
            'hints' => [
                'required',
                'array',
                'min:1',
                'max:5',
            ],
            'hints.*.content' => [
                'required',
                'string',
                'max:2000',
            ],
            'hints.*.point_penalty' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'solutions' => [
                'required',
                'array',
                'min:1',
                'max:10',
            ],
            'solutions.*.solution_code' => [
                'required',
                'string',
                'max:20000',
            ],
            'solutions.*.solution_type' => [
                'required',
                Rule::in([
                    'primary',
                    'alternative',
                ]),
            ],
            'solutions.*.required_keywords' => [
                'nullable',
                'array',
                'max:20',
            ],
            'solutions.*.required_keywords.*' => [
                'string',
                'max:100',
            ],
        ]);

        $lineCount = count(
            preg_split(
                '/\r\n|\r|\n/',
                $validated['broken_code'],
            ),
        );

        if ($validated['buggy_line'] > $lineCount) {
            throw ValidationException::withMessages([
                'buggy_line' =>
                    'Nomor baris bug melebihi jumlah baris kode.',
            ]);
        }

        if (
            collect($validated['solutions'])
                ->where('solution_type', 'primary')
                ->count() !== 1
        ) {
            throw ValidationException::withMessages([
                'solutions' =>
                    'Tantangan harus memiliki tepat satu solusi utama.',
            ]);
        }

        return $validated;
    }

    private function syncChallengeRelations(
        Challenge $challenge,
        array $validated
    ): void {
        $challenge->hints()->delete();
        $challenge->solutions()->delete();

        foreach (
            array_values($validated['hints'])
            as $index => $hint
        ) {
            $challenge->hints()->create([
                'hint_order' => $index + 1,
                'content' => $hint['content'],
                'point_penalty' =>
                    $hint['point_penalty'],
            ]);
        }

        foreach ($validated['solutions'] as $solution) {
            $challenge->solutions()->create([
                'solution_code' =>
                    $solution['solution_code'],
                'solution_type' =>
                    $solution['solution_type'],
                'required_keywords' => collect(
                    $solution['required_keywords'] ?? [],
                )
                    ->map(
                        fn ($keyword) => mb_strtolower(
                            trim((string) $keyword),
                        ),
                    )
                    ->filter()
                    ->unique()
                    ->values()
                    ->all(),
            ]);
        }
    }

    private function uniqueChallengeSlug(
        string $title
    ): string {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 2;

        while (
            Challenge::withTrashed()
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
