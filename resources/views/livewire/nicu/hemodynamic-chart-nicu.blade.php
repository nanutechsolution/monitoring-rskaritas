<div wire:ignore
     x-data="{
        chart: null,
        darkMode: document.documentElement.classList.contains('dark'),
        colors: {
            primary: '{{ config('tailwindcss.theme.extend.colors.primary.600', '#3b82f6') }}',
            danger: '{{ config('tailwindcss.theme.extend.colors.danger.600', '#dc2626') }}',
            green: '#22c55e',
            yellow: '#eab308',
            purple: '#a855f7',
            pink: '#ec4899'
        },

        setChartColors(isDark) {
            if (!this.chart) return;
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
            const labelColor = isDark ? '#9ca3af' : '#6b7280';
            const titleColor = isDark ? '#d1d5db' : '#374151';
            this.chart.options.plugins.legend.labels.color = labelColor;
            this.chart.options.scales.x.title.color = titleColor;
            this.chart.options.scales.x.ticks.color = labelColor;
            this.chart.options.scales.x.grid.color = gridColor;
            if(this.chart.options.scales.yTtv) {
                this.chart.options.scales.yTtv.title.color = titleColor;
                this.chart.options.scales.yTtv.ticks.color = labelColor;
                this.chart.options.scales.yTtv.grid.color = gridColor;
            }
            if(this.chart.options.scales.ySuhu) {
                this.chart.options.scales.ySuhu.title.color = titleColor;
                this.chart.options.scales.ySuhu.ticks.color = labelColor;
                this.chart.options.scales.ySuhu.grid.color = gridColor;
            }
            this.chart.update('none');
        },

        initChart() {
            const isDark = this.darkMode;
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
            const labelColor = isDark ? '#9ca3af' : '#6b7280';
            const titleColor = isDark ? '#d1d5db' : '#374151';
            const ctx = this.$refs.canvas.getContext('2d');
            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [
                        { label: 'Temp inkubator', data: [], borderColor: this.colors.green, tension: 0.1, spanGaps: true, borderDash: [5, 5], yAxisID: 'ySuhu' },
                        { label: 'Temp Skin', data: [], borderColor: this.colors.yellow, tension: 0.1, spanGaps: true, yAxisID: 'ySuhu' },
                        { label: 'Heart Rate', data: [], borderColor: this.colors.danger, tension: 0.1, spanGaps: true, yAxisID: 'yTtv' },
                        { label: 'Resp. Rate', data: [], borderColor: this.colors.primary, tension: 0.1, spanGaps: true, yAxisID: 'yTtv' },
                        { label: 'Tensi Sistolik', data: [], borderColor: this.colors.purple, fill: false, tension: 0.1, pointRadius: 3, spanGaps: true, pointBackgroundColor: this.colors.purple, yAxisID: 'yTtv' },
                        { label: 'Tensi Diastolik', data: [], borderColor: this.colors.pink, fill: '-1', backgroundColor: 'rgba(236, 72, 153, 0.1)', borderDash: [5, 5], spanGaps: true, tension: 0.1, pointRadius: 3, pointBackgroundColor: this.colors.pink, yAxisID: 'yTtv'}
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, animation: false,
                    interaction: { mode: 'index', intersect: false },
                    scales: {
                        x: {
                            type: 'timeseries',
                            time: { unit: 'minute', displayFormats: { minute: 'HH:mm' } },
                            ticks: { source: 'data', maxRotation: 0, autoSkip: true, color: labelColor },
                            grid: { color: gridColor },
                            title: { display: true, text: 'Waktu', color: titleColor }
                        },
                        yTtv: {
                            type: 'linear', position: 'left', title: { display: true, text: 'Nadi / RR / Tensi', color: titleColor },
                            beginAtZero: false, ticks: { color: labelColor }, grid: { color: gridColor }
                        },
                        ySuhu: {
                            type: 'linear', position: 'right', title: { display: true, text: 'Suhu (°C)', color: titleColor },
                            min: 30, suggestedMax: 42, ticks: { color: labelColor }, grid: { drawOnChartArea: false },
                        }
                    },
                    plugins: {
                        legend: { labels: { color: labelColor } }
                    }
                }
            });
        },

        updateChart(event) {
            if (!this.chart) return;
            const chartData = event.detail.chartData;
            if (chartData === undefined) return; // Guard clause

            this.chart.data.datasets[0].data = chartData.temp_incubator || [];
            this.chart.data.datasets[1].data = chartData.temp_skin || [];
            this.chart.data.datasets[2].data = chartData.hr || [];
            this.chart.data.datasets[3].data = chartData.rr || [];
            this.chart.data.datasets[4].data = chartData.bp_systolic || [];
            this.chart.data.datasets[5].data = chartData.bp_diastolic || [];

            this.chart.update('none');
        }
     }"

    x-init="
        // Tunggu 1 'tick' agar Alpine selesai memuat $refs
        $nextTick(() => {

            initChart(); // 1. Buat kerangka chart

            // 2. Pasang listener dark mode
            $watch('darkMode', (val) => {
                setChartColors(val);
            });

            // 3. TAMBAHKAN INI:
            //    Setelah chart siap, MINTA data ke server.
            $wire.loadChartData();

        });
    "

    {{-- Listener ini sekarang akan menangkap data dari mount() DAN refreshChartData() --}}
    @window:update-hemo-chart.window="updateChart($event)"
    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700 rounded-lg">

    <div class="p-6 text-gray-900 dark:text-gray-100">
        <h3 class="text-lg font-medium text-primary-700 dark:text-primary-300">Tren Hemodinamik</h3>
        <div class="relative mt-4 h-72">
            <canvas x-ref="canvas"></canvas>
        </div>
    </div>
</div>
