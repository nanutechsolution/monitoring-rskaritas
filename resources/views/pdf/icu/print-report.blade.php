<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan ICU - {{ $registrasi->pasien->nm_pasien }} - {{ $cycle->sheet_date->format('d-m-Y') }}</title>
    {{-- CSS Inline untuk PDF --}}
    <style>
        @page {
            margin: 15px;
        }

        /* Atur margin halaman */
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 8px;
            line-height: 1.2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 2px 3px;
            text-align: center;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }



        .header-rs-table td {
            padding: 1px 0;
            font-size: 7px;
        }

        /* Style khusus header RS */
        .header-pasien-table td {
            padding: 1px 0;
            font-size: 8px;
        }

        .section-title {
            font-size: 9px;
            font-weight: bold;
            margin-top: 4px;
            margin-bottom: 1px;
            text-align: left;
            background-color: #f8f8f8;
            padding: 1px 3px;
            border: 1px solid #ddd;
        }

        .left-col {
            width: 28%;
            vertical-align: top;
            padding-right: 5px;
        }

        /* Lebar Kolom Kiri */
        .right-col {
            width: 72%;
            vertical-align: top;
            padding-left: 5px;
        }

        /* Lebar Kolom Kanan */
        .param-label {
            text-align: left;
            font-weight: bold;
            width: 90px;
        }

        .group-label {
            background-color: #e2e8f0;
            font-weight: bold;
            text-align: left;
            font-size: 9px;
        }

        .time-header {
            font-size: 7px;
            width: 35px;
            /* Lebar kolom waktu */
        }

        .author-header {
            font-size: 6px;
            color: #555;
            font-weight: normal;
        }

        .whitespace-nowrap {
            white-space: nowrap;
        }

        .whitespace-pre-wrap {
            white-space: pre-wrap;
            word-break: break-word;
        }

        .balance-box table td {
            font-size: 8px;
            padding: 1px 2px;
        }

        .balance-box th {
            font-size: 8px;
            text-align: left;
            padding: 1px 2px;
            width: 70px;
        }

        /* Terapkan border HANYA pada tabel observasi dan device */
        .observation-table th,
        .observation-table td,
        .device-table th,
        .device-table td {
            border: 1px solid #ccc;
            /* <<< Border diterapkan di sini <<< */
        }

        /* Style untuk TH default dan TH di tabel spesifik */
        th,
        .observation-table th,
        .device-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        hr {
            border: none;
            border-top: 1px solid #ccc;
            margin: 2px 0;
        }

        .text-green-700 {
            color: #047857;
        }

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

        .device-table th,
        .device-table td {
            font-size: 7px;
            padding: 1px 2px;
        }

        .page-break {
            page-break-after: always;
        }
/* Style untuk Header Gabungan */
        .header-container { border-bottom: 1.5px solid black; padding-bottom: 5px; margin-bottom: 10px; }
        .header-table { width: 100%; border: none !important; }
        .header-table td { border: none !important; vertical-align: top; padding: 0 5px; font-size: 8px; }
        .logo-cell { width: 50px; text-align: center; padding-right: 10px; }
        .logo-cell img { height: 40px; }
        .instansi-cell { text-align: center; }
        .instansi-cell h1 { font-size: 11px; font-weight: bold; margin: 0; }
        .instansi-cell p { font-size: 7px; margin: 1px 0; }
        .pasien-info-table { width: 100%; border: none !important; margin-top: 8px; }
        .pasien-info-table td { border: none !important; text-align: left; padding: 1px 0; font-size: 8px; vertical-align: top; }
        .label-col { width: 70px; font-weight: normal; } /* Lebar label (NAMA, NO RM, dll) */
        /* Jika perlu page break manual */

    </style>
</head>
<body>
  {{-- ========== HEADER GABUNGAN (PROFESIONAL) ========== --}}
    <div class="header-container">
        {{-- Baris 1: Logo & Info Instansi --}}
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if($setting && $setting->logo)
                        <img src="data:image/png;base64,{{ base64_encode($setting->logo) }}" alt="Logo">
                    @endif
                </td>
                <td class="instansi-cell">
                    <h1>{{ $setting->nama_instansi ?? 'Nama Instansi' }}</h1>
                    <p>{{ $setting->alamat_instansi ?? '' }} {{ $setting->kabupaten ?? '' }}{{ ($setting->kabupaten && $setting->propinsi) ? ', ' : '' }}{{ $setting->propinsi ?? '' }}</p>
                    <p>Telp: {{ $setting->kontak ?? '' }} | Email: {{ $setting->email ?? '' }}</p>
                </td>
                {{-- Kolom Kosong Kanan (jika perlu alignment) --}}
                <td style="width: 50px;"></td>
            </tr>
        </table>
