<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class FundControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_index_returns_published_funds(): void
    {
        $response = $this->getJson('/api/v1/funds');

        $response->assertOk()
            ->assertJsonStructure(['data' => [['id', 'slug', 'name', 'group_key', 'risk_level', 'risk_label', 'order_channel', 'order_channel_label', 'how_to', 'platforms', 'is_featured']]]);

        $this->assertGreaterThanOrEqual(15, count($response->json('data')));
    }

    public function test_featured_filter_limits_to_carousel_funds(): void
    {
        $response = $this->getJson('/api/v1/funds?filters[is_featured]=1');

        $response->assertOk()->assertJsonCount(6, 'data');
        $this->assertTrue(collect($response->json('data'))->every(fn ($fund) => $fund['is_featured']));
    }

    public function test_group_filter_limits_results(): void
    {
        $response = $this->getJson('/api/v1/funds?filters[group_key]=metals');

        $response->assertOk();
        $this->assertTrue(collect($response->json('data'))->every(fn ($fund) => $fund['group_key'] === 'metals'));
    }

    public function test_invalid_group_filter_fails_validation(): void
    {
        $this->getJson('/api/v1/funds?filters[group_key]=bogus')->assertStatus(422);
    }

    public function test_show_returns_fund_with_localized_platforms(): void
    {
        $response = $this->withHeaders(['Accept-Language' => 'ar'])->getJson('/api/v1/funds/dahab-gold-fund');

        $response->assertOk()
            ->assertJsonPath('data.name', 'صندوق دهب للذهب')
            ->assertJsonPath('data.risk_label', 'مخاطر مرتفعة');

        $this->assertContains('ثاندر', $response->json('data.platforms'));
    }

    public function test_show_returns_404_for_unknown_slug(): void
    {
        $this->getJson('/api/v1/funds/unknown')->assertNotFound();
    }

    public function test_fund_categories_index_returns_eight_categories(): void
    {
        $response = $this->getJson('/api/v1/fund-categories');

        $response->assertOk()->assertJsonCount(8, 'data')
            ->assertJsonPath('data.0.key', 'mm_acc')
            ->assertJsonStructure(['data' => [['key', 'name', 'description', 'risk_level', 'risk_label', 'fund_group', 'illustration']]]);
    }
}
