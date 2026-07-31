<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AdminUserRoleRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AdminUserController extends Controller
{
    public function index(Request $request): Response
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
                'challengeProgress as completed_challenges' => fn ($query) => $query
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
                fn ($query, string $role) => $query->where('role', $role),
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

    public function update(
        AdminUserRoleRequest $request,
        User $user
    ): RedirectResponse {
        $validated = $request->validated();

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
}
