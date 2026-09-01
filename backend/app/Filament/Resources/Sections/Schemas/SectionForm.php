<?php

namespace App\Filament\Resources\Sections\Schemas;

use App\Filament\Support\Bilingual;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

/**
 * Editor-friendly form for the `sections` CMS blocks. No raw JSON anywhere —
 * every section key gets exactly the structured fields its block uses on the
 * website; everything else stays hidden.
 */
class SectionForm
{
    private const KEY_OPTIONS = [
        'announcement' => 'Announcement banner (top of home)',
        'ticker' => 'Highlights ticker (home)',
        'hero' => 'Hero (home)',
        'advisor' => '“Need help in investment?” (home)',
        'trust' => 'Partners / trust strip (home)',
        'goals' => 'Invest by goal cards (home)',
        'prices_intro' => 'Prices & yields intro (home)',
        'services_intro' => 'Services intro (home)',
        'why' => 'Why AFIM (home)',
        'figures' => 'By-the-numbers stats (home)',
        'steps' => 'How to start (home)',
        'cta' => 'Closing call-to-action (home)',
        'about_brief' => 'About — brief & vision/mission/values',
        'footer' => 'Footer (all pages)',
        'news_intro' => 'News page intro',
        'faqs_intro' => 'FAQs page intro',
        'survey_intro' => 'Investment survey intro & result labels',
    ];

    // Which keys use which structured blocks.
    private const TEXT_KEYS = ['announcement', 'hero', 'advisor', 'trust', 'goals', 'prices_intro', 'services_intro', 'why', 'steps', 'cta', 'about_brief', 'news_intro', 'faqs_intro', 'survey_intro'];
    private const ITEM_KEYS = ['ticker', 'advisor', 'trust', 'goals', 'why', 'figures', 'steps', 'about_brief', 'footer'];
    private const CTA_KEYS = ['announcement', 'hero', 'advisor', 'steps', 'cta'];
    private const CTA_SECONDARY_KEYS = ['hero', 'advisor', 'cta'];

    private const ICON_OPTIONS = [
        'clock' => 'Clock', 'target' => 'Target', 'compass' => 'Compass',
        'shield' => 'Shield', 'sprout' => 'Sprout (growth)', 'crescent' => 'Crescent (Sharia)',
        'bank' => 'Bank (institutions)', 'eye' => 'Eye (vision)', 'scales' => 'Scales (values)',
    ];

