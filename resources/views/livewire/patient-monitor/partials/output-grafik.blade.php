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
                    { label: 'Heart Rate', data: chartData.hr,spanGaps: true, borderColor: 'indigo', tension: 0.1, },
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
    }" x-init="init()" x-on:update-chart.window="updateChart($event)" x-on:record-saved.window="showNotification($event)" x-on:error-notification.window="showErrorNotification($event)" class="bg-white overflow-hidden shadow-sm ">
     <div class="p-6 text-gray-900">
         <h3 class="text-lg font-medium">Tren Hemodinamik</h3>
         <div class="relative mt-4 h-72"><canvas x-ref="canvas"></canvas></div>
     </div>
 </div>
