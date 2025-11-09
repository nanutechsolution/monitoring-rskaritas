<div wire:ignore
     x-data="{
        chart: null,
        darkMode: document.documentElement.classList.contains('dark'),

        colors: {
            green: '#22c55e',
            yellow: '#eab308',
            danger: '#dc2626',
            primary: '#3b82f6',
            purple: '#a855f7',
            pink: '#ec4899'
        },

        init() {
            // Minta Livewire load data chart saat inisialisasi
            $wire.loadChartData();

            // Watch dark mode untuk update warna chart
            this.$watch('darkMode', (val) => {
                this.updateChartColors(val);
            });
        },

        // Update chart saat Livewire mengirim event
        updateChart(event) {
            const chartData = event.detail.chartData || {};
            if (!chartData) return;

            if (this.chart) this.chart.destroy();

            const ctx = this.$refs.canvas.getContext('2d');

            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [
                        { label: 'Temp inkubator', data: chartData.temp_incubator || [], borderColor: this.colors.green, tension: 0.1, spanGaps: true },
                        { label: 'Temp Skin', data: chartData.temp_skin || [], borderColor: this.colors.yellow, tension: 0.1, spanGaps: true },
                        { label: 'Heart Rate', data: chartData.hr || [], borderColor: this.colors.danger, tension: 0.1, spanGaps: true },
                        { label: 'Resp. Rate', data: chartData.rr || [], borderColor: this.colors.primary, tension: 0.1, spanGaps: true },
                        { label: 'Tensi Sistolik', data: chartData.bp_systolic || [], borderColor: this.colors.purple, fill: false, pointRadius: 3, tension: 0.1, spanGaps: true },
                        { label: 'Tensi Diastolik', data: chartData.bp_diastolic || [], borderColor: this.colors.pink, fill: '-1', backgroundColor: 'rgba(236,72,153,0.2)', borderDash: [5,5], pointRadius: 3, tension: 0.1, spanGaps: true }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    interaction: { mode: 'index', intersect: false },
                    scales: {
                        x: {
                            type: 'timeseries',
                            time: { unit: 'minute', displayFormats: { minute: 'HH:mm' } },
                            ticks: { color: this.darkMode ? '#9ca3af' : '#6b7280', maxRotation: 0, autoSkip: true },
                            grid: { color: this.darkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)' },
                        },
                        y: {
                            beginAtZero: false,
                            ticks: { color: this.darkMode ? '#9ca3af' : '#6b7280' },
                            grid: { color: this.darkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)' }
                        }
                    },
                    plugins: {
                        legend: { labels: { color: this.darkMode ? '#d1d5db' : '#374151' } }
                    }
                }
            });
        },

        // Update warna chart saat dark mode berubah
        updateChartColors(isDark) {
            if (!this.chart) return;

            const gridColor = isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)';
            const labelColor = isDark ? '#9ca3af' : '#6b7280';

            this.chart.options.scales.x.ticks.color = labelColor;
            this.chart.options.scales.x.grid.color = gridColor;
            this.chart.options.scales.y.ticks.color = labelColor;
            this.chart.options.scales.y.grid.color = gridColor;
            this.chart.options.plugins.legend.labels.color = labelColor;

            this.chart.update('none');
        },

        // Notifikasi sukses
        showNotification(event) {
            const type = event.detail?.type || 'success';
            const message = event.detail?.message || '✅ Data berhasil disimpan!';
            const notif = document.createElement('div');
            notif.innerText = message;
            notif.className = `fixed top-5 right-5 py-2 px-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-gray-500 text-white'
            }`;
            document.body.appendChild(notif);
            setTimeout(() => { notif.classList.add('opacity-0'); setTimeout(() => notif.remove(), 300); }, 3000);
        },

        // Notifikasi error
        showErrorNotification(event) {
            const message = event.detail?.message || '❌ Terjadi kesalahan!';
            const notif = document.createElement('div');
            notif.innerText = message;
            notif.className = 'fixed top-5 right-5 bg-red-500 text-white py-2 px-4 rounded-lg shadow-lg z-50';
            document.body.appendChild(notif);
            setTimeout(() => { notif.classList.add('opacity-0'); setTimeout(() => notif.remove(), 300); }, 4000);
        }
     }"
     x-init="init()"
     x-on:update-hemo-chart.window="updateChart($event)"
     x-on:record-saved.window="showNotification($event)"
     x-on:error-notification.window="showErrorNotification($event)"
     class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700 rounded-lg"
>

    <div class="p-6 text-gray-900 dark:text-gray-100">
        <h3 class="text-lg font-medium text-primary-700 dark:text-primary-300">Tren Hemodinamik</h3>
        <div class="relative mt-4 h-72">
            <canvas x-ref="canvas"></canvas>
        </div>
    </div>
</div>
