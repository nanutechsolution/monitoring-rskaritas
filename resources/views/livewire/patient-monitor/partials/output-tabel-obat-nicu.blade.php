{{--
  1. BUNGKUS SEMUANYA DENGAN x-data
  Kita juga tambahkan listener untuk menutup modal saat sukses.
--}}
<div x-data="{ showAddMedicationModal: false }" @close-medication-modal.window="showAddMedicationModal = false">

    {{-- Ini adalah KODE TABEL ANDA (dari user) --}}
    @php
        use Carbon\Carbon;

        // --- Logika PHP Anda (Sudah Benar) ---
        $jamList = $medications->pluck('given_at')
            ->map(fn($t) => Carbon::parse($t)->format('H:i'))
            ->unique()
            ->sort()
            ->values();

        $medNames = $medications->pluck('medication_name')->unique()->values();

        $matrix = [];
        foreach ($medications as $med) {
            $jam = Carbon::parse($med->given_at)->format('H:i');
            $matrix[$med->medication_name][$jam] = [
                'dose' => $med->dose,
                'route' => $med->route,
                'author' => $med->pegawai->nama ?? 'N/A', // Menggunakan relasi 'pegawai'
                'nik' => $med->pegawai->nik ?? '-',
                'timestamp' => Carbon::parse($med->given_at)->translatedFormat('d M Y H:i'),
            ];
        }

        $highlightThreshold = 500;
        $headerBg = 'bg-gray-100 dark:bg-gray-700';
        $headerText = 'text-gray-700 dark:text-gray-300';
        $headerStickyBg = 'bg-gray-100 dark:bg-gray-700';
        $rowBg = 'bg-white dark:bg-gray-800';
        $rowBgAlt = 'bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50';
        $rowStickyBg = 'bg-white dark:bg-gray-800';
        $rowStickyBgAlt = 'bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50';
        $rowHover = 'hover:bg-primary-50 dark:hover:bg-gray-600 dark:hover:bg-opacity-50';
        $border = 'border dark:border-gray-600';
    @endphp

    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="p-6">
            <div class="flex items-center justify-between border-b dark:border-gray-700 pb-3 mb-3">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ $title }}
                </h3>

                {{-- 2. TAMBAHKAN TOMBOL "TAMBAH OBAT" DI SINI --}}
                <button type="button"
                        {{-- Panggil resetForm() di PHP untuk set 'given_at' ke now() --}}
                        @click="showAddMedicationModal = true; $wire.resetForm()"
                        @disabled($isReadOnly)
                        class="text-sm bg-primary-600 text-white px-3 py-1.5 rounded-lg shadow-sm
                               hover:bg-primary-700 focus:outline-none focus:ring-2
                               focus:ring-primary-500 focus:ring-offset-2
                               dark:focus:ring-offset-gray-800
                               disabled:opacity-50 disabled:cursor-not-allowed">
                    + Tambah Obat
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700">
                <table class="min-w-max text-sm text-gray-700 dark:text-gray-300">
                    <thead class="{{ $headerBg }} {{ $headerText }} uppercase text-xs tracking-wider sticky top-0 z-10">
                        <tr>
                            <th class="sticky left-0 {{ $headerStickyBg }} px-4 py-3 text-left font-semibold z-10 {{ $border }}">Obat</th>
                            @foreach($jamList as $jam)
                            <th class="{{ $border }} px-4 py-3 font-semibold text-center">{{ $jam }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($medNames as $medName)
                        <tr class="{{ $loop->even ? $rowBgAlt : $rowBg }} {{ $rowHover }} transition-colors duration-150">
                            <td class="sticky left-0 px-4 py-3 font-medium text-gray-800 dark:text-gray-100 {{ $border }}
                                       {{ $loop->even ? $rowStickyBgAlt : $rowStickyBg }} z-10">
                                {{ $medName }}
                            </td>

                            @foreach($jamList as $jam)
                            @php
                                $data = $matrix[$medName][$jam] ?? null;
                                if ($data) {
                                    $doseText = $data['dose'] . ' / ' . $data['route'];
                                    $author = $data['author'];
                                    $nik = $data['nik'];
                                    $timestamp = $data['timestamp'];
                                    preg_match('/\d+/', $data['dose'], $matches);
                                    $highlightClass = (isset($matches[0]) && intval($matches[0]) > $highlightThreshold)
                                        ? 'text-danger-600 dark:text-danger-400 font-semibold'
                                        : 'text-gray-800 dark:text-gray-100';
                                } else {
                                    $doseText = '-';
                                    $author = null;
                                    $highlightClass = 'text-gray-400 dark:text-gray-500';
                                }
                            @endphp

                            <td class="px-4 py-3 text-center align-top {{ $border }}">
                                @if($data)
                                <div class="{{ $highlightClass }}">{{ $doseText }}</div>

                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 relative group cursor-help">
                                    <i class="fas fa-user-nurse text-primary-500 dark:text-primary-400 mr-1"></i>
                                    <span>{{ Str::limit($author, 15) }}</span>

                                    <div x-data="{ open: false }" x-show="open" x-transition @mouseenter="open = true" @mouseleave="open = false"
                                         class="hidden group-hover:block absolute z-20 bg-gray-800 dark:bg-gray-900 text-white
                                                text-xs rounded-md p-2 w-48 -translate-x-1/2 left-1/2 bottom-full mb-1 shadow-lg
                                                text-left">
                                        <p class="font-semibold">{{ $author }}</p>
                                        <p>NIK: {{ $nik }}</p>
                                        <p class="text-[11px] mt-1">Diberikan: {{ $timestamp }}</p>
                                    </div>
                                </div>
                                @else
                                <span class="{{ $highlightClass }}">-</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $jamList->count() + 1 }}" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 text-sm">
                                Belum ada data pemberian obat untuk siklus ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 3. PINDAHKAN MODAL OBAT KE SINI --}}
    <div x-show="showAddMedicationModal"
         x-cloak
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-60 backdrop-blur-sm"
         @keydown.escape.window="showAddMedicationModal = false">

        <div x-show="showAddMedicationModal"
             @click.away="showAddMedicationModal = false"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">

            {{-- Gunakan wire:submit untuk form --}}
            <form wire:submit="saveMedication">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tambah Pemberian Obat</h3>
                    <button type="button" @click="showAddMedicationModal = false" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                @php
                    $inputModalClasses = 'mt-1 block w-full rounded-md shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500';
                    $labelModalClasses = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1';
                    $errorModalClasses = 'text-xs text-danger-600 dark:text-danger-400 mt-1';
                @endphp

                <div class="p-6 space-y-4 overflow-y-auto">
                    <div>
                        <label class="{{ $labelModalClasses }}">Waktu Pemberian</label>
                        {{-- Hubungkan ke $medicationGivenAt --}}
                        <input type="datetime-local"
                               wire:model="medicationGivenAt"
                               class="{{ $inputModalClasses }}">
                        @error('given_at') <span class="{{ $errorModalClasses }}">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="medication_name_modal" class="{{ $labelModalClasses }}">Nama Obat</label>
                        {{-- Hubungkan ke $medicationName --}}
                        <input id="medication_name_modal" type="text"
                               wire:model="medicationName"
                               list="recent-meds" class="{{ $inputModalClasses }}" placeholder="Ketik atau pilih dari riwayat...">
                        <datalist id="recent-meds">
                            @foreach($recentMedicationNames as $name)
                            <option value="{{ $name }}">
                            @endforeach
                        </datalist>
                        @error('medication_name') <span class="{{ $errorModalClasses }}">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="medication_dose_modal" class="{{ $labelModalClasses }}">Dosis</label>
                            {{-- Hubungkan ke $medicationDose --}}
                            <input id="medication_dose_modal" type="text"
                                   wire:model="medicationDose"
                                   class="{{ $inputModalClasses }}" placeholder="Contoh: 3x80mg">
                            @error('dose') <span class="{{ $errorModalClasses }}">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="medication_route_modal" class="{{ $labelModalClasses }}">Rute</label>
                            {{-- Hubungkan ke $medicationRoute --}}
                            <input id="medication_route_modal" type="text"
                                   wire:model="medicationRoute"
                                   class="{{ $inputModalClasses }}" placeholder="Contoh: IV">
                            @error('route') <span class="{{ $errorModalClasses }}">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                    <button type="button" @click="showAddMedicationModal = false"
                            class="px-4 py-2 text-sm font-medium rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition shadow-sm">
                        Batal
                    </button>

                    {{-- Tombol submit untuk form --}}
                    <button type="submit"
                            wire:loading.attr="disabled" wire:target="saveMedication" wire:loading.class="opacity-75 cursor-wait"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md border border-transparent bg-primary-600 text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition shadow-sm">
                        <svg wire:loading wire:target="saveMedication" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span wire:loading.remove wire:target="saveMedication">Simpan Obat</span>
                        <span wire:loading wire:target="saveMedication">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
