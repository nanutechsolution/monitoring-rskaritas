<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan ICU - {{ $registrasi->pasien->nm_pasien }} - {{ $cycle->sheet_date->format('d-m-Y') }}</title>
    {{-- CSS Inline untuk PDF A3 Landscape --}}
    <style>
        @page {
            margin: 15px 20px;
            size: A3 landscape;
        }

        /* Atur margin & ukuran A3 Landscape */
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 9px;
            line-height: 1.3;
        }

        /* Font sedikit lebih besar untuk A3 */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Terapkan border HANYA pada tabel observasi dan device */
        .observation-table th,
        .observation-table td,
        .device-table th,
        .device-table td {
            border: 1px solid #ccc;
            padding: 3px 4px;
            /* Sedikit lebih lega */
        }

        /* Style untuk TH default dan TH di tabel spesifik */
        th,
        .observation-table th,
        .device-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .header-rs-table td {
            padding: 1px 0;
            font-size: 8px;
        }

        .header-pasien-table td {
            padding: 1px 0;
            font-size: 9px;
        }

        /* Style Header Gabungan */
        .header-container {
            border-bottom: 1.5px solid black;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .header-table {
            width: 100%;
        }

        .header-table td {
            vertical-align: top;
            padding: 0 5px;
            font-size: 9px;
        }

        .logo-cell {
            width: 60px;
            text-align: center;
            padding-right: 10px;
        }

        .logo-cell img {
            height: 50px;
        }

        .instansi-cell {
            text-align: center;
        }

        .instansi-cell h1 {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }

        .instansi-cell p {
            font-size: 9px;
            margin: 1px 0;
        }

        .pasien-info-table {
            width: 100%;
            margin-top: 10px;
        }

        .pasien-info-table td {
            text-align: left;
            padding: 1px 0;
            font-size: 9px;
            vertical-align: top;
        }

        .label-col {
            width: 85px;
            font-weight: normal;
        }

        /* Layout Kolom Kiri & Kanan */
        .left-col {
            width: 30%;
            vertical-align: top;
            padding-right: 8px;
        }

        .right-col {
            width: 70%;
            vertical-align: top;
            padding-left: 8px;
        }

        .section-title {
            font-size: 10px;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 2px;
            text-align: left;
            background-color: #f0f0f0;
            padding: 2px 4px;
            border: 1px solid #ddd;
        }

        /* Style Tabel Observasi */
        .param-label {
            text-align: left;
            font-weight: bold;
            width: 100px;
            background-color: #f9f9f9;
            font-size: 8px;
        }

        .group-label {
            background-color: #e2e8f0;
            font-weight: bold;
            text-align: left;
            font-size: 10px;
            padding: 3px 4px;
        }

        .time-header {
            font-size: 8px;
            width: 40px;
        }

        .author-header {
            font-size: 7px;
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

        /* Style Lainnya */
        .balance-box table td {
            font-size: 9px;
            padding: 1px 2px;
        }

        .balance-box th {
            font-size: 9px;
            text-align: left;
            padding: 1px 2px;
            width: 80px;
        }

        hr {
            border: none;
            border-top: 1px solid #ccc;
            margin: 3px 0;
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
            font-size: 8px;
            padding: 1px 2px;
            border: 1px solid #ccc;
        }

        .page-break {
            page-break-after: always;
        }

        /* Tabel Layout Utama */
        .layout-table>tbody>tr>td {
            /* Target TD langsung di bawah TR tabel layout */
            vertical-align: top;
            /* <<< Pastikan align top >>> */
            border: none !important;
            /* Hapus border */
            padding: 0;
            /* Hapus padding default TD */
        }

        .left-col {
            width: 20%;
            padding-right: 8px;
        }

        .right-col {
            width: 80%;
            padding-left: 8px;
        }

        /* Style Header Gabungan */
        .header-container {
            border-bottom: 1.5px solid black;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }



    </style>
</head>
<body>
    {{-- ========== HEADER GABUNGAN (PROFESIONAL) ========== --}}
    <div class="header-container">
        <table class="header-table no-border">
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
                <td style="width: 60px;"></td>
            </tr>
        </table>
        <h2 style="text-align: center; font-size: 12px; margin-top: 8px; margin-bottom: 8px; font-weight: bold;">LEMBAR OBSERVASI ICU</h2>
        <table class="pasien-info-table no-border">
            <tr>
                <td style="width: 50%; padding-right: 15px;">
                    <table class="no-border">
                        <tr>
                            <td class="label-col">NAMA</td>
                            <td>: {{ $registrasi->pasien->nm_pasien ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">TGL. LAHIR/UMUR</td>
                            <td>: {{ $registrasi->pasien->tgl_lahir ? \Carbon\Carbon::parse($registrasi->pasien->tgl_lahir)->format('d-m-Y') : '-' }} / {{ $registrasi->umurdaftar ?? '' }}{{ $registrasi->sttsumur ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">NO. RM</td>
                            <td>: {{ $registrasi->pasien->no_rkm_medis ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">CARA BAYAR</td>
                            <td>: {{ $registrasi->penjab->png_jawab ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%; padding-left: 15px;">
                    <table class="no-border">
                        <tr>
                            <td class="label-col">INSTALASI</td>
                            <td>: {{ $currentInstallasiName ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">RUANG</td>
                            <td>: {{ $currentRoomName ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">ASAL RUANGAN</td>
                            <td>: {{ $originatingWardName ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">HARI RAWAT KE</td>
                            <td>: {{ $cycle->hari_rawat_ke ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    {{-- ========== AKHIR HEADER GABUNGAN ========== --}}


    {{-- Tabel Utama Dua Kolom (Layout Kiri & Kanan) --}}
  <table class="layout-table no-border">
        <tr>
            {{-- ==================== KOLOM KIRI (Data Statis & Balance) ==================== --}}
            <td class="left-col">
                {{-- Dokter & Diagnosa --}}
                <div class="section-title">Dokter / DPJP</div>
                <div style="font-size: 8px; padding-left: 5px;">
                    @forelse($dpjpDokters as $dokter) {{ $loop->iteration }}. {{ $dokter->nm_dokter }}<br> @empty - @endforelse
                </div>
                <div class="section-title">Diagnosa</div>
                <div style="font-size: 8px; padding-left: 5px; white-space: pre-wrap;">
                    @forelse($diagnosaPasien as $diagnosa) {{ $loop->iteration }}. {{ $diagnosa }}<br> @empty - @endforelse
                </div>
                <hr>
                {{-- Pola Ventilasi --}}
                <div class="section-title">POLA VENTILASI</div>
                <div style="font-size: 8px; padding-left: 5px; white-space: pre-wrap;">{!! nl2br(e($cycle->ventilator_notes ?: '-')) !!}</div>
                <hr>
                {{-- Obat --}}
                <div class="section-title">OBAT</div>
                <div style="font-size: 8px; padding-left: 5px; white-space: pre-wrap;">Parenteral:<br>{!! nl2br(e($cycle->terapi_obat_parenteral ?: '-')) !!}<br>Enteral/Lain:<br>{!! nl2br(e($cycle->terapi_obat_enteral_lain ?: '-')) !!}</div>
                {{-- Target Nutrisi --}}
                <div style="margin-top: 3px; padding-left: 5px;">
                    <div style="font-size: 8px; font-weight: bold;">Target Nutrisi Parenteral (24h):</div>
                    <table class="no-border" style="font-size: 7px; margin-top: 1px;">
                        <tr>
                            <td style="width: 40px;">Vol:</td>
                            <td>{{ $cycle->parenteral_target_volume ?: '-' }} ml</td>
                            <td style="width: 40px;">Kal:</td>
                            <td>{{ $cycle->parenteral_target_kalori ?: '-' }} kkal</td>
                        </tr>
                        <tr>
                            <td>Pro:</td>
                            <td>{{ $cycle->parenteral_target_protein ?: '-' }} g</td>
                            <td>Lem:</td>
                            <td>{{ $cycle->parenteral_target_lemak ?: '-' }} g</td>
                        </tr>
                    </table>
                </div>
                <div style="margin-top: 3px; padding-left: 5px;">
                    <div style="font-size: 8px; font-weight: bold;">Target Nutrisi Enteral (24h):</div>
                    <table class="no-border" style="font-size: 7px; margin-top: 1px;">
                        <tr>
                            <td style="width: 40px;">Vol:</td>
                            <td>{{ $cycle->enteral_target_volume ?: '-' }} ml</td>
                            <td style="width: 40px;">Kal:</td>
                            <td>{{ $cycle->enteral_target_kalori ?: '-' }} kkal</td>
                        </tr>
                        <tr>
                            <td>Pro:</td>
                            <td>{{ $cycle->enteral_target_protein ?: '-' }} g</td>
                            <td>Lem:</td>
                            <td>{{ $cycle->enteral_target_lemak ?: '-' }} g</td>
                        </tr>
                    </table>
                </div>
                <hr>
                {{-- Penunjang --}}
                <div class="section-title">PEMERIKSAAN PENUNJANG</div>
                <div style="font-size: 8px; padding-left: 5px; white-space: pre-wrap;">{!! nl2br(e($cycle->pemeriksaan_penunjang ?: '-')) !!}</div>
                <hr>
                {{-- Catatan Lain --}}
                <div class="section-title">CATATAN / LAIN-LAIN</div>
                <div style="font-size: 8px; padding-left: 5px; white-space: pre-wrap;">{!! nl2br(e($cycle->catatan_lain_lain ?: '-')) !!}</div>
                <hr>
                {{-- Alat & Tube --}}
                <div class="section-title">ALAT TERPASANG & TUBE</div>
                <table class="device-table" style="margin-bottom: 5px;">
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
                            <td colspan="4" style="text-align: center;">-</td>
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
                            <td colspan="4" style="text-align: center;">-</td>
                        </tr> @endif
                    </tbody>
                </table>
                {{-- Luka --}}
                <div class="section-title">LUKA</div>
                <div style="font-size: 8px; padding-left: 5px; white-space: pre-wrap; min-height: 20px;">{!! nl2br(e($cycle->wound_notes ?: '-')) !!}</div>
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
                            <th style="font-size: 10px;">BC Kumulatif</th>
                            <td style="text-align: right; font-weight: bold; font-size: 10px;">{{ number_format($previousBalance + $balance24Jam, 0) }} ml</td>
                        </tr>
                    </table>
                </div>
            </td>

            {{-- ==================== KOLOM KANAN (Tabel Observasi) =================== --}}
            <td class="right-col">
                {{-- Tabel Observasi Dinamis (Per Menit) --}}
              <table class="observation-table">
                    <thead>
                        <tr>
                            <th class="param-label">Parameter</th>
                            @foreach($uniqueTimestamps as $timestamp)
                            <th class="time-header">
                                {{ \Carbon\Carbon::parse($timestamp)->format('H:i') }}<br>
                                <span class="author-header">{{ $mergedRecordsPerMinute[$timestamp]->inputters }}</span>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php $currentGroup = ''; @endphp
                        @foreach($allParameters as $param)
                        @if ($param['group'] == 'CAIRAN')
                        @if ($currentGroup != 'CAIRAN')
                        @php $currentGroup = 'CAIRAN'; @endphp
                        <tr>
                            <td colspan="{{ $uniqueTimestamps->count() + 1 }}" class="group-label">CAIRAN MASUK</td>
                        </tr>
                        @forelse ($uniqueParenteralFluids as $fluidName)
                        <tr>
                            <th class="param-label" style="padding-left: 15px;">{{ $fluidName }}</th>
                            @foreach($uniqueTimestamps as $timestamp)
                            <td>
                                @php $vol = collect($mergedRecordsPerMinute[$timestamp]->fluids_in)->where('is_parenteral', true)->where('jenis', $fluidName)->sum('volume'); @endphp
                                @if($vol > 0) <span class="font-semibold text-green-700">{{ $vol }}</span> @endif
                            </td>
                            @endforeach
                        </tr>
                        @empty @endforelse
                        @forelse ($uniqueEnteralFluids as $fluidName)
                        <tr>
                            <th class="param-label" style="padding-left: 15px;">{{ $fluidName }}</th>
                            @foreach($uniqueTimestamps as $timestamp)
                            <td>
                                @php $vol = collect($mergedRecordsPerMinute[$timestamp]->fluids_in)->where('is_enteral', true)->where('jenis', $fluidName)->sum('volume'); @endphp
                                @if($vol > 0) <span class="font-semibold text-green-700">{{ $vol }}</span> @endif
                            </td>
                            @endforeach
                        </tr>
                        @empty @endforelse
                        <tr style="background-color: #f0f8ff;">
                            <th class="param-label">TOTAL Cairan Masuk</th>
                            @foreach($uniqueTimestamps as $timestamp)
                            <td>
                                @php $totalIn = collect($mergedRecordsPerMinute[$timestamp]->fluids_in)->sum('volume'); @endphp
                                @if($totalIn > 0) <span class="font-bold text-green-700">{{ $totalIn }}</span> @endif
                            </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td colspan="{{ $uniqueTimestamps->count() + 1 }}" class="group-label">CAIRAN KELUAR</td>
                        </tr>
                        @php $keluarTypes = ['Irigasi CM', 'Irigasi CK', 'Urine', 'NGT', 'Drain/WSD 1', 'Drain/WSD 2', 'Lainnya']; @endphp
                        @foreach($keluarTypes as $type)
                        <tr>
                            <th class="param-label">{{ $type }}</th>
                            @foreach($uniqueTimestamps as $timestamp)
                            <td>
                                @php $vol = collect($mergedRecordsPerMinute[$timestamp]->fluids_out)->where('jenis', $type)->sum('volume'); @endphp
                                @if($vol > 0) <span class="font-semibold text-red-700">{{ $vol }}</span> @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                        <tr style="background-color: #fff0f5;">
                            <th class="param-label">TOTAL Cairan Keluar</th>
                            @foreach($uniqueTimestamps as $timestamp)
                            <td>
                                @php $totalOut = collect($mergedRecordsPerMinute[$timestamp]->fluids_out)->sum('volume'); @endphp
                                @if($totalOut > 0) <span class="font-bold text-red-700">{{ $totalOut }}</span> @endif
                            </td>
                            @endforeach
                        </tr>
                        @endif
                        @else
                        @if ($param['group'] != $currentGroup)
                        <tr>
                            <td colspan="{{ $uniqueTimestamps->count() + 1 }}" class="group-label">{{ $param['group'] }}</td>
                        </tr>
                        @php $currentGroup = $param['group']; @endphp
                        @endif
                        @php
                        $key = $param['key'];
                        $hasData = $mergedRecordsPerMinute->contains(function ($mergedRecord) use ($key) {
                        if ($key == 'tensi') { return $mergedRecord->tensi_sistol !== null || $mergedRecord->tensi_diastol !== null; }
                        elseif ($key == 'gcs') { return $mergedRecord->gcs_e !== null || $mergedRecord->gcs_v !== null || $mergedRecord->gcs_m !== null; }
                        elseif ($key == 'pupil') { return $mergedRecord->pupil_left_size_mm !== null || $mergedRecord->pupil_right_size_mm !== null || $mergedRecord->pupil_left_reflex !== null || $mergedRecord->pupil_right_reflex !== null;}
                        elseif (in_array($key, ['clinical_note', 'medication_administration'])) { return !empty($mergedRecord->{$key}); }
                        else { return isset($mergedRecord->{$key}) && $mergedRecord->{$key} !== null; } // Cek isset juga
                        });
                        @endphp
                        @if($hasData)
                        <tr>
                            <th class="param-label">{{ $param['label'] }}</th>
                            @foreach($uniqueTimestamps as $timestamp)
                            <td>
                                @php
                                $record = $mergedRecordsPerMinute[$timestamp];
                                $value = null;
                                if ($key == 'tensi' && isset($record->tensi_sistol)) { $value = $record->tensi_sistol . '/' . $record->tensi_diastol; }
                                elseif ($key == 'gcs' && (isset($record->gcs_e) || isset($record->gcs_v) || isset($record->gcs_m))) { $gcsTotal = (($record->gcs_e ?? 0) + ($record->gcs_v ?? 0) + ($record->gcs_m ?? 0)); $value = "E".($record->gcs_e ?? '-')."V".($record->gcs_v ?? '-')."M".($record->gcs_m ?? '-').($gcsTotal > 0 ? "($gcsTotal)" : ''); }
                                elseif ($key == 'pupil' && (isset($record->pupil_left_size_mm) || isset($record->pupil_right_size_mm))) { $left = ($record->pupil_left_size_mm ?? '-') . '/' . ($record->pupil_left_reflex ?? '-'); $right = ($record->pupil_right_size_mm ?? '-') . '/' . ($record->pupil_right_reflex ?? '-'); $value = "{$left}|{$right}"; }
                                elseif ($key == 'clinical_note' && !empty($record->clinical_note)) { $value = $record->clinical_note; }
                                elseif ($key == 'medication_administration' && !empty($record->medication_administration)) { $value = $record->medication_administration; }
                                // Fallback: Cek isset SEBELUM akses properti
                                elseif (isset($record->{$key}) && $record->{$key} !== null && !in_array($key, ['tensi_sistol','tensi_diastol','gcs_e','gcs_v','gcs_m','gcs_total','pupil_left_size_mm','pupil_left_reflex','pupil_right_size_mm','pupil_right_reflex','clinical_note','medication_administration','cairan_masuk_jenis','cairan_masuk_volume','cairan_keluar_jenis','cairan_keluar_volume','is_enteral','is_parenteral'])) { $value = $record->{$key}; }
                                @endphp
                                <span class="whitespace-pre-wrap">{!! $value !!}</span>
                            </td>
                            @endforeach
                        </tr>
                        @endif
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </td>
            {{-- ==================== AKHIR KOLOM KANAN =================== --}}
        </tr>
    </table>

</body>
</html>
