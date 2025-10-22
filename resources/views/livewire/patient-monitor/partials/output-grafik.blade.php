 {{-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
     <div @class([ 'p-4 rounded-lg shadow-md text-white transition-colors duration-300' , 'bg-red-600'=> $latestHR > 160 || $latestHR < 100, 'bg-yellow-600'=> ($latestHR >= 100 && $latestHR <= 110) || ($latestHR>= 150 && $latestHR <= 160), 'bg-green-600'=> $latestHR > 110 && $latestHR < 150, 'bg-gray-400'=> is_null(value: $latestHR)
                         ])>
                         <div class="text-sm font-semibold uppercase">Heart Rate (HR)</div>
                         <div class="text-3xl font-bold">
                             {{ $latestHR ?? 'N/A' }} <span class="text-lg font-normal">bpm</span>
                         </div>
                         <div class="text-xs mt-1">
                             Normal: 100 - 160
                         </div>
     </div>

     <div @class([ 'p-4 rounded-lg shadow-md text-white transition-colors duration-300' , 'bg-red-600'=> $latestMAP < 40, 'bg-yellow-600'=> $latestMAP >= 40 && $latestMAP < 45, 'bg-green-600'=> $latestMAP >= 45,
                 'bg-gray-400' => is_null($latestMAP)
                 ])>
                 <div class="text-sm font-semibold uppercase">Tensi & MAP</div>
                 <div class="text-3xl font-bold">
                     {{ $latestBPSystolic ?? 'N/A' }}/{{ $latestBPDiastolic ?? 'N/A' }}
                 </div>
                 <div class="text-sm mt-1 font-semibold">
                     MAP: {{ $latestMAP ?? 'N/A' }} mmHg
                 </div>
                 <div class="text-xs">
                     Target MAP: 40 - 60
                 </div>
     </div>

     <div @class([ 'p-4 rounded-lg shadow-md text-white transition-colors duration-300' , 'bg-red-600'=> $latestRR > 60 || $latestRR < 20, 'bg-green-600'=> $latestRR >= 20 && $latestRR <= 60, 'bg-gray-400'=> is_null($latestRR)
                 ])>
                 <div class="text-sm font-semibold uppercase">Resp. Rate (RR)</div>
                 <div class="text-3xl font-bold">
                     {{ $latestRR ?? 'N/A' }} <span class="text-lg font-normal">x/menit</span>
                 </div>
                 <div class="text-xs mt-1">
                     Normal (Neonatus): 20 - 60
                 </div>
     </div>

     <div @class([ 'p-4 rounded-lg shadow-md text-white transition-colors duration-300' , 'bg-red-600'=> $latestTempSkin > 37.5 || $latestTempSkin < 36.5, 'bg-green-600'=> $latestTempSkin >= 36.5 && $latestTempSkin <= 37.5, 'bg-gray-400'=> is_null($latestTempSkin) && is_null($latestTempIncubator)
                 ])>
                 <div class="text-sm font-semibold uppercase">Temp. Kulit / Inkubator</div>
                 <div class="text-3xl font-bold">
                     {{ $latestTempSkin ? number_format($latestTempSkin, 1) : 'N/A' }} <span class="text-lg font-normal">&deg;C</span>
                 </div>
                 <div class="text-xs mt-1">
                     Inkubator: {{ $latestTempIncubator ? number_format($latestTempIncubator, 1) : 'N/A' }} &deg;C
                 </div>
                 <div class="text-xs">
                     Target Kulit: 36.5 - 37.5 &deg;C
                 </div>
     </div>
 </div> --}}
 <div wire:ignore x-data="{
        chart: null,
        init() {
            $wire.loadRecords();
        },
        updateChart(event) {
            if (this.chart) {
                this.chart.destroy();
            }
            const chartData = event.detail[0].chartData;
            const ctx = this.$refs.canvas.getContext('2d');
            this.chart = new Chart(ctx, {
                type: 'line',
                data: { datasets: [
                    { label: 'Temp inkubator', data: chartData.temp_incubator, borderColor: 'green', tension: 0.1, spanGaps: true, },
                    { label: 'Temp Skin', data: chartData.temp_skin,spanGaps: true, borderColor: 'blue', tension: 0.1, },
                    { label: 'Heart Rate', data: chartData.hr,spanGaps: true, borderColor: 'red', tension: 0.1, },
                    { label: 'Resp. Rate', data: chartData.rr,spanGaps: true, borderColor: 'black', tension: 0.1, },
                    {label: 'Tensi Sistolik',data: chartData.bp_systolic,borderColor: 'rgba(255,0,0,1)', fill: false,
                        tension: 0.1,
                        pointRadius: 3,
                        spanGaps: true,
                        pointBackgroundColor: 'rgba(255,0,0,1)',
                    },
                    {
                        label: 'Tensi Diastolik',
                        data: chartData.bp_diastolic,
                        borderColor: 'rgba(255,0,0,0.6)',
                        fill: '-1',
                        backgroundColor: 'rgba(255,0,0,0.2)',
                        borderDash: [5,5],
                        spanGaps: true,
                        tension: 0.1,
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(255,0,0,0.6)',
                    }
                ] },
                options: {
                    responsive: true, maintainAspectRatio: false, animation: false,
                    scales: {
                        x: {
                            type: 'timeseries',
                            time: { unit: 'minute', displayFormats: { minute: 'HH:mm' } },
                            ticks: { source: 'data', maxRotation: 0, autoSkip: true }
                        },
                        y: { beginAtZero: false }
                    }
                }
            });
        },

        showNotification(event) {
            const message = event.detail[0]?.message || '✅ Data berhasil disimpan!';
            const notif = document.createElement('div');
            notif.innerText = message;
            notif.className = 'fixed top-5 right-5 bg-green-500 text-white py-2 px-4 rounded-lg shadow-lg transition-opacity duration-300 z-50';
            document.body.appendChild(notif);
            setTimeout(() => {
                notif.classList.add('opacity-0');
                setTimeout(() => notif.remove(), 300);
            }, 3000);
        },
        showErrorNotification(event) {
            const message = event.detail[0]?.message || 'Terjadi kesalahan!';
            const notif = document.createElement('div');
            notif.innerText = `❌ ${message}`;
            notif.className = 'fixed top-5 right-5 bg-red-500 text-white py-2 px-4 rounded-lg shadow-lg transition-opacity duration-300 z-50';
            document.body.appendChild(notif);
            setTimeout(() => {
                notif.classList.add('opacity-0');
                setTimeout(() => notif.remove(), 300);
            }, 4000);
        }
    }" x-init="init()" x-on:update-chart.window="updateChart($event)" x-on:record-saved.window="showNotification($event)" x-on:error-notification.window="showErrorNotification($event)" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
     <div class="p-6 text-gray-900">
         <h3 class="text-lg font-medium">Tren Hemodinamik</h3>
         <div class="relative mt-4 h-64"><canvas x-ref="canvas"></canvas></div>
     </div>
 </div>
