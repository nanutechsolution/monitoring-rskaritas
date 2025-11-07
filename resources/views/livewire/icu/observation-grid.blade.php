<div class="container mx-auto  space-y-6">
    <div x-data="{
        chartData: JSON.parse($el.getAttribute('data-chart')),
        initChart() {
            const ctx = document.getElementById('icuChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: this.chartData.labels,
                    datasets: this.chartData.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    spanGaps: false,
                    scales: {
                        x: {
                            title: { display: true, text: 'Waktu Input' }
                        },
                        yTtv: {
                            type: 'linear',
                            position: 'left',
                            title: { display: true, text: 'Nadi / RR / Tensi' },
                            min: 0,
                            suggestedMax: 160,
                        },
                        ySuhu: {
                            type: 'linear',
                            position: 'right',
                            title: { display: true, text: 'Suhu (°C)' },
                            min: 30,
                            suggestedMax: 42,
                            grid: { drawOnChartArea: false },
                        },
                    }
                }
            });
        }
    }" x-init="initChart()" data-chart='@json($this->chartData)' class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 sm:p-6 border border-gray-100 dark:border-gray-700">

        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Grafik Tren TTV</h3>
        <div class="h-96">
            <canvas id="icuChart"></canvas>
        </div>
    </div>
</div>
