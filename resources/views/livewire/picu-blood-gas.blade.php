<div class="p-4 border rounded-md shadow-sm bg-white">
    <h3 class="text-lg font-medium mb-4">Blood Gas Monitor (AGD)</h3>

    {{-- Notifikasi Sukses --}}
    @if (session()->has('success-agd'))
        <div class="p-3 my-3 text-green-800 bg-green-100 border border-green-300 rounded-md">
            {{ session('success-agd') }}
        </div>
    @endif

    {{-- 1. FORM INPUT --}}
    <form wire:submit="save" class="p-4 space-y-4 bg-gray-50 border rounded-md">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <x-form-input label="Waktu Log" wire:model="waktu_log" type="datetime-local" />
            <x-form-input label="Gula Darah (BS)" wire:model="guka_darah_bs" type="number" step="0.1" />
            <x-form-input label="pH" wire:model="ph" type="number" step="0.01" />
            <x-form-input label="PCO2" wire:model="pco2" type="number" step="0.1" />
            <x-form-input label="PO2" wire:model="po2" type="number" step="0.1" />
            <x-form-input label="HCO3" wire:model="hco3" type="number" step="0.1" />
            <x-form-input label="BE" wire:model="be" type="number" step="0.1" />
            <x-form-input label="SaO2" wire:model="sao2" type="number" step="0.1" />
        </div>
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                Simpan Log AGD
            </button>
        </div>
    </form>

    {{-- 2. TABEL LOG (RIWAYAT) --}}
    <div class="mt-6">
        <h4 class="font-semibold mb-2">Riwayat Log AGD (Sheet Ini)</h4>
        <div class="overflow-x-auto border rounded-md">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 text-left">Waktu</th>
                        <th class="px-3 py-2 text-left">Gula Darah</th>
                        <th class="px-3 py-2 text-left">pH</th>
                        <th class="px-3 py-2 text-left">PCO2</th>
                        <th class="px-3 py-2 text-left">PO2</th>
                        <th class="px-3 py-2 text-left">HCO3</th>
                        <th class="px-3 py-2 text-left">BE</th>
                        <th class="px-3 py-2 text-left">SaO2</th>
                        <th class="px-3 py-2 text-left">Petugas</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($logs as $log)
                        <tr>
                            <td class="px-3 py-2">{{ $log->waktu_log->format('d/m H:i') }}</td>
                            <td class="px-3 py-2">{{ $log->guka_darah_bs }}</td>
                            <td class="px-3 py-2">{{ $log->ph }}</td>
                            <td class="px-3 py-2">{{ $log->pco2 }}</td>
                            <td class="px-3 py-2">{{ $log->po2 }}</td>
                            <td class="px-3 py-2">{{ $log->hco3 }}</td>
                            <td class="px-3 py-2">{{ $log->be }}</td>
                            <td class="px-3 py-2">{{ $log->sao2 }}</td>
                            <td class="px-3 py-2">{{ $log->petugas_id }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-3 py-4 text-center text-gray-500">
                                Belum ada data Blood Gas untuk sheet ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
