@php
    use Filament\Support\View\ComponentAttributeBag;

    $columns = $this->getColumns();
    $pollingInterval = $this->getPollingInterval();
@endphp
<x-filament-widgets::widget class="flex flex-col gap-4 filament-stats-overview-widget lg:gap-8"
                            :attributes="
        (new ComponentAttributeBag)
            ->merge([
                'wire:poll.' . $pollingInterval => $pollingInterval ? true : null,
            ], escape: false)
            ->class([
            'fi-wi-stats-overview-stats-ctn grid gap-6',
            'md:grid-cols-1' => $columns === 1,
            'md:grid-cols-2' => $columns === 2,
            'md:grid-cols-3' => $columns === 3,
            'md:grid-cols-2 xl:grid-cols-4' => $columns === 4,
        ])
    ">
    @if ($counties)
        <div class="filament-forms-field-wrapper md:grid-cols-1">
            <div class="space-y-2">
                <div class="flex items-center justify-between space-x-2 rtl:space-x-reverse">
                    <label
                        class="inline-flex items-center space-x-3 filament-forms-field-wrapper-label rtl:space-x-reverse"
                        for="widget.county">
                        <span class="text-sm font-medium leading-4">
                            @lang('general.county')
                        </span>
                    </label>
                </div>

                <x-filament::input.wrapper class="flex items-center space-x-1 filament-forms-select-component group rtl:space-x-reverse">
                    <x-filament::input.select id="widget.county" wire:model="county"
                        class=" transition duration-75 border-gray-300 rounded-lg shadow-sm outline-none filament-forms-input focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70">

                        <option value="0">–</option>

                        @foreach ($counties as $county)
                            <option value="{{ $county->id }}">{{ $county->name }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
        </div>
    @endif

    <div>
        {{ $this->content }}
    </div>
</x-filament-widgets::widget>
