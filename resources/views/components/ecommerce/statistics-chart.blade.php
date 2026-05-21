<div
    class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">

    {{-- ========================= --}}
    {{-- HEADER --}}
    {{-- ========================= --}}
    <div
        class="mb-6 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <h3
                class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Sales Statistics
            </h3>

            <p
                class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">
                Daily, weekly, and monthly sales analytics
            </p>

        </div>

        {{-- ========================= --}}
        {{-- RANGE TOGGLE --}}
        {{-- ========================= --}}
        <div
            class="inline-flex items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900">

            @foreach($ranges as $item)

                <button
                    type="button"

                    data-range="{{ $item['key'] }}"

                    class="
                        btn-range
                        rounded-md
                        px-3
                        py-2
                        text-theme-sm
                        font-medium
                        transition-all

                        {{ $range === $item['key']
                            ? 'bg-white text-gray-900 shadow-theme-xs dark:bg-gray-800 dark:text-white'
                            : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'
                        }}
                    "
                >

                    {{ $item['label'] }}

                </button>

            @endforeach

        </div>

    </div>

    {{-- ========================= --}}
    {{-- LOADING --}}
    {{-- ========================= --}}
    <div
        id="chartLoading"
        class="hidden py-10 text-center text-sm text-gray-500 dark:text-gray-400">

        Loading statistics...

    </div>

    {{-- ========================= --}}
    {{-- CHART --}}
    {{-- ========================= --}}
    <div
        class="custom-scrollbar max-w-full overflow-x-auto">

        <div
            id="chartThree"
            class="-ml-4 min-w-[700px] pl-2 xl:min-w-full">
        </div>

    </div>

</div>

{{-- ========================= --}}
{{-- APEXCHART --}}
{{-- ========================= --}}
<script>

let chartThree = null;

/**
 * =========================
 * INITIALIZE
 * =========================
 */
document.addEventListener(
    'DOMContentLoaded',
    () => {

        /**
         * =========================
         * ELEMENT
         * =========================
         */
        const chartElement =
            document.querySelector(
                '#chartThree'
            );

        if (!chartElement) {
            return;
        }

        /**
         * =========================
         * INITIAL DATA
         * =========================
         */
        const chartData =
            @json($statistics);

        const defaultRange =
            @json($range);

        /**
         * =========================
         * BUILD DATA
         * =========================
         */
        const parsed =
            buildChartData(
                chartData,
                defaultRange
            );

        /**
         * =========================
         * OPTIONS
         * =========================
         */
        const options = {

            chart: {
                type: 'area',
                height: 350,
                fontFamily: 'Outfit, sans-serif',
                toolbar: {
                    show: false
                }
            },

            stroke: {
                curve: 'smooth',
                width: 3
            },

            dataLabels: {
                enabled: false
            },

            legend: {
                show: true,
                position: 'top'
            },

            series: [
                {
                    name: 'Sales',
                    data: parsed.sales
                },
                {
                    name: 'Unit',
                    data: parsed.units
                }
            ],

            xaxis: {
                categories:
                    parsed.categories,

                axisBorder: {
                    show: false
                },

                axisTicks: {
                    show: false
                }
            },

            yaxis: [
                {
                    title: {
                        text: 'Sales'
                    }
                }
            ],

            grid: {
                borderColor: '#f1f1f1'
            },

            tooltip: {
                shared: true,
                intersect: false
            }
        };

        /**
         * =========================
         * RENDER
         * =========================
         */
        chartThree =
            new ApexCharts(
                chartElement,
                options
            );

        chartThree.render();

        /**
         * =========================
         * TOGGLE BUTTON
         * =========================
         */
        initRangeToggle();
    });

/**
 * =========================
 * RANGE TOGGLE
 * =========================
 */
function initRangeToggle()
{

    const buttons =
        document.querySelectorAll(
            '.btn-range'
        );

    buttons.forEach(button => {

        button.addEventListener(
            'click',
            async () => {

                /**
                 * =========================
                 * ACTIVE BUTTON
                 * =========================
                 */
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

                button.classList.remove(
                    'text-gray-500',
                    'dark:text-gray-400'
                );

                button.classList.add(
                    'bg-white',
                    'text-gray-900',
                    'shadow-theme-xs',
                    'dark:bg-gray-800',
                    'dark:text-white'
                );

                /**
                 * =========================
                 * LOADING
                 * =========================
                 */
                const loading =
                    document.getElementById(
                        'chartLoading'
                    );

                loading.classList.remove(
                    'hidden'
                );

                /**
                 * =========================
                 * RANGE
                 * =========================
                 */
                const range =
                    button.dataset.range;

                try {

                    /**
                     * =========================
                     * FETCH
                     * =========================
                     */
                    const response =
                        await fetch(
                            `/dashboard/statistics?range=${range}`
                        );

                    const result =
                        await response.json();

                    /**
                     * =========================
                     * UPDATE CHART
                     * =========================
                     */
                    updateSalesChart(
                        result.data,
                        range
                    );

                } catch (error) {

                    console.error(
                        'Chart Error:',
                        error
                    );

                } finally {

                    /**
                     * =========================
                     * HIDE LOADING
                     * =========================
                     */
                    loading.classList.add(
                        'hidden'
                    );
                }
            }
        );
    });
}

/**
 * =========================
 * BUILD DATA
 * =========================
 */
function buildChartData(
    data,
    range
) {

    let categories = [];

    /**
     * =========================
     * DAILY
     * =========================
     */
    if (range === 'daily') {

        categories = data.map(item => {

            return new Date(
                item.Tahun,
                item.Bulan - 1,
                item.Hari
            ).toLocaleDateString(
                'id-ID',
                {
                    day: '2-digit',
                    month: 'short'
                }
            );
        });

    /**
     * =========================
     * WEEKLY
     * =========================
     */
    } else if (range === 'weekly') {

        categories =
            data.map(
                item => item.Label
            );

    /**
     * =========================
     * MONTHLY
     * =========================
     */
    } else {

        categories = data.map(item => {

            return new Date(
                item.Tahun,
                item.Bulan - 1
            ).toLocaleDateString(
                'id-ID',
                {
                    month: 'short',
                    year: 'numeric'
                }
            );
        });
    }

    return {

        categories,

        sales: data.map(item =>
            Number(
                item.TotalPenjualan
            )
        ),

        units: data.map(item =>
            Number(
                item.TotalUnit
            )
        )
    };
}

/**
 * =========================
 * UPDATE CHART
 * =========================
 */
function updateSalesChart(
    data,
    range
) {

    if (!chartThree) {
        return;
    }

    const parsed =
        buildChartData(
            data,
            range
        );

    chartThree.updateOptions({

        xaxis: {
            categories:
                parsed.categories
        },

        series: [
            {
                name: 'Sales',
                data: parsed.sales
            },
            {
                name: 'Unit',
                data: parsed.units
            }
        ]
    });
}

</script>