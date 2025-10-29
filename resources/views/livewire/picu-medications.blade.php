<div class="p-4 border rounded-md shadow-sm bg-white">
    <h3 class="text-lg font-medium mb-4">Obat-obatan</h3>

    {{-- Notifikasi Sukses --}}
    @if (session()->has('success-med'))
        <div class="p-3 my-3 text-green-800 bg-green-100 border border-green-300 rounded-md">
            {{ session('success-med') }}
        </div>
    @endif

    {{-- 1. FORM INPUT --}}
    <form wire:submit="save" class="p-4 space-y-4 bg-gray-50 border rounded-md">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <x-form-input label="Waktu Pemberian" wire:model="waktu_pemberian" type="datetime-local" />
            <x-form-input label="Nama Obat" wire:model="nama_obat" type="text" />
            <x-form-input label="Dosis" wire:model="dosis" type="text" placeholder="cth: 3x50mg" />
            <x-form-input label="Rute" wire:model="rute" type="text" placeholder="cth: IV, PO" />
        </div>
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                Simpan Obat
            </button>
        </div>
    </form>

    {{-- 2. TABEL LOG (RIWAYAT) --}}
    <div class="mt-6">
        <h4 class="font-semibold mb-2">Riwayat Obat-obatan (Sheet Ini)</h4>
        <div class="overflow-y-auto border rounded-md max-h-60">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-100 sticky top-0">
                    <tr>
                        <th class="px-3 py-2 text-left">Waktu</th>
                        <th class="px-3 py-2 text-left">Nama Obat</th>
                        <th class="px-3 py-2 text-left">Dosis</th>
                        <th class="px-3 py-2 text-left">Rute</th>
                        <th class="px-3 py-2 text-left">Petugas</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($this->medicationLogs as $log)
                        <tr>
                            <td class="px-3 py-2">{{ $log->waktu_pemberian->format('d/m H:i') }}</td>
                            <td class="px-3 py-2">{{ $log->nama_obat }}</td>
                            <td class="px-3 py-2">{{ $log->dosis }}</td>
                            <td class="px-3 py-2">{{ $log->rute }}</td>
                            <td class="px-3 py-2">{{ $log->petugas_id }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-4 text-center text-gray-500">
                                Belum ada data obat untuk sheet ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
