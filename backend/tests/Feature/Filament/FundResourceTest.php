<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Funds\Pages\EditFund;
use App\Filament\Resources\Funds\Pages\ListFunds;
use App\Models\Fund;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

final class FundResourceTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_funds_list_renders(): void
    {
        $this->actingAs(User::where('email', 'admin@afim.com.eg')->firstOrFail());

        Livewire::test(ListFunds::class)->assertOk();
    }

    public function test_card_fields_can_be_fed_from_the_admin(): void
    {
        $this->actingAs(User::where('email', 'admin@afim.com.eg')->firstOrFail());
        $fund = Fund::where('slug', 'dahab-gold-fund')->firstOrFail();

        Livewire::test(EditFund::class, ['record' => $fund->getKey()])
            ->fillForm([
                'return_1m' => -2.8,
                'inception_date' => '2014-06-19',
                'currency' => 'EGP',
                'nav_price' => 87.917,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        Livewire::test(EditFund::class, ['record' => $fund->getKey()])
            ->fillForm(['price_date_mode' => 'manual', 'price_date' => '2026-03-01'])
            ->call('save')
            ->assertHasNoFormErrors();
        $this->assertSame('2026-03-01', $fund->fresh()->price_date->toDateString());

        Livewire::test(EditFund::class, ['record' => $fund->getKey()])
            ->fillForm(['price_date_mode' => 'manual', 'price_date' => null])
            ->call('save')
            ->assertHasFormErrors(['price_date' => 'required']);

        $fund->refresh();
        $this->assertSame('-2.80', $fund->return_1m);
        $this->assertSame('2014-06-19', $fund->inception_date->toDateString());
        $this->assertSame('EGP', $fund->currency);
    }
}
