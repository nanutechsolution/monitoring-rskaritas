<div x-data="{ type: @entangle('type') }">

    {{-- Notifikasi Sukses --}}
    @if (session()->has('success-fluid'))
    <div class="p-3 mb-4 text-green-800 bg-green-100 border border-green-300 rounded-md">
        {{ session('success-fluid') }}
    </div>
    @endif

    {{-- =============================================== --}}
    {{-- === BAGIAN 1: SUMMARY 24 JAM === --}}
    {{-- =============================================== --}}
    <div class="p-4 mb-6 border rounded-md shadow-sm bg-white">
        <h3 class="text-lg font-medium mb-4">Summary Keseimbangan Cairan 24 Jam</h3>

        {{-- Input Manual Summary --}}
        <div class="grid grid-cols-2 gap-4 mb-4">
            <x-form-input label="Balance 24 Jam SEBELUMNYA (ml)" wire:model.live.debounce.500ms="balance_cairan_24h_sebelumnya" wire:change="updateSummary" type="number" step="0.1" />

            <x-form-input label="EWL / IWL (ml)" wire:model.live.debounce.500ms="ewl_24h" wire:change="updateSummary" type="number" step="0.1" />
        </div>

        {{-- Tampilan Kalkulasi Otomatis (Read-Only) --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 rounded-md bg-gray-50 border">
            <div>
                <span class="block text-sm font-medium text-gray-500">Total Cairan Masuk (CM)</span>
                <span class="text-xl font-bold text-blue-600">{{ number_format($totalMasuk, 1) }}</span> ml
            </div>
            <div>
                <span class="block text-sm font-medium text-gray-500">Total Cairan Keluar (CK)</span>
                <span class="text-xl font-bold text-red-600">{{ number_format($totalKeluar, 1) }}</span> ml
            </div>
            <div>
                <span class="block text-sm font-medium text-gray-500">Balance Harian (CM - CK - EWL)</span>
                <span class="text-xl font-bold {{ $balanceHarian >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ number_format($balanceHarian, 1) }}
                </span> ml
            </div>
            <div>
                <span class="block text-sm font-medium text-gray-500">Balance Kumulatif</span>
                <span class="text-xl font-bold {{ $balanceKumulatif >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ number_format($balanceKumulatif, 1) }}
                </span> ml
            </div>
            <div class="col-span-2 md:col-span-4">
                <span class="block text-sm font-medium text-gray-500">Produksi Urine 24 Jam</span>
                <span class="text-lg font-semibold">{{ number_format($produksiUrine, 1) }}</span> ml
            </div>
        </div>
    </div>

    {{-- =============================================== --}}
    {{-- === BAGIAN 2: FORM INPUT CAIRAN === --}}
    {{-- =============================================== --}}
    <div class="p-4 mb-6 border rounded-md shadow-sm bg-white">
        <h3 class="text-lg font-medium mb-4">Input Log Cairan (Masuk / Keluar)</h3>

        <form wire:submit="saveLog" class="p-4 space-y-4 bg-gray-50 border rounded-md">
            {{-- Toggle Input / Output --}}
            <div class="flex gap-4">
                <label class="flex items-center">
                    <input type="radio" value="input" x-model="type" class="text-blue-600">
                    <span class="ml-2">Cairan Masuk</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" value="output" x-model="type" class="text-red-600">
                    <span class="ml-2">Cairan Keluar</span>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <x-form-input label="Waktu Log" wire:model="waktu_log" type="datetime-local" class="col-span-1" />

                {{-- Dropdown Kategori (Dinamis) --}}
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select wire:model="kategori" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Pilih Kategori --</option>
                        {{-- Opsi Input (Muncul saat type='input') --}}
                        <template x-if="type === 'input'">
                            <optgroup label="Cairan Masuk">
                                @foreach($kategoriInput as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </optgroup>
                        </template>
                        {{-- Opsi Output (Muncul saat type='output') --}}
                        <template x-if="type === 'output'">
                            <optgroup label="Cairan Keluar">
                                @foreach($kategoriOutput as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </optgroup>
                        </template>
                    </select>
                    @error('kategori') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <x-form-input label="Keterangan (Nama Infus, Susu, Warna Urine, dll)" wire:model="keterangan" type="text" class="col-span-1" />

                <x-form-input label="Jumlah (ml)" wire:model="jumlah" type="number" step="0.1" class="col-span-1" />
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-md" :class="type === 'input' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-red-600 hover:bg-red-700'">
                    Simpan Log
                </button>
            </div>
        </form>
    </div>

    {{-- =============================================== --}}
    {{-- === BAGIAN 3: TABEL LOG (RIWAYAT) === --}}
    {{-- =============================================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- KOLOM KIRI: RIWAYAT MASUK --}}
        <div class="p-4 border rounded-md shadow-sm bg-white">
            <h4 class="font-semibold mb-2 text-blue-700">Riwayat Cairan Masuk (CM)</h4>
            <div class="overflow-y-auto border rounded-md max-h-60">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-blue-50 sticky top-0">
                        <tr>
                            <th class="px-3 py-2 text-left">Waktu</th>
                            <th class="px-3 py-2 text-left">Keterangan</th>
                            <th class="px-3 py-2 text-left">Jumlah (ml)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($this->fluidInputs as $log)
                        <tr>
                            <td class="px-3 py-2">{{ $log->waktu_log->format('H:i') }}</td>
                            <td class="px-3 py-2">
                                <span class="font-medium">{{ $log->keterangan }}</span>
                                <span class="block text-xs text-gray-500">{{ data_get($kategoriInput, $log->kategori, $log->kategori) }}</span>
                            </td>
                            <td class="px-3 py-2">{{ $log->jumlah }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-3 text-center text-gray-500">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- KOLOM KANAN: RIWAYAT KELUAR --}}
        <div class="p-4 border rounded-md shadow-sm bg-white">
            <h4 class="font-semibold mb-2 text-red-700">Riwayat Cairan Keluar (CK)</h4>
            <div class="overflow-y-auto border rounded-md max-h-60">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-red-50 sticky top-0">
                        <tr>
                            <th class="px-3 py-2 text-left">Waktu</th>
                            <th class="px-3 py-2 text-left">Keterangan</th>
                            <th class="px-3 py-2 text-left">Jumlah (ml)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($this->fluidOutputs as $log)
                        <tr>
                            <td class="px-3 py-2">{{ $log->waktu_log->format('H:i') }}</td>
                            <td class="px-3 py-2">
                                <span class="font-medium">{{ $log->keterangan }}</span>
                                <span class="block text-xs text-gray-500">{{ data_get($kategoriOutput, $log->kategori, $log->kategori) }}</span>

                            </td>
                            <td class="px-3 py-2">{{ $log->jumlah }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-3 text-center text-gray-500">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
