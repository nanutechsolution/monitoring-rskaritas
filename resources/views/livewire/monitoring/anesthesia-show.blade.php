<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Lihat Monitoring Intra Anestesi</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Pasien: <strong>{{ $pasien->nm_pasien }}</strong> (RM: {{ $pasien->no_rkm_medis }})
            </p>
        </div>
        <div class="flex space-x-2 mt-4 sm:mt-0">
            <!-- Tombol Kembali (Netral) -->
            <a href="{{ route('monitoring.anestesi.history', ['noRawat' => str_replace('/', '_', $monitoring->no_rawat)]) }}" wire:navigate
               class="inline-flex items-center px-4 py-2
                      bg-gray-200 dark:bg-gray-700
                      text-gray-800 dark:text-gray-200
                      font-medium rounded-lg shadow-sm
                      hover:bg-gray-300 dark:hover:bg-gray-600 transition
                      focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2
                      dark:focus:ring-offset-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <!-- Tombol Cetak (Aksi Positif) -->
            <a href="{{ route('monitoring.anestesi.print', ['monitoringId' => $monitoring->id]) }}" target="_blank"
               class="inline-flex items-center px-4 py-2
                      bg-green-600 dark:bg-green-700
                      text-white font-medium rounded-lg shadow-md
                      hover:bg-green-700 dark:hover:bg-green-600 transition
                      focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2
                      dark:focus:ring-offset-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17H7V7h10v10z" />
                </svg>
                Cetak
            </a>
        </div>
    </div>

    <!-- Grafik Vital (Sekarang dark-mode aware) -->
    @if(!empty($monitoring->vitals) && $monitoring->vitals->count() > 0)
    <fieldset class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 mb-6 border border-gray-100 dark:border-gray-700">
        <legend class="px-2 text-xl font-semibold text-primary-600 dark:text-primary-400">Grafik Pemantauan Vital</legend>
        <div class="mt-4">
            <div wire:ignore
                 x-data="{
                    chart: null,
                    darkMode: document.documentElement.classList.contains('dark'),

                    setChartColors(isDark) {
                        if (!this.chart) return;
                        const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                        const labelColor = isDark ? '#9ca3af' : '#6b7280';
                        const titleColor = isDark ? '#d1d5db' : '#374151';

                        this.chart.options.plugins.legend.labels.color = labelColor;
                        this.chart.options.plugins.title.color = titleColor;
                        this.chart.options.scales.y.ticks.color = labelColor;
                        this.chart.options.scales.y.grid.color = gridColor;
                        this.chart.options.scales.x.ticks.color = labelColor;
                        this.chart.options.scales.x.grid.color = gridColor;
                        this.chart.update();
                    },

                    initChart() {
                        const isDark = this.darkMode;
                        const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                        const labelColor = isDark ? '#9ca3af' : '#6b7280';
                        const titleColor = isDark ? '#d1d5db' : '#374151';

                        const labels = @js($chartLabels ?? []);
                        const nadi = @js($chartDataNadi ?? []);
                        const sistolik = @js($chartDataSistolik ?? []);
                        const diastolik = @js($chartDataDiastolik ?? []);
                        const rr = @js($chartDataRR ?? []);

                        const ctx = document.getElementById('anesthesiaChartShow').getContext('2d');
                        this.chart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [
                                    { label: 'Nadi (RRN)', data: nadi, borderColor: '#EF4444', backgroundColor: 'rgba(239,68,68,0.2)', pointStyle: 'circle', radius: 5, tension: 0.1, fill: true },
                                    { label: 'Sistolik', data: sistolik, borderColor: '#3B82F6', backgroundColor: 'rgba(59,130,246,0.2)', pointStyle: 'triangle', radius: 7, tension: 0.2, fill: true },
                                    { label: 'Diastolik', data: diastolik, borderColor: '#10B981', backgroundColor: 'rgba(16,185,129,0.2)', pointStyle: 'triangle', rotation: 180, radius: 7, tension: 0.1, fill: true },
                                    { label: 'RR', data: rr, borderColor: '#F59E0B', backgroundColor: 'rgba(245,158,11,0.2)', pointStyle: 'crossRot', radius: 7, tension: 0.2, fill: true }
                                ]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { position: 'top', labels: { color: labelColor } },
                                    title: { display: true, text: 'Grafik Vital Pasien', color: titleColor }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: false,
                                        ticks: { color: labelColor },
                                        grid: { color: gridColor }
                                    },
                                    x: {
                                        ticks: { color: labelColor },
                                        grid: { color: gridColor }
                                    }
                                }
                            }
                        });

                        // Watch for dark mode changes
                        $watch('darkMode', (val) => {
                            this.setChartColors(val);
                        });
                    }
                 }"
                 x-init="initChart()">
                <canvas id="anesthesiaChartShow" class="w-full h-64 rounded-lg"></canvas>
            </div>
        </div>
    </fieldset>
    @endif

    <!-- Data Pasien & Staf -->
    <fieldset class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 mb-6 border border-gray-100 dark:border-gray-700">
        <legend class="px-2 text-xl font-semibold text-primary-600 dark:text-primary-400">Data Pasien & Staf</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama Pasien</label>
                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $pasien->nm_pasien }}</p>
            </div>
            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">No. RM</label>
                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $pasien->no_rkm_medis }}</p>
            </div>
            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Dokter Anestesi</label>
                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $monitoring->dokterAnestesi->nm_dokter ?? 'N/A' }}</p>
            </div>
            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Penata Anestesi</label>
                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $monitoring->penataAnestesi->nama ?? 'N/A' }}</p>
            </div>
        </div>
    </fieldset>

    <!-- Grid dua kolom: Persiapan & Tabel -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Kiri: Persiapan, Jalan Nafas, Regional -->
        <div class="space-y-6">
            <fieldset class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6 border border-gray-100 dark:border-gray-700">
                <legend class="px-2 text-lg font-semibold text-primary-600 dark:text-primary-400">Persiapan & Waktu</legend>
                <div class="space-y-2 mt-2 text-gray-700 dark:text-gray-300">
                    <p><strong class="text-gray-600 dark:text-gray-400">Infus 1:</strong> {{ $monitoring->infus_perifer_1_tempat_ukuran ?? '-' }}</p>
                    <p><strong class="text-gray-600 dark:text-gray-400">Infus 2:</strong> {{ $monitoring->infus_perifer_2_tempat_ukuran ?? '-' }}</p>
                    <p><strong class="text-gray-600 dark:text-gray-400">Posisi:</strong> {{ $monitoring->posisi ?? '-' }}</p>
                    <p><strong class="text-gray-600 dark:text-gray-400">Mulai Anestesi:</strong> {{ $monitoring->mulai_anestesia ? $monitoring->mulai_anestesia->format('d-m-Y H:i') : '-' }}</p>
                    <p><strong class="text-gray-600 dark:text-gray-400">Mulai Pembedahan:</strong> {{ $monitoring->mulai_pembedahan ? $monitoring->mulai_pembedahan->format('d-m-Y H:i') : '-' }}</p>
                </div>
            </fieldset>

            <fieldset class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6 border border-gray-100 dark:border-gray-700">
                <legend class="px-2 text-lg font-semibold text-primary-600 dark:text-primary-400">Jalan Nafas & Intubasi</legend>
                <div class="space-y-2 mt-2 text-gray-700 dark:text-gray-300">
                    <p><strong class="text-gray-600 dark:text-gray-400">Jalan Nafas:</strong>
                        {{ $monitoring->jalan_nafas_facemask_no ? 'Face Mask No.'.$monitoring->jalan_nafas_facemask_no : '' }}
                        {{ $monitoring->jalan_nafas_oro_nasopharing ? 'Oro/Naso' : '' }}
                        {{ $monitoring->jalan_nafas_ett_no ? 'ETT No.'.$monitoring->jalan_nafas_ett_no : '' }}
                    </p>
                    <p><strong class="text-gray-600 dark:text-gray-400">Sulit Ventilasi:</strong> {{ $monitoring->sulit_ventilasi ? 'Ya' : 'Tidak' }}</p>
                    <p><strong class="text-gray-600 dark:text-gray-400">Sulit Intubasi:</strong> {{ $monitoring->sulit_intubasi ? 'Ya' : 'Tidak' }}</p>
                </div>
            </fieldset>

            <fieldset class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6 border border-gray-100 dark:border-gray-700">
                <legend class="px-2 text-lg font-semibold text-primary-600 dark:text-primary-400">Teknik Regional</legend>
                <div class="space-y-2 mt-2 text-gray-700 dark:text-gray-300">
                    <p><strong class="text-gray-600 dark:text-gray-400">Jenis:</strong> {{ $monitoring->regional_jenis ?? '-' }}</p>
                    <p><strong class="text-gray-600 dark:text-gray-400">Lokasi:</strong> {{ $monitoring->regional_lokasi ?? '-' }}</p>
                    <p><strong class="text-gray-600 dark:text-gray-400">Hasil:</strong> {{ $monitoring->regional_hasil ?? '-' }}</p>
                </div>
            </fieldset>
        </div>

        <!-- Kanan: Tabel Vital & Obat -->
        <div class="space-y-6">
            <fieldset class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6 border border-gray-100 dark:border-gray-700 overflow-x-auto">
                <legend class="px-2 text-lg font-semibold text-primary-600 dark:text-primary-400">Tabel Tanda Vital</legend>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mt-2">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr class="text-center text-xs text-gray-500 dark:text-gray-300 uppercase">
                            <th class="px-2 py-2">Waktu</th>
                            <th class="px-2 py-2">Nadi</th>
                            <th class="px-2 py-2">Sis/Dis</th>
                            <th class="px-2 py-2">RR</th>
                            <th class="px-2 py-2">SpO2</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-center text-gray-700 dark:text-gray-300">
                        @forelse($monitoring->vitals as $vital)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-2 py-1 text-sm">{{ $vital->waktu }}</td>
                            <td class="px-2 py-1 text-sm">{{ $vital->rrn ?? '-' }}</td>
                            <td class="px-2 py-1 text-sm">{{ $vital->td_sis ?? '-' }}/{{ $vital->td_dis ?? '-' }}</td>
                            <td class="px-2 py-1 text-sm">{{ $vital->rr ?? '-' }}</td>
                            <td class="px-2 py-1 text-sm">{{ $vital->spo2 ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-2 text-gray-500 dark:text-gray-400">Tidak ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </fieldset>

            <fieldset class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6 border border-gray-100 dark:border-gray-700 overflow-x-auto">
                <legend class="px-2 text-lg font-semibold text-primary-600 dark:text-primary-400">Obat-obatan / Infus</legend>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mt-2">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr class="text-center text-xs text-gray-500 dark:text-gray-300 uppercase">
                            <th class="px-2 py-2">Waktu</th>
                            <th class="px-2 py-2">Nama Obat</th>
                            <th class="px-2 py-2">Dosis</th>
                            <th class="px-2 py-2">Rute</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-center text-gray-700 dark:text-gray-300">
                        @forelse($monitoring->medications as $med)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-2 py-1 text-sm">{{ $med->waktu }}</td>
                            <td class="px-2 py-1 text-sm">{{ $med->nama_obat_infus_gas }}</td>
                            <td class="px-2 py-1 text-sm">{{ $med->dosis ?? '-' }}</td>
                            <td class="px-2 py-1 text-sm">{{ $med->rute ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-2 text-gray-500 dark:text-gray-400">Tidak ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </fieldset>
        </div>
    </div>
</div>
