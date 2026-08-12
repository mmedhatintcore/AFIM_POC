<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Sections\Pages\EditSection;
use App\Filament\Resources\Sections\Pages\ListSections;
use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

final class SectionResourceTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function admin(): User
    {
        return User::where('email', 'admin@afim.com.eg')->firstOrFail();
    }

    public function test_sections_list_renders(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(ListSections::class)->assertOk();
    }

    public function test_every_seeded_section_edit_form_renders(): void
    {
        $this->actingAs($this->admin());

        Section::all()->each(function (Section $section) {
            Livewire::test(EditSection::class, ['record' => $section->getKey()])
                ->assertOk();
        });
    }

    public function test_editing_hero_title_preserves_items_cta_and_extra(): void
    {
        $this->actingAs($this->admin());

        $hero = Section::where('key', 'hero')->firstOrFail();

        Livewire::test(EditSection::class, ['record' => $hero->getKey()])
            ->fillForm(['title.en' => 'Updated hero title'])
            ->call('save')
            ->assertHasNoFormErrors();

        $hero->refresh();
        $this->assertSame('Updated hero title', $hero->getTranslation('title', 'en'));
        $this->assertSame('أول شركة إدارة أصول في مصر.', $hero->getTranslation('title', 'ar'));

        $items = array_values($hero->items);
        $this->assertCount(3, $items);
        $this->assertSame('Dahab Gold', $items[0]['title']['en']);
        $this->assertSame('+0.88%', $items[0]['value']);
        $this->assertSame('up', $items[0]['trend']);
        $this->assertSame('142.83', $items[1]['value']);
        $this->assertArrayNotHasKey('text', $items[0]);

        $this->assertSame('Asset Management • Egypt • Since 1994', $hero->extra['eyebrow']['en']);
        $this->assertSame('#finder', $hero->cta['href']);
        $this->assertSame('خدماتنا', $hero->cta['secondary_label']['ar']);
    }

    public function test_editing_goal_card_title_preserves_row_structure(): void
    {
        $this->actingAs($this->admin());

        $goals = Section::where('key', 'goals')->firstOrFail();

        Livewire::test(EditSection::class, ['record' => $goals->getKey()])
            ->fillForm(function (array $rawState): array {
                $firstRowKey = array_key_first($rawState['items']);

                return ["items.{$firstRowKey}.title.en" => 'Protect capital'];
            })
            ->call('save')
            ->assertHasNoFormErrors();

        $goals->refresh();
        $items = array_values($goals->items);
        $this->assertCount(4, $items);
        $this->assertSame('Protect capital', $items[0]['title']['en']);
        $this->assertSame('الحفاظ على رأس المال', $items[0]['title']['ar']);
        $this->assertSame('shield', $items[0]['icon']);
        $this->assertSame('/funds', $items[0]['href']);
        $this->assertSame('Explore funds →', $items[0]['link_label']['en']);
    }

    public function test_editing_footer_extra_preserves_contact_details(): void
    {
        $this->actingAs($this->admin());

        $footer = Section::where('key', 'footer')->firstOrFail();

        Livewire::test(EditSection::class, ['record' => $footer->getKey()])
            ->fillForm(['extra.phone' => '(+202) 11111111'])
            ->call('save')
            ->assertHasNoFormErrors();

        $footer->refresh();
        $this->assertSame('(+202) 11111111', $footer->extra['phone']);
        $this->assertSame('info@afim.com.eg', $footer->extra['email']);
        $this->assertSame('Head office', $footer->extra['office_title']['en']);
        $this->assertNotEmpty($footer->extra['copyright']['ar']);

        $items = array_values($footer->items);
        $this->assertCount(3, $items);
        $this->assertSame('office', $items[0]['group']);
        $this->assertSame('https://facebook.com/AFIM1994', $items[1]['href']);
    }

    public function test_editing_figures_stats_preserves_numbers(): void
    {
        $this->actingAs($this->admin());

        $figures = Section::where('key', 'figures')->firstOrFail();

        Livewire::test(EditSection::class, ['record' => $figures->getKey()])
            ->fillForm(function (array $rawState): array {
                $firstRowKey = array_key_first($rawState['items']);

                return ["items.{$firstRowKey}.value" => '95'];
            })
            ->call('save')
            ->assertHasNoFormErrors();

        $figures->refresh();
        $items = array_values($figures->items);
        $this->assertCount(4, $items);
        $this->assertSame('95', $items[0]['value']);
        $this->assertTrue((bool) ($items[0]['hero'] ?? false));
        $this->assertSame('EGP bn', $items[0]['unit']['en']);

        $marketShare = collect($items)->firstWhere('value', '21.3');
        $this->assertNotNull($marketShare);
        $this->assertSame('%', $marketShare['suffix']);
        $this->assertEquals(1, $marketShare['decimals']);
    }
}
