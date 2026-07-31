<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AdminSubmissionController extends Controller
{
    public function index(Request $request): Response
    {
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
                fn ($query, string $status) => $query->where('status', $status),
            )
            ->when(
                $filters['challenge'] ?? null,
                fn ($query, int $id) => $query->where('challenge_id', $id),
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render(
            'Admin/Submissions/Index',
            [
                'submissions' => $submissions,
                'challenges' => Challenge::query()
                    ->orderBy('title', 'asc')
                    ->get(['id', 'title']),
                'filters' => [
                    'search' => $filters['search'] ?? '',
                    'status' => $filters['status'] ?? '',
                    'challenge' => $filters['challenge'] ?? '',
                ],
            ],
        );
    }

    public function show(
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
}
