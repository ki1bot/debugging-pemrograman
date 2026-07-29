<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Challenge;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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

        return Inertia::render('Admin/Dasbor', [
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
}
