<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\User;
use Database\Seeders\BugHuntSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BugHuntAuthorizationTest extends TestCase
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

    public function test_guest_is_redirected_when_opening_challenge_detail(): void
    {
        $challenge = Challenge::query()
            ->firstOrFail();

        $this->get(
            route(
                'challenges.show',
                $challenge,
            ),
        )->assertRedirect(
            route('login'),
        );
    }

    public function test_normal_user_cannot_access_admin_pages(): void
    {
        $user = User::query()
            ->where(
                'email',
                'rifqiuser@bughunt.test',
            )
            ->firstOrFail();

        $this->actingAs($user)
            ->get(
                route(
                    'admin.dashboard',
                ),
            )
            ->assertForbidden();

        $this->actingAs($user)
            ->get(
                route(
                    'admin.statistics.index',
                ),
            )
            ->assertForbidden();
    }

    public function test_admin_can_access_admin_pages(): void
    {
        $admin = User::query()
            ->where(
                'email',
                'admin@bughunt.test',
            )
            ->firstOrFail();

        $this->actingAs($admin)
            ->get(
                route(
                    'admin.dashboard',
                ),
            )
            ->assertOk();

        $this->actingAs($admin)
            ->get(
                route(
                    'admin.statistics.index',
                ),
            )
            ->assertOk();

        $this->actingAs($admin)
            ->get(
                route(
                    'admin.challenges.index',
                ),
            )
            ->assertOk();
    }

    public function test_user_can_access_user_dashboard(): void
    {
        $user = User::query()
            ->where(
                'email',
                'rifqiuser@bughunt.test',
            )
            ->firstOrFail();

        $this->actingAs($user)
            ->get(
                route('dashboard'),
            )
            ->assertOk();
    }
}
