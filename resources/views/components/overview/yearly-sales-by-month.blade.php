@php
    $chartData = collect($yearlySalesByMonth ?? [])->map(function ($item) {
        return [
            'month' => substr($item->NamaBulan ?? '-', 0, 3),
            'sales' => (float) ($item->TotalSetelahDiskon ?? 0),
            'unit' => (int) ($item->TotalUnit ?? 0),
        ];
    })->values();
@endphp

<div
    class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">

    <div class="flex flex-col gap-5 mb-6 sm:flex-row sm:justify-between">

        <div class="w-full">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Yearly Sales By Month
            </h3>

            <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
                Monthly sales and unit performance this year
            </p>
        </div>

        <div class="flex items-start w-full gap-3 sm:justify-end">

            <div x-data="{ selected: 'sales' }"
                class="inline-flex w-fit items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900">

                @php
                    $options = [
                        ['value' => 'sales', 'label' => 'Sales'],
                        ['value' => 'unit', 'label' => 'Unit'],
                    ];
                @endphp

                @foreach ($options as $option)
                    <button
                        @click="selected = '{{ $option['value'] }}'"
                        :class="selected === '{{ $option['value'] }}'
                            ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800'
                            : 'text-gray-500 dark:text-gray-400'"
                        class="px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white">

                        {{ $option['label'] }}

                    </button>
                @endforeach
            </div>

        </div>
    </div>

    <div class="max-w-full overflow-x-auto custom-scrollbar">

        <div id="chartThree"
            data-chart='@json($chartData)'
            class="-ml-4 min-w-[700px] pl-2 xl:min-w-full">
        </div>

    </div>
</div>