    public static function configure(Schema $schema): Schema
    {
        $keyIs = fn (string ...$keys) => fn (Get $get): bool => in_array($get('key'), $keys, true);
        // Inside repeater rows, `key` must be read from the form root (two levels up).
        $rowKeyIs = fn (string ...$keys) => fn (Get $get): bool => in_array($get('../../key'), $keys, true);
        $keyIn = fn (array $keys) => fn (Get $get): bool => in_array($get('key'), $keys, true);

        return $schema
            ->components([
                Select::make('key')
                    ->label('Section')
                    ->options(self::KEY_OPTIONS)
                    ->required()
                    ->live()
                    ->disabledOn('edit')
                    ->helperText('Where this block appears on the website. Cannot change after creation.'),
                Toggle::make('is_enabled')
                    ->label('Visible on the website')
                    ->default(true),

                Bilingual::tabs([
                    ['name' => 'title', 'label' => 'Title'],
                    ['name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea', 'rows' => 2],
                    ['name' => 'body', 'label' => 'Body text', 'type' => 'textarea', 'rows' => 5],
                ])->visible($keyIn(self::TEXT_KEYS)),

                // ============ Primary / secondary call-to-action ============
                Fieldset::make('Button / link')
                    ->schema([
                        TextInput::make('cta.label.en')->label('Label (English)'),
                        TextInput::make('cta.label.ar')->label('Label (Arabic)'),
                        TextInput::make('cta.href')
                            ->label('Link')
                            ->helperText('A page path like /funds or /survey — or #finder to open the service-finder popup.'),
                    ])
                    ->columns(3)
                    ->visible($keyIn(self::CTA_KEYS)),
                Fieldset::make('Secondary button / link')
                    ->schema([
                        TextInput::make('cta.secondary_label.en')->label('Label (English)'),
                        TextInput::make('cta.secondary_label.ar')->label('Label (Arabic)'),
                        TextInput::make('cta.secondary_href')->label('Link'),
                    ])
                    ->columns(3)
                    ->visible($keyIn(self::CTA_SECONDARY_KEYS)),

                // ============ Repeatable entries, per section ============
                Repeater::make('items')
                    ->label(fn (Get $get) => match ($get('key')) {
                        'ticker' => 'Ticker highlights',
                        'advisor' => 'Feature chips',
                        'trust' => 'Partner names',
                        'goals' => 'Goal cards',
                        'why' => 'Numbered features',
                        'figures' => 'Statistics (first row = the big hero number)',
                        'steps' => 'Steps',
                        'about_brief' => 'Vision / Mission / Values cards',
                        'footer' => 'Footer entries (office address + social links)',
                        default => 'Entries',
                    })
                    ->visible($keyIn(self::ITEM_KEYS))
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title']['en']
                        ?? $state['text']['en']
                        ?? $state['label']['en']
                        ?? (is_string($state['value'] ?? null) ? $state['value'] : null))
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        // Icon — advisor chips, goal cards, vision/mission/values
                        Select::make('icon')
                            ->options(self::ICON_OPTIONS)
                            ->visible($rowKeyIs('advisor', 'goals', 'about_brief')),

                        // Title EN/AR — goals, why, steps, about cards
                        TextInput::make('title.en')->label('Title (English)')
                            ->visible($rowKeyIs('goals', 'why', 'steps', 'about_brief')),
                        TextInput::make('title.ar')->label('Title (Arabic)')
                            ->visible($rowKeyIs('goals', 'why', 'steps', 'about_brief')),

                        // Text EN/AR — most sections
                        Textarea::make('text.en')->label('Text (English)')->rows(2)
                            ->visible($rowKeyIs('ticker', 'trust', 'advisor', 'goals', 'why', 'steps', 'about_brief', 'footer')),
                        Textarea::make('text.ar')->label('Text (Arabic)')->rows(2)
                            ->visible($rowKeyIs('ticker', 'trust', 'advisor', 'goals', 'why', 'steps', 'about_brief', 'footer')),

                        // Figures number (plain text, Latin digits)
                        TextInput::make('value')
                            ->label('Number')
                            ->helperText('Digits only, e.g. 93 or 21.3')
                            ->visible($rowKeyIs('figures')),
                        TextInput::make('suffix')->label('Suffix')
                            ->helperText('e.g. + or %')
                            ->visible($rowKeyIs('figures')),
                        TextInput::make('decimals')->label('Decimal places')->numeric()
                            ->visible($rowKeyIs('figures')),
                        TextInput::make('unit.en')->label('Unit (English)')
                            ->helperText('Hero stat only, e.g. EGP bn')
                            ->visible($rowKeyIs('figures')),
                        TextInput::make('unit.ar')->label('Unit (Arabic)')
                            ->visible($rowKeyIs('figures')),
                        TextInput::make('label.en')->label('Caption (English)')
                            ->visible($rowKeyIs('figures')),
                        TextInput::make('label.ar')->label('Caption (Arabic)')
                            ->visible($rowKeyIs('figures')),
                        Toggle::make('hero')->label('Show as the big headline number')
                            ->visible($rowKeyIs('figures')),

                        // Goal cards link
                        TextInput::make('link_label.en')->label('Link label (English)')
                            ->visible($rowKeyIs('goals')),
                        TextInput::make('link_label.ar')->label('Link label (Arabic)')
                            ->visible($rowKeyIs('goals')),
                        TextInput::make('href')->label('Link')
                            ->helperText('Page path, e.g. /funds')
                            ->visible($rowKeyIs('goals')),

                        // Footer entries
                        Select::make('group')
                            ->label('Column')
                            ->options(['office' => 'Head office', 'social' => 'Follow AFIM'])
                            ->visible($rowKeyIs('footer')),
                        TextInput::make('href')->label('Link (social only)')
                            ->helperText('Full URL, e.g. https://facebook.com/AFIM1994')
                            ->visible($rowKeyIs('footer')),
                    ]),

