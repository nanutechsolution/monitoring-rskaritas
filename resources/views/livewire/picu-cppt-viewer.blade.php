<div class="p-4 border rounded-md shadow-sm bg-white">
    {{--
      Kita buat max-h-96 (tinggi 384px) dan overflow-y-auto
      agar halaman utama tidak terlalu panjang.
    --}}
    <div class="space-y-4 max-h-96 overflow-y-auto pr-2">

        @forelse ($this->cpptRecords as $cppt)
            <div class="p-3 border rounded-md bg-gray-50 shadow-sm">

                {{-- Header CPPT per entri --}}
                <div class="flex justify-between items-center border-b pb-2 mb-2">
                    <span class="font-bold text-gray-800">
                        {{-- Format tanggal dan jam --}}
                        {{ $cppt->tgl_perawatan->format('d/m/Y') }}
                        @php
                            // Format jam_rawat (yang bisa jadi string)
                            try {
                                echo \Carbon\Carbon::parse($cppt->jam_rawat)->format('H:i:s');
                            } catch (\Exception $e) {
                                echo $cppt->jam_rawat;
                            }
                        @endphp
                    </span>
                    <span class="text-sm font-medium text-blue-600">
                        {{-- Tampilkan nama pegawai, jika relasi berhasil --}}
                        {{ $cppt->pegawai->nama ?? $cppt->nip }}
                    </span>
                </div>

                {{-- Konten SOAP --}}
                <div class="grid grid-cols-12 gap-x-2 gap-y-1 text-sm">
                    {{-- S: Subjective / Keluhan --}}
                    <div class="col-span-1 font-bold text-right text-gray-600">S:</div>
                    <div class="col-span-11">{{ $cppt->keluhan }}</div>

                    {{-- O: Objective / Pemeriksaan --}}
                    <div class="col-span-1 font-bold text-right text-gray-600">O:</div>
                    <div class="col-span-11">
                        {{ $cppt->pemeriksaan }}
                        {{-- Tampilkan Tanda Vital dari SOAP --}}
                        <div class="text-xs text-gray-500 mt-1 pl-2 border-l-2">
                            T: {{ $cppt->tensi }} mmHg,
                            N: {{ $cppt->nadi }} x/m,
                            RR: {{ $cppt->respirasi }} x/m,
                            S: {{ $cppt->suhu_tubuh }} °C,
                            SpO2: {{ $cppt->spo2 }} %,
                            GCS: {{ $cppt->gcs }} ({{ $cppt->kesadaran }})
                        </div>
                    </div>

                    {{-- A: Assessment / Penilaian --}}
                    <div class="col-span-1 font-bold text-right text-gray-600">A:</div>
                    <div class="col-span-11">{{ $cppt->penilaian }}</div>

                    {{-- P: Plan / RTL & Instruksi --}}
                    <div class="col-span-1 font-bold text-right text-gray-600">P:</div>
                    <div class="col-span-11">
                        @if($cppt->rtl)<p><strong>RTL:</strong> {{ $cppt->rtl }}</p>@endif
                        @if($cppt->instruksi)<p><strong>Instruksi:</strong> {{ $cppt->instruksi }}</p>@endif
                        @if($cppt->evaluasi)<p><strong>Evaluasi:</strong> {{ $cppt->evaluasi }}</p>@endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-center py-4">
                Belum ada data CPPT (SOAP) yang tercatat di Khanza untuk pasien ini.
            </p>
        @endforelse
    </div>
</div>
