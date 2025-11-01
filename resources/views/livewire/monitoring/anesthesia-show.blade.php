<div class="max-w-7xl mx-auto p-4 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Lihat Monitoring Intra Anestesi</h1>
            <p class="text-gray-500 mt-1">
                Pasien: <strong>{{ $pasien->nm_pasien }}</strong> (RM: {{ $pasien->no_rkm_medis }})
            </p>
        </div>
        <div class="flex space-x-2 mt-4 sm:mt-0">
            <a href="{{ route('monitoring.anestesi.history', ['noRawat' => str_replace('/', '_', $monitoring->no_rawat)]) }}" wire:navigate class="flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg shadow-md hover:bg-gray-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <a href="{{ route('monitoring.anestesi.print', ['monitoringId' => $monitoring->id]) }}" target="_blank" class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17H7V7h10v10z" />
                </svg>
                Cetak
            </a>
        </div>
    </div>

    <!-- Grafik Vital -->
    @if(!empty($monitoring->vitals) && $monitoring->vitals->count() > 0)
    <fieldset class="bg-white shadow-xl rounded-xl p-6 mb-6 border border-gray-100">
        <legend class="px-2 text-xl font-semibold text-blue-600">Grafik Pemantauan Vital</legend>
        <div class="mt-4">
            <div wire:ignore x-data x-init="
                const labels = @js($chartLabels ?? []);
                const nadi = @js($chartDataNadi ?? []);
                const sistolik = @js($chartDataSistolik ?? []);
                const diastolik = @js($chartDataDiastolik ?? []);
                const rr = @js($chartDataRR ?? []);
                const ctx = document.getElementById('anesthesiaChartShow').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            { label: 'Nadi (RRN)', data: nadi, borderColor: '#EF4444', backgroundColor: 'rgba(239,68,68,0.2)', tension: 0.2, fill: true },
                            { label: 'Sistolik', data: sistolik, borderColor: '#3B82F6', backgroundColor: 'rgba(59,130,246,0.2)', tension: 0.2, fill: true },
                            { label: 'Diastolik', data: diastolik, borderColor: '#10B981', backgroundColor: 'rgba(16,185,129,0.2)', tension: 0.2, fill: true },
                            { label: 'RR', data: rr, borderColor: '#F59E0B', backgroundColor: 'rgba(245,158,11,0.2)', tension: 0.2, fill: true }
                        ]
                    },
                    options: { responsive: true, plugins: { legend: { position: 'top' }, title: { display: true, text: 'Grafik Vital Pasien' } }, scales: { y: { beginAtZero: false }, x: {} } }
                });
            ">
                <canvas id="anesthesiaChartShow" class="w-full h-64 rounded-lg"></canvas>
            </div>
        </div>
    </fieldset>
    @endif

    <!-- Data Pasien & Staf -->
    <fieldset class="bg-white shadow-xl rounded-xl p-6 mb-6 border border-gray-100">
        <legend class="px-2 text-xl font-semibold text-blue-600">Data Pasien & Staf</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div class="p-3 bg-gray-50 rounded-lg">
                <label class="block text-sm font-medium text-gray-500">Nama Pasien</label>
                <p class="text-lg font-semibold">{{ $pasien->nm_pasien }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-lg">
                <label class="block text-sm font-medium text-gray-500">No. RM</label>
                <p class="text-lg font-semibold">{{ $pasien->no_rkm_medis }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-lg">
                <label class="block text-sm font-medium text-gray-500">Dokter Anestesi</label>
                <p class="text-lg font-semibold">{{ $monitoring->dokterAnestesi->nm_dokter ?? 'N/A' }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-lg">
                <label class="block text-sm font-medium text-gray-500">Penata Anestesi</label>
                <p class="text-lg font-semibold">{{ $monitoring->penataAnestesi->nama ?? 'N/A' }}</p>
            </div>
        </div>
    </fieldset>

    <!-- Grid dua kolom: Persiapan & Tabel -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Kiri: Persiapan, Jalan Nafas, Regional -->
        <div class="space-y-6">
            <fieldset class="bg-white shadow-md rounded-xl p-6 border border-gray-100">
                <legend class="px-2 text-lg font-semibold text-blue-600">Persiapan & Waktu</legend>
                <div class="space-y-2 mt-2">
                    <p><strong class="text-gray-600">Infus 1:</strong> {{ $monitoring->infus_perifer_1_tempat_ukuran ?? '-' }}</p>
                    <p><strong class="text-gray-600">Infus 2:</strong> {{ $monitoring->infus_perifer_2_tempat_ukuran ?? '-' }}</p>
                    <p><strong class="text-gray-600">Posisi:</strong> {{ $monitoring->posisi ?? '-' }}</p>
                    <p><strong class="text-gray-600">Mulai Anestesi:</strong> {{ $monitoring->mulai_anestesia ? $monitoring->mulai_anestesia->format('d-m-Y H:i') : '-' }}</p>
                    <p><strong class="text-gray-600">Mulai Pembedahan:</strong> {{ $monitoring->mulai_pembedahan ? $monitoring->mulai_pembedahan->format('d-m-Y H:i') : '-' }}</p>
                </div>
            </fieldset>

            <fieldset class="bg-white shadow-md rounded-xl p-6 border border-gray-100">
                <legend class="px-2 text-lg font-semibold text-blue-600">Jalan Nafas & Intubasi</legend>
                <div class="space-y-2 mt-2">
                    <p><strong class="text-gray-600">Jalan Nafas:</strong>
                        {{ $monitoring->jalan_nafas_facemask_no ? 'Face Mask No.'.$monitoring->jalan_nafas_facemask_no : '' }}
                        {{ $monitoring->jalan_nafas_oro_nasopharing ? 'Oro/Naso' : '' }}
                        {{ $monitoring->jalan_nafas_ett_no ? 'ETT No.'.$monitoring->jalan_nafas_ett_no : '' }}
                    </p>
                    <p><strong class="text-gray-600">Sulit Ventilasi:</strong> {{ $monitoring->sulit_ventilasi ? 'Ya' : 'Tidak' }}</p>
                    <p><strong class="text-gray-600">Sulit Intubasi:</strong> {{ $monitoring->sulit_intubasi ? 'Ya' : 'Tidak' }}</p>
                </div>
            </fieldset>

            <fieldset class="bg-white shadow-md rounded-xl p-6 border border-gray-100">
                <legend class="px-2 text-lg font-semibold text-blue-600">Teknik Regional</legend>
                <div class="space-y-2 mt-2">
                    <p><strong class="text-gray-600">Jenis:</strong> {{ $monitoring->regional_jenis ?? '-' }}</p>
                    <p><strong class="text-gray-600">Lokasi:</strong> {{ $monitoring->regional_lokasi ?? '-' }}</p>
                    <p><strong class="text-gray-600">Hasil:</strong> {{ $monitoring->regional_hasil ?? '-' }}</p>
                </div>
            </fieldset>
        </div>

        <!-- Kanan: Tabel Vital & Obat -->
        <div class="space-y-6">
            <fieldset class="bg-white shadow-md rounded-xl p-6 border border-gray-100 overflow-x-auto">
                <legend class="px-2 text-lg font-semibold text-blue-600">Tabel Tanda Vital</legend>
                <table class="min-w-full divide-y divide-gray-200 mt-2">
                    <thead class="bg-gray-50">
                        <tr class="text-center text-xs">
                            <th class="px-2 py-2">Waktu</th>
                            <th class="px-2 py-2">Nadi</th>
                            <th class="px-2 py-2">Sis/Dis</th>
                            <th class="px-2 py-2">RR</th>
                            <th class="px-2 py-2">SpO2</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-center">
                        @forelse($monitoring->vitals as $vital)
                        <tr>
                            <td class="px-2 py-1 text-sm">{{ $vital->waktu }}</td>
                            <td class="px-2 py-1 text-sm">{{ $vital->rrn ?? '-' }}</td>
                            <td class="px-2 py-1 text-sm">{{ $vital->td_sis ?? '-' }}/{{ $vital->td_dis ?? '-' }}</td>
                            <td class="px-2 py-1 text-sm">{{ $vital->rr ?? '-' }}</td>
                            <td class="px-2 py-1 text-sm">{{ $vital->spo2 ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-2 text-gray-500">Tidak ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </fieldset>

            <fieldset class="bg-white shadow-md rounded-xl p-6 border border-gray-100 overflow-x-auto">
                <legend class="px-2 text-lg font-semibold text-blue-600">Obat-obatan / Infus</legend>
                <table class="min-w-full divide-y divide-gray-200 mt-2">
                    <thead class="bg-gray-50">
                        <tr class="text-center text-xs">
                            <th class="px-2 py-2">Waktu</th>
                            <th class="px-2 py-2">Nama Obat</th>
                            <th class="px-2 py-2">Dosis</th>
                            <th class="px-2 py-2">Rute</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-center">
                        @forelse($monitoring->medications as $med)
                        <tr>
                            <td class="px-2 py-1 text-sm">{{ $med->waktu }}</td>
                            <td class="px-2 py-1 text-sm">{{ $med->nama_obat_infus_gas }}</td>
                            <td class="px-2 py-1 text-sm">{{ $med->dosis ?? '-' }}</td>
                            <td class="px-2 py-1 text-sm">{{ $med->rute ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-2 text-gray-500">Tidak ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </fieldset>
        </div>
    </div>
</div>
