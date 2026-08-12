<?php

namespace Tests\Feature\Api\V1;

use App\Models\NewsPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class NewsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_index_returns_paginated_news(): void
    {
        $response = $this->getJson('/api/v1/news');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [['id', 'slug', 'type', 'type_label', 'source', 'title', 'excerpt', 'published_at']],
                'links',
                'meta' => ['current_page', 'per_page', 'total'],
            ]);
    }

    public function test_type_filter_limits_results(): void
    {
        $response = $this->getJson('/api/v1/news?filters[type]=press');

        $response->assertOk();
        $this->assertTrue(collect($response->json('data'))->every(fn ($post) => $post['type'] === 'press'));
    }

    public function test_invalid_type_filter_fails_validation(): void
    {
        $this->getJson('/api/v1/news?filters[type]=bogus')->assertStatus(422);
    }

    public function test_search_filters_results(): void
    {
        $response = $this->getJson('/api/v1/news?search=Dahab');

        $response->assertOk();
        $this->assertGreaterThanOrEqual(1, $response->json('meta.total'));
        $this->assertLessThan(NewsPost::count(), $response->json('meta.total'));
    }

    public function test_per_page_changes_page_size(): void
    {
        $response = $this->getJson('/api/v1/news?per_page=2');

        $response->assertOk()->assertJsonCount(2, 'data')->assertJsonPath('meta.per_page', 2);
    }

    public function test_show_returns_body_and_related(): void
    {
        $response = $this->getJson('/api/v1/news/aum-surpasses-egp-93-billion');

        $response->assertOk()
            ->assertJsonStructure(['data' => ['body', 'related']])
            ->assertJsonPath('data.slug', 'aum-surpasses-egp-93-billion');

        foreach ($response->json('data.related') as $related) {
            $this->assertSame('press', $related['type']);
            $this->assertNotSame('aum-surpasses-egp-93-billion', $related['slug']);
        }
    }

    public function test_show_returns_arabic_title(): void
    {
        $response = $this->withHeaders(['Accept-Language' => 'ar'])
            ->getJson('/api/v1/news/aum-surpasses-egp-93-billion');

        $response->assertOk()->assertJsonPath('data.title', 'الأصول المُدارة تتجاوز ٩٣ مليار جنيه');
    }

    public function test_show_returns_404_for_unpublished_post(): void
    {
        NewsPost::query()->where('slug', 'fund-4-cash-coupon')->update(['is_published' => false]);

        $this->getJson('/api/v1/news/fund-4-cash-coupon')->assertNotFound();
    }
}
