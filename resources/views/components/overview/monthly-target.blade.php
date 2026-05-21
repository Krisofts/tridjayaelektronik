@php
    $target            = $summary['target'] ?? 0;
    $sales             = $summary['sales'] ?? 0;
    $achievement       = $summary['achievement'] ?? 0;
    $remainingTarget   = $summary['remaining_target'] ?? 0;
    $dailyRunRate      = $summary['daily_run_rate'] ?? 0;
    $projectedClosing  = $summary['projected_closing'] ?? 0;
    $transactions      = $summary['transactions'] ?? 0;

    $isTargetReached = $projectedClosing >= $target;

    $progressWidth = min(max($achievement, 0), 100);

    $statusLabel = $isTargetReached
        ? 'On Track'
        : 'Need Push';

    $statusClasses = $isTargetReached
        ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400'
        : 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400';

    $progressClasses = $isTargetReached
        ? 'bg-emerald-500'
        : 'bg-amber-500';
@endphp

<div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">

    <div class="p-5">

        {{-- HEADER --}}
        <div class="flex items-start justify-between gap-3 mb-5">

            <div>
                <div class="flex items-center gap-2 mb-1">

                    <h2 class="text-sm font-semibold tracking-tight text-gray-900 dark:text-white">
                        Monthly Target
                    </h2>

                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-medium {{ $statusClasses }}">
                        {{ $statusLabel }}
                    </span>

                </div>

                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ now()->format('F Y') }}
                </p>
            </div>

            <div class="text-right">

                <div class="text-[10px] uppercase tracking-wider text-gray-400 mb-1">
                    Achievement
                </div>

                <div class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ number_format($achievement, 1) }}%
                </div>

            </div>

        </div>

        {{-- SALES --}}
        <div class="mb-5">

            <div class="text-[10px] uppercase tracking-[0.18em] text-gray-400 mb-2">
                Current Sales
            </div>

            <div class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white leading-none">
                Rp {{ number_format($sales, 0, ',', '.') }}
            </div>

            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                Target:
                <span class="font-medium text-gray-700 dark:text-gray-300">
                    Rp {{ number_format($target, 0, ',', '.') }}
                </span>
            </div>

        </div>

        {{-- PROGRESS --}}
        <div class="mb-5">

            <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                <div
                    class="h-full rounded-full transition-all duration-700 {{ $progressClasses }}"
                    style="width: {{ $progressWidth }}%">
                </div>
            </div>

            <div class="mt-2 flex items-center justify-between text-[11px]">

                <div class="text-gray-500 dark:text-gray-400">
                    Remaining
                </div>

                <div class="font-medium text-gray-900 dark:text-white">
                    Rp {{ number_format($remainingTarget, 0, ',', '.') }}
                </div>

            </div>

        </div>

        {{-- STATS --}}
        <div class="grid grid-cols-3 gap-3">

            {{-- RUN RATE --}}
            <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-800/40 p-3">

                <div class="text-[10px] uppercase tracking-wider text-gray-400 mb-1">
                    Run Rate
                </div>

                <div class="text-sm font-semibold tracking-tight text-gray-900 dark:text-white">
                    Rp {{ number_format($dailyRunRate, 0, ',', '.') }}
                </div>

            </div>

            {{-- PROJECTED --}}
            <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-800/40 p-3">

                <div class="text-[10px] uppercase tracking-wider text-gray-400 mb-1">
                    Projection
                </div>

                <div class="text-sm font-semibold tracking-tight text-gray-900 dark:text-white">
                    Rp {{ number_format($projectedClosing, 0, ',', '.') }}
                </div>

            </div>

            {{-- TRANSACTIONS --}}
            <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-800/40 p-3">

                <div class="text-[10px] uppercase tracking-wider text-gray-400 mb-1">
                    Transactions
                </div>

                <div class="text-sm font-semibold tracking-tight text-gray-900 dark:text-white">
                    {{ number_format($transactions) }}
                </div>

            </div>

        </div>

    </div>

</div>