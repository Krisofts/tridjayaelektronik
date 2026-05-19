<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6">

    {{-- CARD 1: SALES --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6
                dark:border-gray-800 dark:bg-gray-900/40
                hover:shadow-md transition-all duration-300">

        <div class="flex items-center justify-center w-12 h-12 rounded-xl
                    bg-gray-100 text-gray-700
                    dark:bg-gray-800 dark:text-white">

            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                <path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z"/>
            </svg>
        </div>

        <div class="flex items-end justify-between mt-5">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Total Penjualan Hari Ini
                </span>

                <h4 class="mt-2 font-bold text-gray-900 dark:text-white text-xl">
                    Rp {{ number_format($comparison['sales']['today'] ?? 0, 0, ',', '.') }}
                </h4>

                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                    vs kemarin Rp {{ number_format($comparison['sales']['yesterday'] ?? 0, 0, ',', '.') }}
                </p>
            </div>

            @php
                $growth = $comparison['sales']['growth'] ?? 0;
                $isPositive = $growth >= 0;
            @endphp

            <span class="flex items-center gap-1 rounded-full px-3 py-1 text-sm font-medium
                {{ $isPositive
                    ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400'
                    : 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400' }}">

                {{ $isPositive ? '+' : '' }}{{ number_format($growth, 1) }}%
            </span>
        </div>
    </div>

    {{-- CARD 2: TRANSAKSI --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6
                dark:border-gray-800 dark:bg-gray-900/40
                hover:shadow-md transition-all duration-300">

        <div class="flex items-center justify-center w-12 h-12 rounded-xl
                    bg-gray-100 text-gray-700
                    dark:bg-gray-800 dark:text-white">

            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                <path d="M3 3h18v18H3z"/>
            </svg>
        </div>

        <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Transaksi</span>

            <h4 class="mt-2 font-bold text-gray-900 dark:text-white text-xl">
                {{ $comparison['transaksi']['today'] ?? 0 }}
            </h4>
        </div>
    </div>

    {{-- CARD 3: UNIT --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6
                dark:border-gray-800 dark:bg-gray-900/40
                hover:shadow-md transition-all duration-300">

        <div class="flex items-center justify-center w-12 h-12 rounded-xl
                    bg-gray-100 text-gray-700
                    dark:bg-gray-800 dark:text-white">

            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                <path d="M4 4h16v16H4z"/>
            </svg>
        </div>

        <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Unit</span>

            <h4 class="mt-2 font-bold text-gray-900 dark:text-white text-xl">
                {{ $comparison['unit']['today'] ?? 0 }}
            </h4>
        </div>
    </div>

    {{-- CARD 4: AVG --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6
                dark:border-gray-800 dark:bg-gray-900/40
                hover:shadow-md transition-all duration-300">

        <div class="flex items-center justify-center w-12 h-12 rounded-xl
                    bg-gray-100 text-gray-700
                    dark:bg-gray-800 dark:text-white">

            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                <path d="M12 2v20"/>
            </svg>
        </div>

        <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Avg / Transaksi</span>

            <h4 class="mt-2 font-bold text-gray-900 dark:text-white text-xl">
                Rp {{
                    ($comparison['transaksi']['today'] ?? 0) > 0
                        ? number_format(
                            ($comparison['sales']['today'] ?? 0) / $comparison['transaksi']['today'],
                            0, ',', '.'
                        )
                        : 0
                }}
            </h4>
        </div>
    </div>

</div>