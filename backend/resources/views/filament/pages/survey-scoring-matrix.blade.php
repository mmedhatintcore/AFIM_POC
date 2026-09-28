<x-filament-panels::page>
    @php
        $categories = $this->categories();
        $questions = $this->questions();
    @endphp

    <x-filament::section>
        <x-slot name="heading">How the recommendation is calculated</x-slot>

        <p class="text-sm leading-relaxed">
            Every fund category starts at <strong>0</strong>. Each answer the visitor picks adds the points shown
            below to the categories it maps to:
        </p>
        <p class="my-3 rounded-lg bg-gray-50 p-3 font-mono text-sm dark:bg-white/5">
            Score(category) = Σ points(chosen answer of each question → category)
        </p>
        <p class="text-sm leading-relaxed">
            The category with the highest total is recommended; the next-highest category from a different fund
            group is offered as "also worth a look". Answering <strong>Yes</strong> to the Islamic question limits
            the result to Sharia-compliant categories, otherwise only conventional ones are considered. Answers with
            no points (age, income, amount, experience…) do not affect the result. Edit points on the question itself.
        </p>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">Points per answer</x-slot>

        <div class="overflow-x-auto">
            <table class="w-full text-start text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-xs uppercase text-gray-500 dark:border-white/10">
                        <th class="py-2 pe-4 text-start">Question / answer</th>
                        @foreach ($categories as $category)
                            <th class="px-2 py-2 text-center" title="{{ $category->getTranslation('name', 'en') }}">
                                {{ $category->key }}
                            </th>
                        @endforeach
                        <th class="py-2 ps-4 text-start">Business meaning</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($questions as $question)
                        <tr class="bg-gray-50 dark:bg-white/5">
                            <td class="py-2 pe-4 font-semibold" colspan="{{ $categories->count() + 2 }}">
                                {{ $question->sort }}. {{ $question->getTranslation('question', 'en') }}
                                @if (! collect($question->options)->contains(fn ($o) => ! empty($o['votes'])))
                                    <span class="ms-2 text-xs font-normal text-gray-500">— profile only, does not affect the result</span>
                                @endif
                            </td>
                        </tr>
                        @foreach ($question->options as $option)
                            <tr class="border-b border-gray-100 dark:border-white/5">
                                <td class="py-1.5 pe-4 ps-4">{{ $option['label']['en'] ?? '' }}</td>
                                @foreach ($categories as $category)
                                    @php $points = $option['votes'][$category->key] ?? null; @endphp
                                    <td class="px-2 py-1.5 text-center {{ $points ? 'font-bold text-primary-600' : 'text-gray-300' }}">
                                        {{ $points ?: '·' }}
                                    </td>
                                @endforeach
                                <td class="py-1.5 ps-4 text-xs text-gray-500">{{ $option['business_meaning'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>
