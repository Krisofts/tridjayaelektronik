export const initChartThree = () => {

    const chartElement = document.querySelector('#chartThree');

    if (chartElement) {

        const chartData = JSON.parse(
            chartElement.dataset.chart || '[]'
        );

        const categories = chartData.map(item => item.month);

        const salesData = chartData.map(item => item.sales);

        const unitData = chartData.map(item => item.unit);

        // Format angka singkat tanpa .0
        const formatValue = (number) => {
            return parseFloat(number.toFixed(1));
        };

        const formatShortNumber = (value) => {

            if (value >= 1_000_000_000_000) {
                return formatValue(value / 1_000_000_000_000) + 'T';
            }

            if (value >= 1_000_000_000) {
                return formatValue(value / 1_000_000_000) + 'M';
            }

            if (value >= 1_000_000) {
                return formatValue(value / 1_000_000) + 'J';
            }

            if (value >= 1_000) {
                return formatValue(value / 1_000) + 'K';
            }

            return value;
        };

        const chartThreeOptions = {

            series: [
                {
                    name: "Sales",
                    data: salesData,
                },
                {
                    name: "Unit",
                    data: unitData,
                },
            ],

            legend: {
                show: true,
                position: "top",
                horizontalAlign: "left",
            },

            colors: ["#465FFF", "#9CB9FF"],

            chart: {
                fontFamily: "Outfit, sans-serif",
                height: 310,
                type: "area",

                toolbar: {
                    show: false,
                },

                dropShadow: {
                    enabled: true,
                    top: 2,
                    left: 0,
                    blur: 4,
                    opacity: 0.1,
                },
            },

            fill: {
                gradient: {
                    enabled: true,
                    opacityFrom: 0.55,
                    opacityTo: 0,
                },
            },

            stroke: {
                curve: "smooth",
                lineCap: "round",
                width: [2, 2],
            },

            markers: {
                size: 0,
            },

            grid: {
                xaxis: {
                    lines: {
                        show: false,
                    },
                },
                yaxis: {
                    lines: {
                        show: true,
                    },
                },
            },

            dataLabels: {
                enabled: false,
            },

            tooltip: {
                shared: true,

                y: {
                    formatter: function(value, { seriesIndex }) {

                        if (seriesIndex === 0) {
                            return 'Rp ' +
                                new Intl.NumberFormat('id-ID')
                                    .format(value);
                        }

                        return new Intl.NumberFormat('id-ID')
                            .format(value) + ' Unit';
                    }
                }
            },

            xaxis: {
                type: "category",

                categories: categories,

                axisBorder: {
                    show: false,
                },

                axisTicks: {
                    show: false,
                },

                tooltip: false,
            },

            yaxis: [
                {
                    labels: {
                        formatter: function(value) {
                            return 'Rp ' +
                                formatShortNumber(value);
                        }
                    }
                },
                {
                    opposite: true,

                    labels: {
                        formatter: function(value) {
                            return formatShortNumber(value);
                        }
                    }
                }
            ],
        };

        chartElement.innerHTML = "";

        const chart = new ApexCharts(
            chartElement,
            chartThreeOptions
        );

        chart.render();

        return chart;
    }
}

export default initChartThree;