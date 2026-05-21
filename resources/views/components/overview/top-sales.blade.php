<div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">

    {{-- HEADER --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-800">

        <div>
            <h2 class="text-sm font-semibold tracking-tight text-gray-900 dark:text-white">
                Top Sales Performance
            </h2>

            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Best sales performance this month
            </p>
        </div>

        <div class="text-[11px] text-gray-400">
            Top 10
        </div>

    </div>

    {{-- CONTENT --}}
    <div class="p-3">

        <div class="space-y-2">

            @forelse ($sales as $index => $item)

                @php
                    $rank = $index + 1;

                    $rankClasses = match ($rank) {
                        1 => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400',
                        2 => 'bg-gray-200 text-gray-700 dark:bg-gray-500/10 dark:text-gray-300',
                        3 => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                        default => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                    };
                @endphp

                <div class="flex items-center justify-between gap-4 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-800/30 px-4 py-3 hover:bg-gray-100/70 dark:hover:bg-gray-800/50 transition-all duration-200">

                    {{-- LEFT --}}
                    <div class="flex items-center gap-3 min-w-0">

                        {{-- RANK --}}
                        <div class="flex items-center justify-center w-8 h-8 rounded-full text-xs font-bold shrink-0 {{ $rankClasses }}">
                            {{ $rank }}
                        </div>

                        {{-- SALES INFO --}}
                        <div class="min-w-0">

                            <div class="text-sm font-semibold tracking-tight text-gray-900 dark:text-white truncate">
                                {{ $item['name'] }}
                            </div>

                            <div class="flex items-center gap-3 mt-1 text-[11px] text-gray-500 dark:text-gray-400">

                                <span>
                                    Tx {{ number_format($item['transactions']) }}
                                </span>

                                <span>
                                    {{ number_format($item['qty']) }} Unit
                                </span>

                            </div>

                        </div>

                    </div>

                    {{-- RIGHT --}}
                    <div class="text-right shrink-0">

                        <div class="text-sm font-semibold tracking-tight text-gray-900 dark:text-white">
                            Rp {{ number_format($item['sales'], 0, ',', '.') }}
                        </div>

                        <div class="text-[11px] text-gray-400 mt-1">
                            Net Sales
                        </div>

                    </div>

                </div>

            @empty

                <div class="flex items-center justify-center py-10">

                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        No sales performance data available
                    </div>

                </div>

            @endforelse

        </div>

    </div>

</div>