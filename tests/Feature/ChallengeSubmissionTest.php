<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Submission;
use App\Models\User;
use App\Models\UserChallengeProgress;
use Database\Seeders\BugHuntSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ChallengeSubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->seed(
            BugHuntSeeder::class,
        );
    }

    public function test_correct_submission_is_stored_and_points_are_added(): void
    {
        $user = $this->user();
        $challenge = $this->challenge();

        $this->actingAs($user)
            ->post(
                route(
                    'challenges.submit',
                    $challenge,
                ),
                $this->correctPayload(
                    $challenge,
                ),
            )
            ->assertRedirect();

        $submission =
            Submission::query()
                ->firstOrFail();

        $this->assertSame(
            'completed',
            $submission->status,
        );

        $this->assertSame(
            $challenge->base_points,
            $submission->final_score,
        );

        $this->assertSame(
            $challenge->base_points,
            $user
                ->fresh()
                ->total_points,
        );

        $this->assertDatabaseHas(
            'user_challenge_progress',
            [
                'user_id' => $user->id,
                'challenge_id' => $challenge->id,
                'best_score' => $challenge
                    ->base_points,
                'is_completed' => true,
            ],
        );
    }

    public function test_repeated_submission_does_not_duplicate_best_points(): void
    {
        $user = $this->user();
        $challenge = $this->challenge();

        $payload =
            $this->correctPayload(
                $challenge,
            );

        $this->actingAs($user)
            ->post(
                route(
                    'challenges.submit',
                    $challenge,
                ),
                $payload,
            )
            ->assertRedirect();

        $this->actingAs($user)
            ->post(
                route(
                    'challenges.submit',
                    $challenge,
                ),
                $payload,
            )
            ->assertRedirect();

        $this->assertSame(
            $challenge->base_points,
            $user
                ->fresh()
                ->total_points,
        );

        $this->assertSame(
            2,
            Submission::query()->count(),
        );

        $progress =
            UserChallengeProgress::query()
                ->where(
                    'user_id',
                    $user->id,
                )
                ->where(
                    'challenge_id',
                    $challenge->id,
                )
                ->firstOrFail();

        $this->assertSame(
            2,
            $progress->attempts_count,
        );
    }

    public function test_opening_first_hint_reduces_final_score_by_ten_percent(): void
    {
        $user = $this->user();
        $challenge = $this->challenge();

        $hint = $challenge
            ->hints()
            ->orderBy('hint_order')
            ->firstOrFail();

        $this->actingAs($user)
            ->post(
                route(
                    'challenges.hints.store',
                    [
                        'challenge' => $challenge,
                        'hint' => $hint,
                    ],
                ),
            )
            ->assertRedirect();

        $this->actingAs($user)
            ->post(
                route(
                    'challenges.submit',
                    $challenge,
                ),
                $this->correctPayload(
                    $challenge,
                ),
            )
            ->assertRedirect();

        $expectedScore = (int) round(
            $challenge->base_points
            * 0.90,
        );

        $submission =
            Submission::query()
                ->firstOrFail();

        $this->assertSame(
            10,
            $submission->hint_penalty,
        );

        $this->assertSame(
            $expectedScore,
            $submission->final_score,
        );

        $this->assertSame(
            $expectedScore,
            $user
                ->fresh()
                ->total_points,
        );
    }

    public function test_incorrect_submission_does_not_complete_challenge(): void
    {
        $user = $this->user();
        $challenge = $this->challenge();

        $this->actingAs($user)
            ->post(
                route(
                    'challenges.submit',
                    $challenge,
                ),
                $this->incorrectPayload(
                    $challenge,
                ),
            )
            ->assertRedirect();

        $submission =
            Submission::query()
                ->firstOrFail();

        $this->assertNotSame(
            'completed',
            $submission->status,
        );

        $progress =
            UserChallengeProgress::query()
                ->where(
                    'user_id',
                    $user->id,
                )
                ->where(
                    'challenge_id',
                    $challenge->id,
                )
                ->firstOrFail();

        $this->assertFalse(
            $progress->is_completed,
        );
    }

    public function test_solution_is_hidden_before_challenge_is_completed(): void
    {
        $user = $this->user();
        $challenge = $this->challenge();

        $this->actingAs($user)
            ->post(
                route(
                    'challenges.submit',
                    $challenge,
                ),
                $this->incorrectPayload(
                    $challenge,
                ),
            )
            ->assertRedirect();

        $submission =
            Submission::query()
                ->firstOrFail();

        $this->actingAs($user)
            ->get(
                route(
                    'submissions.show',
                    $submission,
                ),
            )
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component(
                        'Submissions/Detail',
                    )
                    ->where(
                        'submission.challenge.can_view_solution',
                        false,
                    )
                    ->where(
                        'submission.challenge.buggy_line',
                        null,
                    )
                    ->where(
                        'submission.challenge.explanation',
                        null,
                    )
                    ->where(
                        'submission.challenge.primary_solution',
                        null,
                    )
                    ->where(
                        'submission.challenge.alternative_solutions',
                        [],
                    ),
            );
    }

    public function test_solution_is_visible_after_challenge_is_completed(): void
    {
        $user = $this->user();
        $challenge = $this->challenge();

        $this->actingAs($user)
            ->post(
                route(
                    'challenges.submit',
                    $challenge,
                ),
                $this->correctPayload(
                    $challenge,
                ),
            )
            ->assertRedirect();

        $submission =
            Submission::query()
                ->firstOrFail();

        $primary =
            $challenge
                ->solutions
                ->firstWhere(
                    'solution_type',
                    'primary',
                );

        $this->actingAs($user)
            ->get(
                route(
                    'submissions.show',
                    $submission,
                ),
            )
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component(
                        'Submissions/Detail',
                    )
                    ->where(
                        'submission.challenge.can_view_solution',
                        true,
                    )
                    ->where(
                        'submission.challenge.buggy_line',
                        $challenge->buggy_line,
                    )
                    ->where(
                        'submission.challenge.explanation',
                        $challenge->explanation,
                    )
                    ->where(
                        'submission.challenge.primary_solution',
                        $primary->solution_code,
                    ),
            );
    }

    private function user(): User
    {
        return User::query()
            ->where(
                'email',
                'user@bughunt.test',
            )
            ->firstOrFail();
    }

    private function challenge(): Challenge
    {
        return Challenge::query()
            ->with([
                'solutions',
                'hints',
            ])
            ->where(
                'slug',
                'javascript-perulangan-melewati-batas-array',
            )
            ->firstOrFail();
    }

    private function correctPayload(
        Challenge $challenge
    ): array {
        $primary =
            $challenge
                ->solutions
                ->firstWhere(
                    'solution_type',
                    'primary',
                );

        $keywords = collect(
            $primary
                ?->required_keywords
            ?? [],
        )->implode(' ');

        return [
            'selected_line' => $challenge->buggy_line,
            'submitted_code' => $primary->solution_code,
            'submitted_explanation' => "Kesalahan terjadi karena {$keywords}. Kondisi perulangan harus dihentikan sebelum indeks mencapai panjang array.",
        ];
    }

    private function incorrectPayload(
        Challenge $challenge
    ): array {
        return [
            'selected_line' => 1,
            'submitted_code' => $challenge->broken_code,
            'submitted_explanation' => 'Saya belum menemukan penyebab kesalahan pada kode ini.',
        ];
    }
}