<hr>
        {{-- Baris 2: Judul Laporan --}}
        <h2 style="text-align: center; font-size: 11px; margin-top: 5px; margin-bottom: 5px; font-weight: bold;">LEMBAR OBSERVASI ICU</h2>

        {{-- Baris 3: Info Pasien & Lokasi (2 Kolom) --}}
        <table class="pasien-info-table">
             <tr>
                {{-- Kolom Kiri: Data Pasien --}}
                <td style="width: 50%; padding-right: 10px;">
                    <table class="no-border">
                        <tr><td class="label-col">NAMA</td><td>: {{ $registrasi->pasien->nm_pasien ?? 'N/A' }}</td></tr>
                        <tr><td class="label-col">TGL. LAHIR/UMUR</td><td>: {{ $registrasi->pasien->tgl_lahir ? \Carbon\Carbon::parse($registrasi->pasien->tgl_lahir)->format('d-m-Y') : '-' }} / {{ $registrasi->umurdaftar ?? '' }}{{ $registrasi->sttsumur ?? '' }}</td></tr>
                        <tr><td class="label-col">NO. RM</td><td>: {{ $registrasi->pasien->no_rkm_medis ?? 'N/A' }}</td></tr>
                        <tr><td class="label-col">CARA BAYAR</td><td>: {{ $registrasi->penjab->png_jawab ?? 'N/A' }}</td></tr>
                    </table>
                </td>
                {{-- Kolom Kanan: Data Lokasi --}}
                <td style="width: 50%; padding-left: 10px;">
                     <table class="no-border">
                        <tr><td class="label-col">INSTALASI</td><td>: {{ $currentInstallasiName ?? 'N/A' }}</td></tr>
                        <tr><td class="label-col">RUANG</td><td>: {{ $currentRoomName ?? 'N/A' }}</td></tr>
                        <tr><td class="label-col">ASAL RUANGAN</td><td>: {{ $originatingWardName ?? 'N/A' }}</td></tr>
                        <tr><td class="label-col">HARI RAWAT KE</td><td>: {{ $cycle->hari_rawat_ke ?? 'N/A' }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    {{-- ========== AKHIR HEADER GABUNGAN ========== --}}

    {{-- Tabel Utama Dua Kolom --}}
    <table class="no-border">
        <tr>
            {{-- ==================== KOLOM KIRI ==================== --}}
            <td class="left-col">



                {{-- Dokter & Diagnosa --}}
                <div class="section-title">Dokter / DPJP</div>
                <div style="font-size: 7px; padding-left: 5px;">
                    @forelse($cycle->dpjpDokter as $dokter)
                    {{ $loop->iteration }}. {{ $dokter->nm_dokter }}<br>
                    @empty - @endforelse
                </div>
                <div class="section-title">Diagnosa</div>
                <div style="font-size: 7px; padding-left: 5px; white-space: pre-wrap;">{{ $cycle->diagnosa ?: '-' }}</div>
                <hr>

                {{-- Obat --}}
                <div class="section-title">OBAT</div>
                <div style="font-size: 7px; padding-left: 5px; white-space: pre-wrap;">Parenteral:<br>{{ $cycle->terapi_obat_parenteral ?: '-' }}<br>Enteral/Lain:<br>{{ $cycle->terapi_obat_enteral_lain ?: '-'}}</div>
                <hr>

                {{-- Penunjang --}}
                <div class="section-title">PEMERIKSAAN PENUNJANG</div>
                <div style="font-size: 7px; padding-left: 5px; white-space: pre-wrap;">{{ $cycle->pemeriksaan_penunjang ?: '-' }}</div>
                <hr>

                {{-- Catatan Lain --}}
                <div class="section-title">CATATAN / LAIN-LAIN</div>
                <div style="font-size: 7px; padding-left: 5px; white-space: pre-wrap;">{{ $cycle->catatan_lain_lain ?: '-' }}</div>
                <hr>

                {{-- Alat & Tube --}}
                <div class="section-title">ALAT TERPASANG & TUBE</div>
                <table class="no-border device-table" style="margin-left: -2px;"> {{-- Adjust margin if needed --}}
                    <thead>
                        <tr>
                            <th style="width: 40%;">Jenis</th>
                            <th style="width: 15%;">Ukr</th>
                            <th style="width: 25%;">Lokasi</th>
                            <th style="width: 20%;">Tgl</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $alatDisplayed = false; @endphp
                        @foreach ($cycle->devices->where('device_category', 'ALAT')->sortBy('device_name') as $device)
                        <tr>
                            <td>{{ $device->device_name }}</td>
                            <td>{{ $device->ukuran ?: '-' }}</td>
                            <td>{{ $device->lokasi ?: '-' }}</td>
                            <td>{{ $device->tanggal_pasang ? $device->tanggal_pasang->format('d/m') : '-' }}</td>
                        </tr>
                        @php $alatDisplayed = true; @endphp
                        @endforeach
                        @if(!$alatDisplayed) <tr>
                            <td colspan="4">-</td>
                        </tr> @endif

                        @php $tubeDisplayed = false; @endphp
                        @foreach ($cycle->devices->where('device_category', 'TUBE')->sortBy('device_name') as $device)
                        <tr>
                            <td>{{ $device->device_name }}</td>
                            <td>{{ $device->ukuran ?: '-' }}</td>
                            <td>{{ $device->lokasi ?: '-' }}</td>
                            <td>{{ $device->tanggal_pasang ? $device->tanggal_pasang->format('d/m') : '-' }}</td>
                        </tr>
                        @php $tubeDisplayed = true; @endphp
                        @endforeach
                        @if(!$tubeDisplayed) <tr>
                            <td colspan="4">-</td>
                        </tr> @endif
                    </tbody>
                </table>
                <hr>

                {{-- Balance Cairan Summary Box --}}
                <div class="section-title">BALANCE CAIRAN</div>
                <div class="balance-box">
                    <table class="no-border">
                        <tr>
                            <th>MASUK</th>
                            <td style="text-align: right;">{{ number_format($totalMasuk, 0) }} ml</td>
                        </tr>
                        <tr>
                            <th>KELUAR</th>
                            <td style="text-align: right;">{{ number_format($totalKeluar, 0) }} ml</td>
                        </tr>
                        <tr>
                            <th>IWL</th>
                            <td style="text-align: right;">{{ number_format($iwl, 0) }} ml</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <hr style="border-style: dashed;">
                            </td>
                        </tr>
                        <tr>
                            <th>BC 24 Jam</th>
                            <td style="text-align: right; font-weight: bold;">{{ number_format($balance24Jam, 0) }} ml</td>
                        </tr>
                        <tr>
                            <th>BC Sblmnya</th>
                            <td style="text-align: right;">{{ number_format($previousBalance, 0) }} ml</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <hr>
                            </td>
                        </tr>
                        <tr>
                            <th style="font-size: 9px;">BC Kumulatif</th>
                            <td style="text-align: right; font-weight: bold; font-size: 9px;">{{ number_format($previousBalance + $balance24Jam, 0) }} ml</td>
                        </tr>
                    </table>
                </div>

            </td>

            {{-- ==================== KOLOM KANAN =================== --}}
            <td class="right-col no-border">
                {{-- Tabel Observasi Dinamis --}}
                <table class="observation-table">
                    <thead>
                        <tr>
                            <th class="param-label">Parameter</th>
                            {{-- Generate Kolom Waktu --}}
                            @foreach($allRecords as $record)
                            <th class="time-header">
                                {{ $record->observation_time->format('H:i') }}<br>
                                <span class="author-header">{{ $record->inputter->nama ?? 'N/A' }}</span>
                            </th>
                            @endforeach
                            {{-- Tambahkan kolom kosong jika data sedikit agar tabel penuh --}}
                            @if($allRecords->count() < 15) {{-- Sesuaikan angka minimal --}} @for ($i=0; $i < (15 - $allRecords->count()); $i++)
                                <th class="time-header"></th>
                                @endfor
                                @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php $currentGroup = ''; @endphp
                        @foreach($allParameters as $param)

                        {{-- SECTION KHUSUS UNTUK GRUP CAIRAN --}}
                        @if ($param['group'] == 'CAIRAN')
                        @if ($currentGroup != 'CAIRAN')
                        @php $currentGroup = 'CAIRAN'; @endphp
                        <tr class="bg-gray-50 border-t border-b">
                            <td colspan="{{ max(15, $allRecords->count()) + 1 }}" class="group-label">CAIRAN MASUK</td>
                        </tr>
                        {{-- Parenteral --}}
                        @forelse ($uniqueParenteralFluids as $fluidName)
                        <tr>
                            <th class="param-label" style="padding-left: 15px;">{{ $fluidName }}</th>
                            @foreach($allRecords as $record)
                            <td>@if($record->is_parenteral && $record->cairan_masuk_jenis == $fluidName && $record->cairan_masuk_volume)<span class="font-semibold text-green-700">{{ $record->cairan_masuk_volume }}</span>@endif</td>
                            @endforeach
                            @if($allRecords->count() < 15) @for ($i=0; $i < (15 - $allRecords->count()); $i++) <td></td> @endfor @endif
                        </tr>
                        @empty @endforelse
                        {{-- Enteral --}}
                        @forelse ($uniqueEnteralFluids as $fluidName)
                        <tr>
                            <th class="param-label" style="padding-left: 15px;">{{ $fluidName }}</th>
                            @foreach($allRecords as $record)
                            <td>@if($record->is_enteral && $record->cairan_masuk_jenis == $fluidName && $record->cairan_masuk_volume)<span class="font-semibold text-green-700">{{ $record->cairan_masuk_volume }}</span>@endif</td>
                            @endforeach
                            @if($allRecords->count() < 15) @for ($i=0; $i < (15 - $allRecords->count()); $i++) <td></td> @endfor @endif
                        </tr>
                        @empty @endforelse
                        <tr class="bg-gray-50 border-t border-b">
                            <td colspan="{{ max(15, $allRecords->count()) + 1 }}" class="group-label">CAIRAN KELUAR</td>
                        </tr>
                        {{-- Cairan Keluar --}}
                        @php $keluarTypes = ['Irigasi CM', 'Irigasi CK', 'Urine', 'NGT', 'Drain/WSD 1', 'Drain/WSD 2']; @endphp
                        @foreach($keluarTypes as $type)
                        <tr>
                            <th class="param-label">{{ $type }}</th>
                            @foreach($allRecords as $record)
                            <td>@if($record->cairan_keluar_jenis == $type && $record->cairan_keluar_volume)<span class="font-semibold text-red-700">{{ $record->cairan_keluar_volume }}</span>@endif</td>
                            @endforeach
                            @if($allRecords->count() < 15) @for ($i=0; $i < (15 - $allRecords->count()); $i++) <td></td> @endfor @endif
                        </tr>
                        @endforeach
                        @endif
                        {{-- SECTION UNTUK GRUP LAIN --}}
                        @else
                        @if ($param['group'] != $currentGroup)
                        <tr class="bg-gray-50 border-t border-b">
                            <td colspan="{{ max(15, $allRecords->count()) + 1 }}" class="group-label">{{ $param['group'] }}</td>
                        </tr>
                        @php $currentGroup = $param['group']; @endphp
                        @endif
                        <tr>
                            <th class="param-label">{{ $param['label'] }}</th>
                            @foreach($allRecords as $record)
                            <td>
                                @php
                                $value = null; $key = $param['key'];
                                if ($key == 'tensi' && $record->tensi_sistol) { $value = $record->tensi_sistol . '/' . $record->tensi_diastol; }
                                elseif ($key == 'gcs' && ($record->gcs_e || $record->gcs_v || $record->gcs_m)) { $gcsTotal = $record->gcs_total ?? (($record->gcs_e ?? 0) + ($record->gcs_v ?? 0) + ($record->gcs_m ?? 0)); $value = "E".($record->gcs_e ?? '-')."V".($record->gcs_v ?? '-')."M".($record->gcs_m ?? '-').($gcsTotal > 0 ? "($gcsTotal)" : ''); }
                                elseif ($key == 'pupil' && ($record->pupil_left_size_mm || $record->pupil_right_size_mm)) { $left = ($record->pupil_left_size_mm ?? '-') . '/' . ($record->pupil_left_reflex ?? '-'); $right = ($record->pupil_right_size_mm ?? '-') . '/' . ($record->pupil_right_reflex ?? '-'); $value = "{$left}|{$right}"; }
                                elseif ($key == 'clinical_note' && $record->clinical_note) { $value = $record->clinical_note; }
                                elseif ($key == 'medication_administration' && $record->medication_administration) { $value = $record->medication_administration; }
                                elseif (isset($record->$key) && !in_array($key, ['tensi', 'gcs', 'pupil', 'clinical_note', 'medication_administration', 'cairan_masuk', 'cairan_keluar', 'is_enteral', 'is_parenteral'])) { $value = $record->$key; }
                                // --- PERBAIKI BAGIAN INI ---
                                // Ganti isset($record->$key) menjadi $record->{$key} !== null
                                // Tambahkan 'is_enteral' dan 'is_parenteral' ke array pengecualian
                                elseif ($record->{$key} !== null && !in_array($key, [
                                'tensi_sistol', 'tensi_diastol', // Pecah tensi agar tidak bentrok
                                'gcs_e','gcs_v','gcs_m','gcs_total', // Pecah GCS
                                'pupil_left_size_mm','pupil_left_reflex','pupil_right_size_mm','pupil_right_reflex', // Pecah pupil
                                'clinical_note', 'medication_administration',
                                'cairan_masuk_jenis','cairan_masuk_volume', 'cairan_keluar_jenis','cairan_keluar_volume', // Pecah cairan
                                'is_enteral', 'is_parenteral'
                                ]))
                                {
                                $value = $record->{$key};
                                }
                                @endphp
                                <span class="whitespace-pre-wrap">{!! $value !!}</span>
                            </td>
                            @endforeach
                            @if($allRecords->count() < 15) @for ($i=0; $i < (15 - $allRecords->count()); $i++) <td></td> @endfor @endif
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
