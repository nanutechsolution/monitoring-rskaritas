<div
    x-data="{ 
        chart: null,
        chartData: @js($this->chartData), 
        
        initChart() {
            if (this.chart) this.chart.destroy(); 
            
            const ctx = this.$refs.canvas.getContext('2d'); 

            this.chart = new Chart(ctx, {
                type: 'line',
                data: this.chartData, // Data (sudah berisi labels & datasets)
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { 
                        mode: 'index', // Ganti ke 'index' (lebih baik utk kategori)
                        intersect: false 
                    },
                    scales: {
                        x: {
                            type: 'category', 
                            title: { display: true, text: 'Waktu Observasi (Data Aktual)' },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        yTemp: { 
                            position: 'left', min: 30, max: 45,
                            title: { display: true, text: 'Suhu (°C)', color: '#10B981' },
                            ticks: { color: '#10B981' },
                            grid: { color: (ctx) => ctx.tick.value >= 38 ? 'rgba(255,0,0,0.3)' : 'rgba(0,0,0,0.05)' }
                        },
                        yRate: { 
                            position: 'left', min: 0, max: 200, offset: 50, 
                            title: { display: true, text: 'HR / RR / Tensi', color: '#EF4444' },
                            ticks: { color: '#EF4444' },
                            grid: { drawOnChartArea: false }
                        },
                        ySupport: { 
                            position: 'right', min: 0, max: 100,
                            title: { display: true, text: 'Support (SpO2 / FiO2 / PEEP)', color: '#6366F1' },
                            ticks: { color: '#6366F1' },
                            grid: { drawOnChartArea: false }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { usePointStyle: true, boxWidth: 8, color: '#374151' }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: '#fff', bodyColor: '#fff',
                            callbacks: {
                                label: (context) => `${context.dataset.label}: ${context.parsed.y}`
                            }
                        }
                    },
                    elements: {
                        point: { radius: 4, hoverRadius: 6, borderWidth: 2 },
                        line: { tension: 0.3, borderWidth: 2 }
                    }
                }
            });
        },

        updateChart(newData) {
            this.chartData = newData; 
            this.chart.data = newData; // Set data baru
            this.chart.update('none'); 
        }
    }"
    
    x-init="initChart()"
    @refresh-chart.window="updateChart($event.detail.data)"
    
    class="p-4 bg-gradient-to-br from-blue-50 to-white rounded-2xl shadow-lg"
    wire:ignore 
>
    <div class="flex justify-between items-center mb-3">
        <h2 class="text-xl font-semibold text-gray-800">📈 Tren Vital Signs & Intervensi</h2>
    </div>

    <div class="relative h-[450px]">
        <canvas x-ref="canvas" style="height: 450px;"></canvas>
    </div>
</div>