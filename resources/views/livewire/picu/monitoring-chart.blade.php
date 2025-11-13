 <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6 px-4" x-data="{
        chartInstance: null,
        chartConfig: @js($chartData),
        dataExists: false
    }" x-init="
        const isDataAvailable = (data) => {
            return data.datasets.some(dataset =>
                dataset.data.some(value => value !== null && value !== 0 && !isNaN(value))
            );
        };

        const createChart = (data) => {
            if (chartInstance) {
                chartInstance.destroy();
            }

            const ctx = $refs.canvas.getContext('2d');

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { type: 'category', title: { display: true, text: 'Waktu (Jam)' } },
                        y: { beginAtZero: false, title: { display: true, text: 'Nilai' } }
                    },
                    plugins: { legend: { display: true, position: 'top' } },
                    animation: false,
                }
            });
        };

        // 1. Initial Render
        if (isDataAvailable(chartConfig)) {
            dataExists = true;
            createChart(chartConfig);
        }

        // 2. Listener Reaktif (Ini tetap bisa diakses karena wire:ignore ada di dalam)
        $wire.on('chartDataUpdated', (data) => {
            const updatedHasData = isDataAvailable(data);

            if (updatedHasData) {
                dataExists = true;
                if (chartInstance) {
                    chartInstance.data = data;
                    chartInstance.update();
                } else {
                    createChart(data); // Buat ulang jika hilang karena navigasi
                }
            } else {
                if (chartInstance) {
                    chartInstance.destroy();
                    chartInstance = null;
                }
                dataExists = false;
            }
        });
    " wire:key="monitoring-chart-{{ $no_rawat }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
     <x-slot name="header">
         <livewire:patient-header :no-rawat="$no_rawat" />
     </x-slot>
     <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6  py-4">
         <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
             <div class="flex items-center gap-4">
                 <div class="p-3 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-lg flex items-center justify-center">
                     <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c1.657 0 3-1.343 3-3S13.657 2 12 2 9 3.343 9 5s1.343 3 3 3zm-4 4a4 4 0 00-4 4v5h16v-5a4 4 0 00-4-4H8z" />
                     </svg>
                 </div>
                 <div>
                     <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 leading-tight">
                         Monitoring 24 Jam
                         <span class="bg-gradient-to-r from-primary-600 to-primary-700 bg-clip-text text-transparent">PEDIATRIC INTENSIVE CARE UNIT (PICU)</span>
                     </h2>
                     <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau kondisi pasien secara real-time dengan mudah</p>
                 </div>
             </div>
             <div class="flex flex-wrap items-center gap-2 justify-end">
                 <a href="{{ route('patient.picu.history' ,['no_rawat' => str_replace('/', '_', $no_rawat) ]) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-400 text-gray-700 rounded-lg hover:bg-gray-200 shadow transition">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                     </svg>
                     Kembali Ke Riwayat
                 </a>
             </div>
         </div>
     </div>
     <div class="p-6 text-gray-900 dark:text-gray-100">
         <h3 class="text-lg font-semibold border-b dark:border-gray-700 pb-3 mb-4">
             Tren Observasi TTV (Siklus Aktif)
         </h3>
         <div wire:ignore class="relative w-full max-w-full">
             <div class="relative w-full" style="aspect-ratio: video;">
                 <canvas x-show="dataExists" x-ref="canvas" class="w-full h-full"></canvas>
             </div>
         </div>

         <div x-show="!dataExists" class="text-center py-10 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 rounded-md">
             Tidak ada data TTV numerik yang tercatat dalam siklus ini.
         </div>
     </div>
     <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 gap-6 mt-12">
         <livewire:picu.observasi-table :no-rawat="$no_rawat" :cycle-id="$selectedCycleId" wire:key="chart-page-table" />
     </div>
 </div>
