export const initChartMonthlyTarget = () => {
    const chartElement = document.querySelector('#chartMonthlyTarget');

    if (!chartElement) return null;

    const progress = window.monthlyProgress || 0;
    const safeProgress = Math.max(0, Math.min(progress, 100));

    const chartOptions = {
        series: [safeProgress],

        colors: ["#465FFF"],

        chart: {
            fontFamily: "Outfit, sans-serif",
            type: "radialBar",
            height: 330,
            sparkline: {
                enabled: true,
            },
        },

        plotOptions: {
            radialBar: {
                startAngle: -90,
                endAngle: 90,

                hollow: {
                    size: "80%", // sedikit lebih balance dari 80%
                },

                track: {
                    background: "#E4E7EC",
                    strokeWidth: "100%",
                    margin: 5,
                },

                dataLabels: {
                    name: {
                        show: false,
                    },

                    value: {
                        fontSize: "40px",      // 🔥 sedikit lebih besar biar lebih “dashboard look”
                        fontWeight: "700",
                        offsetY: 5,           // 🔥 CENTER FIX (ini kunci utama)
                        color: "#1D2939",

                        formatter: function (val) {
                            return val.toFixed(1) + "%";
                        },
                    },
                },
            },
        },

        fill: {
            type: "solid",
            colors: ["#465FFF"],
        },

        stroke: {
            lineCap: "round",
        },

        labels: ["Progress"],
    };

    const chart = new ApexCharts(chartElement, chartOptions);
    chart.render();

    return chart;
};

export default initChartMonthlyTarget;