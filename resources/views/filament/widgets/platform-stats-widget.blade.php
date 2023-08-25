<x-filament::widget class="flex flex-col gap-4 filament-stats-overview-widget lg:gap-8">
    @if ($counties)
        <div class="filament-forms-field-wrapper">
            <div class="space-y-2">
                <div class="flex items-center justify-between space-x-2 rtl:space-x-reverse">
                    <label
                        class="inline-flex items-center space-x-3 filament-forms-field-wrapper-label rtl:space-x-reverse"
                        for="widget.county">
                        <span class="text-sm font-medium leading-4 text-gray-700">
                            @lang('general.county')
                        </span>
                    </label>
                </div>

                <div class="flex items-center space-x-1 filament-forms-select-component group rtl:space-x-reverse">
                    <select id="widget.county" wire:model="county"
                        class="block text-gray-900 transition duration-75 border-gray-300 rounded-lg shadow-sm outline-none filament-forms-input focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70">

                        <option value="0">–</option>

                        @foreach ($counties as $county)
                            <option value="{{ $county->id }}">{{ $county->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endif

    <div {!! ($pollingInterval = $this->getPollingInterval()) ? "wire:poll.{$pollingInterval}" : '' !!}>
        <x-filament::stats :columns="$this->getColumns()">
            @foreach ($this->getCachedCards() as $card)
                {{ $card }}
            @endforeach
        </x-filament::stats>
    </div>

</x-filament::widget>
