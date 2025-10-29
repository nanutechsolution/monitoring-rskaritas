<div class="container mx-auto  space-y-6">
    {{-- <div class="bg-white shadow rounded-lg p-2 flex space-x-2">
        <button wire:click="$set('filterShift', 'all')" class="px-4 py-2 rounded-md text-sm font-medium {{ $filterShift == 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
    Semua (24 Jam)
    </button>
    <button wire:click="$set('filterShift', 'pagi')" class="px-4 py-2 rounded-md text-sm font-medium {{ $filterShift == 'pagi' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
        Shift Pagi (07-14)
    </button>
    <button wire:click="$set('filterShift', 'siang')" class="px-4 py-2 rounded-md text-sm font-medium {{ $filterShift == 'siang' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
        Shift Siang (14-21)
    </button>
    <button wire:click="$set('filterShift', 'malam')" class="px-4 py-2 rounded-md text-sm font-medium {{ $filterShift == 'malam' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
        Shift Malam (21-07)
    </button>
</div> --}}
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
                            min: 34,
                            suggestedMax: 42,
                            grid: { drawOnChartArea: false },
                        },
                        yCvp: {
                                 type: 'linear',
                                 position: 'right',
                                 title: { display: true, text: 'CVP' },
                                 min: -5,
                                 suggestedMax: 20,
                                 grid: {
                                     drawOnChartArea: false,
                                 },
                                 ticks: {
                                     color: 'rgb(249, 115, 22)',
                                 }
                             }
                    }
                }
            });
        }
    }" x-init="initChart()" data-chart='@json($this->chartData)' class="bg-white shadow rounded-lg p-4">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Grafik Tren TTV</h3>
    <div class="h-96">
        <canvas id="icuChart"></canvas>
    </div>
</div>


</div>