                // ============ Advisor mini-steps (Answer → Get matched → Subscribe) ============
                Repeater::make('extra.steps')
                    ->label('Mini steps under the panel')
                    ->schema([
                        TextInput::make('en')->label('English')->required(),
                        TextInput::make('ar')->label('Arabic')->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->visible($keyIs('advisor')),

                // ============ Hero growth-chart year markers ============
                Repeater::make('extra.chart_milestones')
                    ->label('Growth-chart year markers')
                    ->helperText('Shown left-to-right on the hero illustration, in the order below. Add a row for a new year at any time.')
                    ->schema([
                        TextInput::make('year')->label('Year')->required(),
                        TextInput::make('value')->label('AUM (EGP bn)')
                            ->helperText('Digits only, e.g. 93 or 15.5')
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->reorderable()
                    ->visible($keyIs('hero')),

                // ============ Per-section extras (explicit fields, no JSON) ============
                Fieldset::make('More settings')
                    ->columns(2)
                    ->columnSpanFull()
                    ->visible($keyIs('hero', 'advisor', 'prices_intro', 'why', 'figures', 'about_brief', 'footer', 'survey_intro'))
                    ->schema([
                        ...self::pair('extra.eyebrow', 'Eyebrow line', $keyIs('hero')),
                        ...self::pair('extra.chart_unit', 'Growth-chart unit label', $keyIs('hero')),
                        ...self::pair('extra.live_label', '“Live” label', $keyIs('prices_intro')),
                        ...self::pair('extra.note', 'Small note under the buttons', $keyIs('advisor', 'survey_intro')),
                        ...self::pair('extra.disclaimer', 'Disclaimer line (a live "As of <month/year>" is prefixed automatically)', $keyIs('prices_intro')),
                        ...self::pair('extra.kicker', 'Kicker (small label above the title)', $keyIs('why', 'figures')),
                        ...self::pair('extra.board_note', 'Board tab note', $keyIs('about_brief')),
                        ...self::pair('extra.committees_note', 'Committees tab note', $keyIs('about_brief')),
                        ...self::pair('extra.leadership_note', 'Leadership tab note', $keyIs('about_brief')),
                        ...self::pair('extra.office_title', '“Head office” column title', $keyIs('footer')),
                        ...self::pair('extra.services_title', '“Services” column title', $keyIs('footer')),
                        ...self::pair('extra.follow_title', '“Follow AFIM” column title', $keyIs('footer')),
                        TextInput::make('extra.phone')->label('Phone')->visible($keyIs('footer')),
                        TextInput::make('extra.email')->label('Email')->visible($keyIs('footer')),
                        ...self::pair('extra.reply_note', '“We reply within 24 hours” note', $keyIs('footer')),
                        ...self::pair('extra.copyright', 'Copyright / bottom line', $keyIs('footer'), textarea: true),
                        ...self::pair('extra.result_title', 'Survey result heading', $keyIs('survey_intro')),
                        ...self::pair('extra.funds_label', 'Survey matching-funds heading', $keyIs('survey_intro')),
                    ]),
            ]);
    }

    /** A pair of EN/AR inputs bound to `{path}.en` / `{path}.ar`. */
    private static function pair(string $path, string $label, \Closure $visible, bool $textarea = false): array
    {
        $make = fn (string $locale, string $suffix) => ($textarea
            ? Textarea::make("{$path}.{$locale}")->rows(2)
            : TextInput::make("{$path}.{$locale}"))
            ->label("{$label} ({$suffix})")
            ->visible($visible);

        return [$make('en', 'English'), $make('ar', 'Arabic')];
    }
}
