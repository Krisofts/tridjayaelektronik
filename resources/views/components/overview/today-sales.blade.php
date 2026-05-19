@php
    $salesToday = $comparison['sales']['today'] ?? 0;
    $salesYesterday = $comparison['sales']['yesterday'] ?? 0;
    $growth = $comparison['sales']['growth'] ?? 0;

    $transaksiToday = $comparison['transaksi']['today'] ?? 0;
    $unitToday = $comparison['unit']['today'] ?? 0;

    $avg = $transaksiToday > 0
        ? $salesToday / $transaksiToday
        : 0;

    $isPositive = $growth >= 0;
@endphp

<div class="rounded-2xl border border-gray-200 bg-white
            px-6 py-5 sm:px-7 sm:py-6
            dark:border-gray-800 dark:bg-white/[0.03]">

    {{-- HEADER --}}
    <div class="flex items-start justify-between gap-6">

        {{-- LEFT --}}
        <div class="space-y-1">
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                Total Penjualan Hari Ini
            </span>

            <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-gray-900 dark:text-white/90">
                Rp {{ number_format($salesToday, 0, ',', '.') }}
            </h2>

            <p class="text-xs text-gray-500 dark:text-gray-400">
                vs kemarin Rp {{ number_format($salesYesterday, 0, ',', '.') }}
            </p>
        </div>

        {{-- RIGHT --}}
        <div class="shrink-0">
            <div class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold
                transition
                {{ $isPositive
                    ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400'
                    : 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400' }}">

                @if($isPositive)
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3l6 6h-4v8H8V9H4l6-6z" clip-rule="evenodd"/>
                    </svg>
                @else
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 17l-6-6h4V3h4v8h4l-6 6z" clip-rule="evenodd"/>
                    </svg>
                @endif

                <span>
                    {{ $isPositive ? '+' : '' }}{{ number_format($growth, 1) }}%
                </span>
            </div>
        </div>

    </div>

    {{-- DIVIDER --}}
    <div class="my-5 h-px bg-gray-100 dark:bg-gray-800"></div>

    {{-- BOTTOM STATS --}}
    <div class="grid grid-cols-3 gap-6">

        <div class="space-y-1">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Transaksi
            </p>
            <p class="text-lg font-semibold text-gray-900 dark:text-white/90">
                {{ $transaksiToday }}
            </p>
        </div>

        <div class="space-y-1">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Unit
            </p>
            <p class="text-lg font-semibold text-gray-900 dark:text-white/90">
                {{ $unitToday }}
            </p>
        </div>

        <div class="space-y-1">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Avg / Transaksi
            </p>
            <p class="text-lg font-semibold text-gray-900 dark:text-white/90">
                Rp {{ number_format($avg, 0, ',', '.') }}
            </p>
        </div>

    </div>

</div>