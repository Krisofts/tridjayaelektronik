<div
    class="relative rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">

    {{-- ========================= --}}
    {{-- HEADER --}}
    {{-- ========================= --}}
    <div class="mb-6 flex flex-col gap-5 sm:flex-row sm:justify-between">

        <div class="w-full">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Sales Statistics
            </h3>

            <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">
                Daily (30 days), Weekly (12 weeks), Monthly (12 months)
            </p>
        </div>

        {{-- ========================= --}}
        {{-- RANGE TOGGLE --}}
        {{-- ========================= --}}
        <div class="inline-flex items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900">

            @foreach($ranges as $item)

                <button
                    type="button"
                    data-range="{{ $item['key'] }}"
                    class="btn-range px-3 py-2 rounded-md text-theme-sm font-medium transition
                        {{ $range === $item['key']
                            ? 'bg-white text-gray-900 shadow-theme-xs dark:bg-gray-800 dark:text-white'
                            : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'
                        }}"
                >
                    {{ $item['label'] }}
                </button>

            @endforeach

        </div>
    </div>

    {{-- ========================= --}}
    {{-- LOADING OVERLAY --}}
    {{-- ========================= --}}
    <div
        id="chartLoading"
        class="hidden absolute inset-0 z-10 flex items-center justify-center rounded-2xl bg-white/60 dark:bg-black/40 backdrop-blur-sm">

        <div class="text-sm font-medium text-gray-600 dark:text-gray-300">
            Loading statistics...
        </div>

    </div>

    {{-- ========================= --}}
    {{-- CHART --}}
    {{-- ========================= --}}
    <div class="relative">

        <div class="custom-scrollbar max-w-full overflow-x-auto">

            <div
                id="chartThree"
                class="-ml-4 min-w-[700px] pl-2 xl:min-w-full">
            </div>

        </div>

    </div>

</div>

{{-- ========================= --}}
{{-- SCRIPT --}}
{{-- ========================= --}}
<script>

document.addEventListener('DOMContentLoaded', () => {

    const buttons = document.querySelectorAll('.btn-range');
    const loading = document.getElementById('chartLoading');

    const setActiveButton = (activeBtn) => {

        buttons.forEach(btn => {

            btn.classList.remove(
                'bg-white',
                'text-gray-900',
                'shadow-theme-xs',
                'dark:bg-gray-800',
                'dark:text-white'
            );

            btn.classList.add(
                'text-gray-500',
                'dark:text-gray-400'
            );
        });

        activeBtn.classList.add(
            'bg-white',
            'text-gray-900',
            'shadow-theme-xs',
            'dark:bg-gray-800',
            'dark:text-white'
        );

        activeBtn.classList.remove(
            'text-gray-500',
            'dark:text-gray-400'
        );
    };

    const loadChart = async (range, activeBtn) => {

        try {

            // disable all buttons (anti spam click)
            buttons.forEach(btn => btn.disabled = true);

            loading.classList.remove('hidden');

            const response = await fetch(
                `/dashboard/statistics?range=${range}`
            );

            const result = await response.json();

            if (!result?.data) return;

            updateSalesChart(result.data, range);

        } catch (error) {

            console.error('Sales Chart Error:', error);

        } finally {

            loading.classList.add('hidden');

            buttons.forEach(btn => btn.disabled = false);
        }
    };

    buttons.forEach(button => {

        button.addEventListener('click', () => {

            const range = button.dataset.range;

            setActiveButton(button);

            loadChart(range, button);
        });
    });

});

</script>