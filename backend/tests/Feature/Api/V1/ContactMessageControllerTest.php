<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ContactMessageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_store_creates_message_and_returns_201(): void
    {
        $response = $this->postJson('/api/v1/contact-messages', [
            'name' => 'Hazem',
            'email' => 'hazem@example.com',
            'phone' => '+201000000000',
            'subject' => 'Portfolio inquiry',
            'message' => 'I would like to know more about discretionary mandates.',
        ]);

        $response->assertCreated()->assertJsonStructure(['data' => ['id'], 'message']);

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'hazem@example.com',
            'locale' => 'en',
            'is_read' => false,
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $this->postJson('/api/v1/contact-messages', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'message']);
    }

    public function test_store_returns_arabic_confirmation(): void
    {
        $response = $this->withHeaders(['Accept-Language' => 'ar'])
            ->postJson('/api/v1/contact-messages', [
                'name' => 'حازم',
                'email' => 'hazem@example.com',
                'message' => 'أرغب في معرفة المزيد.',
            ]);

        $response->assertCreated()->assertJsonPath('message', 'استلمنا رسالتك وسنرد خلال ٢٤ ساعة.');
        $this->assertDatabaseHas('contact_messages', ['locale' => 'ar']);
    }
}
