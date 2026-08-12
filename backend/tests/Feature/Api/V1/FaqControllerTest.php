<?php

namespace Tests\Feature\Api\V1;

use App\Models\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class FaqControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_index_returns_published_faqs_in_order(): void
    {
        $response = $this->getJson('/api/v1/faqs');

        $response->assertOk()->assertJsonCount(5, 'data')
            ->assertJsonStructure(['data' => [['id', 'question', 'answer', 'action_type', 'action_label']]])
            ->assertJsonPath('data.0.action_type', 'finder');
    }

    public function test_index_excludes_unpublished_faqs(): void
    {
        Faq::query()->where('sort', 1)->update(['is_published' => false]);

        $this->getJson('/api/v1/faqs')->assertOk()->assertJsonCount(4, 'data');
    }

    public function test_index_returns_arabic_content(): void
    {
        $response = $this->withHeaders(['Accept-Language' => 'ar'])->getJson('/api/v1/faqs');

        $response->assertOk()->assertJsonPath('data.0.question', 'جديد — كيف أبدأ وما الذي يناسبني؟');
    }
}
