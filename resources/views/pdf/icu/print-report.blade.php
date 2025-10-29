<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan ICU - {{ $registrasi->pasien->nm_pasien }} - {{ $cycle->sheet_date->format('d-m-Y') }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 8px;
            /* Font sangat kecil */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 2px 4px;
            text-align: center;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .header-table td {
            border: none;
            text-align: left;
            font-size: 9px;
            padding: 1px 0;
        }

        .param-label {
            text-align: left;
            font-weight: bold;
            width: 100px;
            /* Lebar kolom parameter */
        }

        .group-label {
            background-color: #e2e8f0;
            font-weight: bold;
            text-align: left;
        }

        .time-header {
            font-size: 7px;
            /* Font waktu lebih kecil lagi */
        }

        .author-header {
            font-size: 6px;
            color: #555;
        }

        .sticky {
            /* Tidak berfungsi di dompdf, abaikan */
        }

        .whitespace-nowrap {
            white-space: nowrap;
        }

        .whitespace-pre-wrap {
            white-space: pre-wrap;
            word-break: break-word;
        }

        .text-green-700 {
            color: #047857;
        }

        /* Sesuaikan warna jika perlu */
        .text-red-700 {
            color: #b91c1c;
        }

        .text-yellow-700 {
            color: #a16207;
        }

        .text-purple-700 {
            color: #7e22ce;
        }

        .text-blue-700 {
            color: #1d4ed8;
        }

    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 15px;">
        <img src="data:image/png;base64,{{ base64_encode($setting->logo) }}" alt="Logo RS" style="height: 50px; margin-bottom: 5px;">
        <h1 style="font-size: 12px; font-weight: bold; margin: 0;">{{ $setting->nama_instansi ?? 'Nama Instansi Tidak Ditemukan' }}</h1>
        <p style="font-size: 8px; margin: 2px 0;">{{ $setting->alamat_instansi ?? '' }}</p>
        <p style="font-size: 8px; margin: 2px 0;">
            {{ $setting->kabupaten ?? '' }}{{ ($setting->kabupaten && $setting->propinsi) ? ', ' : '' }}{{ $setting->propinsi ?? '' }}
        </p>
        <p style="font-size: 8px; margin: 2px 0;">
            Telp: {{ $setting->kontak ?? '' }} | Email: {{ $setting->email ?? '' }}
        </p>
        <hr style="margin-top: 5px; border-top: 1px solid black;">
    </div>
    <h2>Laporan Observasi ICU</h2>
    <table class="header-table" style="width: 60%; margin-bottom: 5px;">
        <tr>
            <td style="width: 100px;">Nama Pasien</td>
            <td>: {{ $registrasi->pasien->nm_pasien }}</td>
            <td style="width: 100px;">Instalasi</td>
            <td>: {{ $currentInstallasiName }}</td>
        </tr>
        <tr>
            <td>No. RM</td>
            <td>: {{ $registrasi->pasien->no_rkm_medis }}</td>
            <td>Ruang</td>
            <td>: {{ $currentRoomName }}</td>
        </tr>
        <tr>
            <td>No. Rawat</td>
            <td>: {{ $registrasi->no_rawat }}</td>
            <td>Asal Ruangan</td>
            <td>: {{ $originatingWardName }}</td>
        </tr>
        <tr>
            <td>Tgl. Observasi</td>
            <td>: {{ $cycle->sheet_date->isoFormat('dddd, D MMMM Y') }}</td>
            <td>Hari Rawat Ke</td>
            <td>: {{ $cycle->hari_rawat_ke }}</td>
        </tr>
        <tr>
            <td>Cara bayar</td>
            <td>: {{ $registrasi->penjab->png_jawab ?? 'N/A' }}</td>
        </tr>
    </table>

    {{-- Tabel Laporan Dinamis --}}
    <table>
        <thead>
            <tr>
                <th class="param-label">Parameter</th>
                @foreach($allRecords as $record)
                <th class="time-header">
                    {{ $record->observation_time->format('H:i') }}<br>
                    <span class="author-header">{{ $record->inputter->nama ?? 'N/A' }}</span>
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php $currentGroup = ''; @endphp
            @foreach($allParameters as $param)

            {{-- SECTION KHUSUS UNTUK GRUP CAIRAN --}}
            @if ($param['group'] == 'CAIRAN')

            {{-- Hanya proses sekali saat grup CAIRAN dimulai --}}
            @if ($currentGroup != 'CAIRAN')
            @php $currentGroup = 'CAIRAN'; @endphp

            {{-- Sub-Header CAIRAN MASUK --}}
            <tr class="bg-gray-50 border-t border-b">
                <td colspan="{{ $allRecords->count() + 1 }}" class="group-label">CAIRAN MASUK</td>
            </tr>

            {{-- Baris untuk Input PARENTERAL --}}
            @forelse ($uniqueParenteralFluids as $fluidName)
            <tr>
                <th class="param-label" style="padding-left: 15px;">{{ $fluidName }}</th> {{-- Indentasi --}}
                @foreach($allRecords as $record)
                <td>
                    {{-- Tampilkan volume jika waktu & nama cocok & parenteral --}}
                    @if($record->is_parenteral && $record->cairan_masuk_jenis == $fluidName && $record->cairan_masuk_volume)
                    <span class="font-semibold text-green-700">{{ $record->cairan_masuk_volume }}</span>
                    @endif
                </td>
                @endforeach
            </tr>
            @empty
            {{-- Opsional: Baris jika tidak ada parenteral --}}
            {{-- <tr><th class="param-label" style="padding-left: 15px;">(Tidak ada Parenteral)</th><td colspan="{{ $allRecords->count() }}"></td>
            </tr> --}}
            @endforelse

            {{-- Baris Dinamis untuk ENTERAL --}}
            @forelse ($uniqueEnteralFluids as $fluidName)
            <tr>
                <th class="param-label" style="padding-left: 15px;">{{ $fluidName }}</th> {{-- Indentasi --}}
                @foreach($allRecords as $record)
                <td>
                    {{-- Tampilkan volume jika waktu & nama cocok & enteral --}}
                    @if($record->is_enteral && $record->cairan_masuk_jenis == $fluidName && $record->cairan_masuk_volume)
                    <span class="font-semibold text-green-700">{{ $record->cairan_masuk_volume }}</span>
                    @endif
                </td>
                @endforeach
            </tr>
            @empty
            {{-- Opsional: Baris jika tidak ada enteral --}}
            <tr>
                <th class="param-label" style="padding-left: 15px;">(Tidak ada Enteral)</th>
                <td colspan="{{ $allRecords->count() }}"></td>
            </tr>
            @endforelse

            {{-- Baris JUMLAH Cairan Masuk (Opsional) --}}
            <tr>
                <th class="param-label">Jumlah</th>@foreach($allRecords as $record)<td></td>@endforeach
            </tr>

            {{-- Baris TOTAL Cairan Masuk (Opsional) --}}
            <tr>
                <th class="param-label">TOTAL Cairan Masuk</th>@foreach($allRecords as $record)<td></td>@endforeach
            </tr>

            {{-- Sub-Header CAIRAN KELUAR --}}
            <tr class="bg-gray-50 border-t border-b">
                <td colspan="{{ $allRecords->count() + 1 }}" class="group-label">CAIRAN KELUAR</td>
            </tr>

            {{-- Baris Tetap untuk Jenis Cairan Keluar --}}
            @php
            // Daftar jenis cairan keluar sesuai form kertas
            $keluarTypes = ['Irigasi CM', 'Irigasi CK', 'Urine', 'NGT', 'Drain/WSD 1', 'Drain/WSD 2', 'Lainnya'];
            @endphp
            @foreach($keluarTypes as $type)
            <tr>
                <th class="param-label">{{ $type }}</th>
                @foreach($allRecords as $record)
                <td>
                    {{-- Tampilkan volume jika jenisnya cocok --}}
                    @if($record->cairan_keluar_jenis == $type && $record->cairan_keluar_volume)
                    <span class="font-semibold text-red-700">{{ $record->cairan_keluar_volume }}</span>
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
            {{-- Baris TOTAL Cairan Keluar (Opsional) --}}
            <tr><th class="param-label">TOTAL Cairan Keluar</th>@foreach($allRecords as $record)<td></td>@endforeach</tr>

            @endif {{-- Akhir dari if currentGroup != CAIRAN --}}

            {{-- SECTION UNTUK GRUP LAIN (Hemodinamik, Respirasi, dll) --}}
            @else
            {{-- Tampilkan header grup jika berubah --}}
            @if ($param['group'] != $currentGroup)
            <tr class="bg-gray-50 border-t border-b">
                <td colspan="{{ $allRecords->count() + 1 }}" class="group-label">
                    {{ $param['group'] }}
                </td>
            </tr>
            @php $currentGroup = $param['group']; @endphp
            @endif

            {{-- Tampilkan baris parameter seperti biasa --}}
            <tr>
                <th class="param-label">{{ $param['label'] }}</th>
                @foreach($allRecords as $record)
                <td>
                    @php
                    $value = null;
                    $key = $param['key'];
                    // --- Logika Penggabungan Data (Pastikan sudah lengkap) ---
                    if ($key == 'tensi' && $record->tensi_sistol) { $value = $record->tensi_sistol . '/' . $record->tensi_diastol; }
                    elseif ($key == 'gcs' && ($record->gcs_e || $record->gcs_v || $record->gcs_m)) { $gcsTotal = $record->gcs_total ?? (($record->gcs_e ?? 0) + ($record->gcs_v ?? 0) + ($record->gcs_m ?? 0)); $value = "E".($record->gcs_e ?? '-')."V".($record->gcs_v ?? '-')."M".($record->gcs_m ?? '-').($gcsTotal > 0 ? "($gcsTotal)" : ''); }
                    elseif ($key == 'pupil' && ($record->pupil_left_size_mm || $record->pupil_right_size_mm)) { $left = ($record->pupil_left_size_mm ?? '-') . '/' . ($record->pupil_left_reflex ?? '-'); $right = ($record->pupil_right_size_mm ?? '-') . '/' . ($record->pupil_right_reflex ?? '-'); $value = "{$left}|{$right}"; }
                    elseif ($key == 'clinical_note' && $record->clinical_note) { $value = $record->clinical_note; }
                    elseif ($key == 'medication_administration' && $record->medication_administration) { $value = $record->medication_administration; }
                    elseif (isset($record->$key) && !in_array($key, ['tensi', 'gcs', 'pupil', 'clinical_note', 'medication_administration', 'cairan_masuk', 'cairan_keluar'])) { $value = $record->$key; } // Exclude cairan keys
                    @endphp
                    {{-- Tampilkan nilainya --}}
                    <span class="whitespace-pre-wrap">{!! $value !!}</span>
                </td>
                @endforeach
            </tr>
            @endif {{-- Akhir dari if group == CAIRAN --}}

            @endforeach {{-- Akhir loop parameter --}}
        </tbody>
    </table>

</body>
</html>
