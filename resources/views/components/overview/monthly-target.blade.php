@php
    function shortNumber($num) {
        $num = (float) $num;

        if ($num >= 1000000000) {
            return round($num / 1000000000, 1) . 'M';
        } elseif ($num >= 1000000) {
            return round($num / 1000000, 1) . 'Jt';
        } elseif ($num >= 1000) {
            return round($num / 1000, 1) . 'K';
        }

        return number_format($num, 0, ',', '.');
    }

    $sales = $monthly['sales'] ?? 0;
    $target = $monthly['target'] ?? 0;
    $progress = min(max($monthly['progress'] ?? 0, 0), 100);
    $remaining = $monthly['remaining'] ?? 0;
@endphp

<div class="rounded-2xl border border-gray-200 bg-gray-100 dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="shadow-default rounded-2xl bg-white px-5 pb-11 pt-5 dark:bg-gray-900 sm:px-6 sm:pt-6">
        <div class="flex justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Monthly Target
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Target you’ve set for this month
                </p>
            </div>

            <x-common.dropdown-menu />
        </div>

        {{-- CHART --}}
        <div class="relative max-h-[195px]">

            <div id="chartMonthlyTarget" class="h-full"></div>

            <span class="absolute left-1/2 top-[65%] -translate-x-1/2 -translate-y-[65%] rounded-full bg-success-50 px-3 py-1 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                {{ number_format($progress, 1) }}%
            </span>

            

        </div>

    
        {{-- DESCRIPTION --}}
        <p class="mx-auto mt-3 max-w-[380px] text-center text-sm text-gray-500 sm:text-base">
            You’ve reached
            <span class="font-semibold text-gray-800 dark:text-white">
                $sales
            </span>
            this month. Keep pushing your target.
        </p>

    </div>

    {{-- FOOTER --}}
    <div class="grid grid-cols-3 divide-x divide-gray-200 dark:divide-gray-800 py-4">

        {{-- TARGET --}}
        <div class="text-center px-3">
            <p class="text-xs text-gray-500 dark:text-gray-400">Target</p>
            <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white/90">
                 {{ shortNumber($target) }}
            </p>
        </div>

        {{-- SALES --}}
        <div class="text-center px-3">
            <p class="text-xs text-gray-500 dark:text-gray-400">Sales</p>
            <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white/90">
                 {{ shortNumber($sales) }}
            </p>
        </div>

        {{-- REMAINING --}}
        <div class="text-center px-3">
            <p class="text-xs text-gray-500 dark:text-gray-400">Remaining</p>
            <p class="mt-1 text-base font-semibold text-gray-800 dark:text-white/90">
                {{ shortNumber($remaining) }}
            </p>
        </div>

    </div>

</div>

{{-- JS DATA --}}
<script>
    window.monthlyProgress = @json($progress);
</script>