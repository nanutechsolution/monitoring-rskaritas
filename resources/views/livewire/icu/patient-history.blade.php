<div class="max-w-7xl mx-auto p-4 sm:p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- ===== KOLOM KIRI (KONTEN UTAMA) ===== -->
    <div class="lg:col-span-2 space-y-6">

        <!-- KARTU "RIWAYAT LAMPAU" (Sekarang jadi konten utama di kiri) -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-3 sm:p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 flex justify-between items-center">
                <h3 class="text-sm sm:text-lg font-semibold text-gray-800 dark:text-gray-100">Riwayat Lembar Observasi</h3>
                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $allCycles->count() }} catatan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs sm:text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase">
                        <tr>
                            <th class="px-3 py-2 text-left font-medium tracking-wider">Tanggal</th>
                            <th class="px-3 py-2 text-left font-medium tracking-wider">BC 24 Jam</th>
                            <th class="px-3 py-2 text-left font-medium tracking-wider">BC Kumulatif</th>
                            <th class="px-3 py-2 text-right font-medium tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($allCycles as $cycle)
                        <!--
                            TIPS UX:
                            Buat seluruh baris bisa diklik sambil tetap menghormati wire:navigate.
                            'x-data' inisialisasi kosong.
                            '@click' menemukan link di dalam baris dan 'mengkliknya'.
                        -->
                        <tr class="hover:bg-primary-50 dark:hover:bg-gray-700 transition-colors cursor-pointer"
                            x-data
                            @click="$el.querySelector('a').click()">
                            <td class="px-3 py-3 whitespace-nowrap font-medium text-gray-800 dark:text-gray-100">
                                {{ $cycle->sheet_date->isoFormat('D MMM Y') }}
                                @if($cycle->sheet_date->isSameDay($todayDate))
                                <span class="ml-1 text-[10px] font-semibold
                                             bg-primary-100 dark:bg-primary-900
                                             text-primary-700 dark:text-primary-200
                                             px-1 py-0.5 rounded-full">
                                    Hari Ini
                                </span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-gray-700 dark:text-gray-300">{{ $cycle->calculated_balance_24h ?? 'N/A' }} ml</td>
                            <td class="px-3 py-3 text-gray-700 dark:text-gray-300">
                                {{ ($cycle->previous_balance ?? 0) + ($cycle->calculated_balance_24h ?? 0) }} ml
                            </td>
                            <td class="px-3 py-3 text-right">
                                <!-- Tombol 'Buka' ini sekarang menjadi target klik untuk <tr> -->
                                <a href="{{ route('monitoring.icu.workspace', [
                                        'noRawat' => str_replace('/', '_', $registrasi->no_rawat),
                                        'sheetDate' => $cycle->sheet_date->toDateString()
                                    ]) }}" wire:navigate
                                   class="inline-flex items-center
                                          text-primary-600 dark:text-primary-400
                                          hover:text-primary-800 dark:hover:text-primary-300
                                          font-medium text-xs sm:text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m0 0l4 4m-4-4l4-4m10 8h4v-8h-4" />
                                    </svg>
                                    Buka
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                                <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                </svg>
                                Belum ada riwayat monitoring untuk pasien ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ===== KOLOM KANAN (KONTEKS & AKSI) ===== -->
    <div class="lg:col-span-1 space-y-6">

        <!-- KARTU PASIEN (Sekarang di kanan atas) -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border-l-4 border-primary-600 dark:border-primary-500 px-3 py-3 sm:px-6 sm:py-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-3">
                <div class="leading-tight">
                    <h2 class="text-base sm:text-xl font-bold text-gray-800 dark:text-gray-100 truncate">
                        {{ $registrasi->pasien->nm_pasien }}
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium">RM:</span> {{ $registrasi->pasien->no_rkm_medis }} |
                        <span class="font-medium">Rawat:</span> {{ $registrasi->no_rawat }}
                    </p>
                </div>

                <a href="{{ route('dashboard') }}" wire:navigate class="text-[11px] sm:text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium transition-colors whitespace-nowrap">
                    ← Kembali
                </a>
            </div>
        </div>

        <!-- KARTU "BUKA HARI INI" (Sekarang di kanan) -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4 sm:p-5 text-center">
            <h3 class="text-sm sm:text-lg font-semibold text-gray-800 dark:text-gray-100 mb-1 sm:mb-2">Lembar Observasi Hari Ini</h3>
            <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-base mb-4 sm:mb-5">
                {{ \Carbon\Carbon::parse($todayDate)->isoFormat('dddd, D MMMM Y') }}
            </p>

            <!-- Tombol dibuat lebih besar (py-3, text-base) karena ini aksi utama -->
            <a href="{{ route('monitoring.icu.workspace', [
                    'noRawat' => str_replace('/', '_', $registrasi->no_rawat),
                    'sheetDate' => $todayDate
                ]) }}" wire:navigate
               class="inline-flex items-center justify-center gap-1.5 sm:gap-2
                      w-full
                      bg-primary-600 hover:bg-primary-700
                      text-white px-4 sm:px-8 py-3 rounded-md
                      font-semibold text-base shadow hover:shadow-md transition-all
                      focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                      dark:focus:ring-offset-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Buka Hari Ini
            </a>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-3">
                Jika belum ada, sistem akan membuatkan otomatis.
            </p>
        </div>

    </div>

</div>
