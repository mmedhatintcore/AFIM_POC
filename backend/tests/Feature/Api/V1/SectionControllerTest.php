<?php

namespace Tests\Feature\Api\V1;

use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SectionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_index_returns_all_enabled_sections(): void
    {
        $response = $this->getJson('/api/v1/sections');

        $response->assertOk()
            ->assertJsonStructure(['data' => [['key', 'is_enabled', 'title', 'subtitle', 'body', 'items', 'cta', 'extra']]]);

        $keys = collect($response->json('data'))->pluck('key');
        $this->assertTrue($keys->contains('hero'));
        $this->assertTrue($keys->contains('footer'));
    }

    public function test_index_hides_disabled_sections(): void
    {
        Section::query()->where('key', 'announcement')->update(['is_enabled' => false]);

        $response = $this->getJson('/api/v1/sections');

        $this->assertFalse(collect($response->json('data'))->pluck('key')->contains('announcement'));
    }

    public function test_show_returns_section_localized_in_arabic(): void
    {
        $response = $this->withHeaders(['Accept-Language' => 'ar'])->getJson('/api/v1/sections/hero');

        $response->assertOk()
            ->assertJsonPath('data.key', 'hero')
            ->assertJsonPath('data.title', 'أول شركة إدارة أصول في مصر.');
    }

    public function test_show_localizes_nested_item_leaves(): void
    {
        $response = $this->withHeaders(['Accept-Language' => 'en'])->getJson('/api/v1/sections/goals');

        $response->assertOk()
            ->assertJsonPath('data.items.0.title', 'Preserve capital');
    }

    public function test_show_returns_404_for_unknown_key(): void
    {
        $this->getJson('/api/v1/sections/nope')->assertNotFound()->assertJsonStructure(['message']);
    }
}
