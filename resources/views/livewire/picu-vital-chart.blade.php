<div class="bg-white p-4 border rounded-md shadow-sm">
    <h3 class="text-lg font-semibold mb-3">Grafik Trend Tanda Vital 24 Jam</h3>

    <div x-data="{
            chart: null,
            chartData: @js($this->chartData), // Data yang di-pass dari Livewire

            initChart() {
                const ctx = this.$refs.canvas.getContext('2d');

                const data = this.chartData;

                const config = {
                    type: 'line',
                    data: data,
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        stacked: false,
                        scales: {
                            x: {
                                title: { display: true, text: 'Jam Observasi' }
                            },
                            // Sumbu Y1 (Untuk Heart Rate & Resp Rate)
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: { display: true, text: 'Rate (x/mnt)' },
                                min: 0,
                                max: 200 // Maksimum 200 untuk Heart Rate
                            },
                            // Sumbu Y2 (Untuk Suhu)
                            y2: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                title: { display: true, text: 'Suhu (°C)' },
                                min: 30,
                                max: 45,
                                grid: { drawOnChartArea: false } // Jangan gambar grid di area chart
                            },
                            // Sumbu Y3 (Untuk Sat O2)
                            y3: {
                                type: 'linear',
                                display: false, // Sembunyikan untuk menjaga tampilan bersih
                                position: 'right',
                                title: { display: true, text: 'Sat O2 (%)' },
                                min: 70,
                                max: 100,
                                grid: { drawOnChartArea: false }
                            }
                        },
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                };

                this.chart = new Chart(ctx, config);
            },

            // Fungsi untuk update chart saat Livewire data berubah
            updateChart() {
                if (this.chart) {
                    this.chart.data = @js($this->chartData);
                    this.chart.update();
                }
            }
        }"
        x-init="initChart()"
        @cycle-updated.window="updateChart()" {{-- Dengar event dari PicuInputRealtime --}}
        wire:ignore {{-- Pastikan Livewire tidak mengganggu Canvas --}}
    >
        <canvas x-ref="canvas"></canvas>
    </div>
</div>
