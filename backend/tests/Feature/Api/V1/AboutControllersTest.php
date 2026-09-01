<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AboutControllersTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_timeline_returns_ordered_milestones(): void
    {
        $response = $this->getJson('/api/v1/timeline-milestones');

        $response->assertOk()->assertJsonCount(5, 'data')
            ->assertJsonPath('data.0.year', '1994')
            ->assertJsonStructure(['data' => [['id', 'year', 'title', 'body']]]);
    }

    public function test_team_members_filterable_by_group(): void
    {
        $board = $this->getJson('/api/v1/team-members?filters[group]=board');
        $leadership = $this->getJson('/api/v1/team-members?filters[group]=leadership');
        $all = $this->getJson('/api/v1/team-members');

        $board->assertOk()->assertJsonCount(7, 'data');
        $leadership->assertOk()->assertJsonCount(6, 'data');
        $all->assertOk()->assertJsonCount(13, 'data');
    }

    public function test_team_members_invalid_group_fails_validation(): void
    {
        $this->getJson('/api/v1/team-members?filters[group]=bogus')->assertStatus(422);
    }

    public function test_committees_return_localized_responsibilities(): void
    {
        $response = $this->withHeaders(['Accept-Language' => 'ar'])->getJson('/api/v1/committees');

        $response->assertOk()->assertJsonCount(4, 'data')
            ->assertJsonPath('data.0.name', 'لجنة المراجعة');

        $this->assertIsString($response->json('data.0.responsibilities.0'));
    }
}
