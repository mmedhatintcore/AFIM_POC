<?php

namespace Tests\Feature\Api\V1;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ServiceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_index_returns_published_services_ordered(): void
    {
        $response = $this->getJson('/api/v1/services');

        $response->assertOk()->assertJsonCount(4, 'data')
            ->assertJsonPath('data.0.key', 'portfolio')
            ->assertJsonStructure(['data' => [['id', 'key', 'slug', 'name', 'description', 'body', 'icon', 'sort']]]);
    }

    public function test_index_excludes_unpublished_services(): void
    {
        Service::query()->where('key', 'portfolio')->update(['is_published' => false]);

        $this->getJson('/api/v1/services')->assertOk()->assertJsonCount(3, 'data');
    }

    public function test_show_returns_service_in_arabic(): void
    {
        $response = $this->withHeaders(['Accept-Language' => 'ar'])
            ->getJson('/api/v1/services/portfolio-management');

        $response->assertOk()->assertJsonPath('data.name', 'إدارة المحافظ');
    }

    public function test_show_returns_404_for_unknown_slug(): void
    {
        $this->getJson('/api/v1/services/unknown')->assertNotFound();
    }
}
