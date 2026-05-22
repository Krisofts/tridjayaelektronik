<div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">

    {{-- HEADER --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-800">

        <div>
            <h2 class="text-sm font-semibold tracking-tight text-gray-900 dark:text-white">
                Dealer Daily Target
            </h2>

            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Target vs Achievement Today
            </p>
        </div>

        <div class="text-[11px] text-gray-400">
            {{ now()->format('d M Y') }}
        </div>

    </div>

    {{-- CONTENT --}}
    <div class="p-3">

        <div class="space-y-2">

            @forelse ($dealers as $index => $dealer)

                @php
                    $rank = $index + 1;

                    $statusColors = match ($dealer['status']) {
                        'ACHIEVED' => 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                        'GOOD'     => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                        'WARNING'  => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400',
                        'LOW'      => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                        default    => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                    };

                    $percent = $dealer['achievement_percent'];
                @endphp

                <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-800/30 px-4 py-3 hover:bg-gray-100/70 dark:hover:bg-gray-800/50 transition-all duration-200">

                    {{-- TOP ROW --}}
                    <div class="flex items-center justify-between gap-3">

                        {{-- LEFT --}}
                        <div class="min-w-0">

                            <div class="text-sm font-semibold tracking-tight text-gray-900 dark:text-white truncate">
                                {{ $dealer['dealer_name'] }}
                            </div>

                            <div class="flex items-center gap-3 mt-1 text-[11px] text-gray-500 dark:text-gray-400">

                                <span>
                                    Tx {{ number_format($dealer['transactions']) }}
                                </span>

                                <span>
                                    {{ number_format($dealer['units']) }} Unit
                                </span>

                            </div>

                        </div>

                        {{-- RIGHT --}}
                        <div class="text-right shrink-0">

                            <div class="text-sm font-semibold tracking-tight text-gray-900 dark:text-white">
                                {{ number_format($dealer['achieve'], 0, ',', '.') }}
                            </div>

                            <div class="text-[11px] text-gray-400">
                                / {{ number_format($dealer['daily_target'], 0, ',', '.') }}
                            </div>

                        </div>

                    </div>

                    {{-- PROGRESS BAR --}}
                    <div class="mt-3">

                        <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">

                            <div
                                class="h-2 rounded-full transition-all duration-300
                                @if($percent >= 100) bg-green-500
                                @elseif($percent >= 80) bg-blue-500
                                @elseif($percent >= 50) bg-yellow-500
                                @else bg-red-500
                                @endif"
                                style="width: {{ min($percent, 100) }}%"
                            ></div>

                        </div>

                        <div class="flex items-center justify-between mt-2">

                            <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                Achievement
                            </div>

                            <div class="flex items-center gap-2">

                                <span class="text-[11px] font-semibold text-gray-700 dark:text-gray-300">
                                    {{ $percent }}%
                                </span>

                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $statusColors }}">
                                    {{ $dealer['status'] }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="flex items-center justify-center py-10">

                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        No dealer target data available
                    </div>

                </div>

            @endforelse

        </div>

    </div>

</div>