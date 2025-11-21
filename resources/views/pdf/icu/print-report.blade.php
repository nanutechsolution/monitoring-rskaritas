<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan ICU - {{ $registrasi->pasien->nm_pasien }} - {{ $cycle->sheet_date->format('d-m-Y') }}</title>
    <style>
        @page {
            margin: 10px;
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
    <div class="header-container">
        {{-- Tabel Utama 3 Kolom untuk Kop & Info Pasien --}}
        <table class="header-main-table no-border" style="width: 100%; border-collapse: collapse;">
            <tr>
                {{-- KOLOM 1: LOGO --}}
                <td style="width: 15%; vertical-align: top; text-align: left;">
                    @if($setting && $setting->logo)
                    {{-- Pastikan lebar gambar diatur agar tidak terlalu besar --}}
                    <img src="data:image/png;base64,{{ base64_encode($setting->logo) }}" alt="Logo" style="max-width: 80px; height: auto;">
                    @endif
                </td>

                {{-- KOLOM 2: NAMA RS & JUDUL DOKUMEN --}}
                <td style="width: 60%; vertical-align: top; text-align: center;">
                    <h1 style="font-size: 14px; margin-top: 0; margin-bottom: 5px; font-weight: bold;">{{ $setting->nama_instansi ?? 'Nama Instansi' }}</h1>
                    <p style="font-size: 10px; margin: 0;">{{ $setting->alamat_instansi ?? '' }} {{ $setting->kabupaten ?? '' }}{{ ($setting->kabupaten && $setting->propinsi) ? ', ' : '' }}{{ $setting->propinsi ?? '' }}</p>
                    <p style="font-size: 10px; margin-top: 2px;">Telp: {{ $setting->kontak ?? '' }} | Email: {{ $setting->email ?? '' }}</p>

                    {{-- Judul Dokumen dipisah dengan garis --}}
                    <hr style="border: 0; border-top: 1px solid black; margin: 5px 0 5px 0;">
                    <h2 style="font-size: 12px; margin: 0; font-weight: bold;">LEMBAR OBSERVASI ICU</h2>
                </td>

                {{-- KOLOM 3: INFORMASI PASIEN (Gabungan Kanan Atas) --}}
                <td style="width: 25%; vertical-align: top; text-align: left; border: 1px solid black; padding: 3px 5px;">
                    <table class="no-border" style="width: 100%; font-size: 9px; border-collapse: collapse;">
                        <tr>
                            <td class="label-col" style="width: 45%;">NAMA</td>
                            <td style="width: 55%;">: {{ $registrasi->pasien->nm_pasien ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">NO. RM</td>
                            <td>: {{ $registrasi->pasien->no_rkm_medis ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">TGL. LAHIR/UMUR</td>
                            <td>: {{ $registrasi->pasien->tgl_lahir ? \Carbon\Carbon::parse($registrasi->pasien->tgl_lahir)->format('d-m-Y') : '-' }} / {{ $registrasi->umurdaftar ?? '' }}{{ $registrasi->sttsumur ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">RUANG</td>
                            <td>: {{ $currentRoomName ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">HARI RAWAT KE</td>
                            <td>: {{ $hospitalDayNumber ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">TGL. OBSERVASI</td>
                            <td>: {{ \Carbon\Carbon::parse($sheetDate)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">CARA BAYAR</td>
                            <td>: {{ $registrasi->penjab->png_jawab ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- BARIS KOSONG DI BAWAH (untuk spacing) --}}
        <div style="height: 10px;"></div>

    </div>
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

            {{-- ==================== KOLOM KANAN =================== --}}
            <td class="right-col">
                @if(isset($hemodynamicChartUri) && $hemodynamicChartUri)
                <h4 style="margin-top: 5px; margin-bottom: 5px; font-weight: bold;">GRAFIK HEMODINAMIK & TTV INTI</h4>
                <div style="text-align: center; margin-bottom: 10px;">
                    {{-- Gambar Base64 yang dihasilkan oleh QuickChart --}}
                    <img src="{{ $hemodynamicChartUri }}" style="width: 100%; height: 250px; object-fit: contain;">
                </div>
                <div style="margin-top: 15px;"></div>
                @else
                <p style="text-align: center; color: red;">Gagal memuat grafik Hemodinamik. Cek koneksi server atau Log.</p>
                @endif

                {{-- ==================== 1. Tabel HEMODINAMIK (Tabel Paling Atas) =================== --}}
                <!-- @if($uniqueTimestampsHemodynamic->count() > 0)
                <h4 style="margin-top: 5px; margin-bottom: 5px; font-weight: bold;">HEMODINAMIK (TTV INTI)</h4>
                <table class="observation-table">
                    <thead>
                        <tr>
                            <th class="param-label">Parameter</th>
                            @foreach($uniqueTimestampsHemodynamic as $timestamp)
                            <th class="time-header">
                                {{ \Carbon\Carbon::parse($timestamp)->format('H:i') }}<br>
                                <span class="author-header">{{ $mergedRecordsPerMinute[$timestamp]->inputters }}</span>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allParameters as $param)

                        {{-- HANYA TAMPILKAN GRUP HEMODINAMIK --}}
                        @if ($param['group'] == 'HEMODINAMIK')
                        @php
                        $key = $param['key'];
                        // Cek apakah ada data di kolom Hemodinamik yang difilter
                        $hasDataInFilteredColumns = false;
                        foreach($uniqueTimestampsHemodynamic as $timestamp) {
                        $record = $mergedRecordsPerMinute[$timestamp];
                        if ($key == 'tensi' && ($record->tensi_sistol !== null || $record->tensi_diastol !== null)) { $hasDataInFilteredColumns = true; break; }
                        elseif ($record->{$key} !== null) { $hasDataInFilteredColumns = true; break; }
                        }
                        @endphp

                        @if($hasDataInFilteredColumns)
                        <tr>
                            <th class="param-label">{{ $param['label'] }}</th>
                            @foreach($uniqueTimestampsHemodynamic as $timestamp)
                            <td>
                                @php
                                $record = $mergedRecordsPerMinute[$timestamp];
                                $value = null;

                                if ($key == 'tensi' && (isset($record->tensi_sistol) || isset($record->tensi_diastol))) { $value = ($record->tensi_sistol ?? '-') . '/' . ($record->tensi_diastol ?? '-'); }
                                elseif (isset($record->{$key}) && $record->{$key} !== null) { $value = $record->{$key}; }
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
                <div style="margin-top: 15px;"></div>
                @endif -->


                {{-- ==================== 2. Tabel VENTILATOR SETTING =================== --}}
                @if($uniqueTimestampsVentilator->count() > 0)
                <h4 style="margin-top: 5px; margin-bottom: 5px; font-weight: bold;">VENTILATOR SETTING</h4>
                <table class="observation-table">
                    <thead>
                        <tr>
                            <th class="param-label">Parameter</th>
                            @foreach($uniqueTimestampsVentilator as $timestamp)
                            <th class="time-header">
                                {{ \Carbon\Carbon::parse($timestamp)->format('H:i') }}<br>
                                <span class="author-header">{{ $mergedRecordsPerMinute[$timestamp]->inputters }}</span>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allParameters as $param)

                        @if ($param['group'] == 'VENTILATOR')
                        @php
                        $key = $param['key'];
                        $hasDataInFilteredColumns = false;
                        foreach($uniqueTimestampsVentilator as $timestamp) {
                        $record = $mergedRecordsPerMinute[$timestamp];
                        if ($record->{$key} !== null) { $hasDataInFilteredColumns = true; break; }
                        }
                        @endphp

                        @if($hasDataInFilteredColumns)
                        <tr>
                            <th class="param-label">{{ $param['label'] }}</th>
                            @foreach($uniqueTimestampsVentilator as $timestamp)
                            <td>
                                @php
                                $record = $mergedRecordsPerMinute[$timestamp];
                                $value = $record->{$key} ?? null;
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
                <div style="margin-top: 15px;"></div>
                @endif


                {{-- ==================== 3. Tabel TTV & OBSERVASI LAIN =================== --}}
                @if($uniqueTimestampsNonFluid->count() > 0)
                <h4 style="margin-top: 5px; margin-bottom: 5px; font-weight: bold;">TTV & OBSERVASI LAIN</h4>
                <table class="observation-table">
                    <thead>
                        <tr>
                            <th class="param-label">Parameter</th>
                            @foreach($uniqueTimestampsNonFluid as $timestamp)
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

                        {{-- HANYA TAMPILKAN GRUP RESPIRASI DAN OBSERVASI --}}
                        @if (in_array($param['group'], ['RESPIRASI', 'OBSERVASI']))

                        @if ($param['group'] != $currentGroup)
                        <tr>
                            <td colspan="{{ $uniqueTimestampsNonFluid->count() + 1 }}" class="group-label">{{ $param['group'] }}</td>
                        </tr>
                        @php $currentGroup = $param['group']; @endphp
                        @endif

                        @php
                        $key = $param['key'];
                        $hasDataInFilteredColumns = $mergedRecordsPerMinute->contains(function ($mergedRecord) use ($key) {
                        if ($key == 'gcs') { return $mergedRecord->gcs_e !== null || $mergedRecord->gcs_v !== null || $mergedRecord->gcs_m !== null; }
                        elseif ($key == 'pupil') { return $mergedRecord->pupil_left_size_mm !== null || $mergedRecord->pupil_right_size_mm !== null || $mergedRecord->pupil_left_reflex !== null || $mergedRecord->pupil_right_reflex !== null;}
                        else { return isset($mergedRecord->{$key}) && $mergedRecord->{$key} !== null; }
                        });
                        @endphp

                        @if($hasDataInFilteredColumns)
                        <tr>
                            <th class="param-label">{{ $param['label'] }}</th>
                            @foreach($uniqueTimestampsNonFluid as $timestamp)
                            <td>
                                @php
                                $record = $mergedRecordsPerMinute[$timestamp];
                                $value = null;

                                if ($key == 'gcs' && (isset($record->gcs_e) || isset($record->gcs_v) || isset($record->gcs_m))) {
                                $gcsTotal = (($record->gcs_e ?? 0) + ($record->gcs_v ?? 0) + ($record->gcs_m ?? 0));
                                $value = "E".($record->gcs_e ?? '-')."V".($record->gcs_v ?? '-')."M".($record->gcs_m ?? '-').($gcsTotal > 0 ? "($gcsTotal)" : '');
                                }
                                elseif ($key == 'pupil' && (isset($record->pupil_left_size_mm) || isset($record->pupil_right_size_mm))) {
                                $left = ($record->pupil_left_size_mm ?? '-') . '/' . ($record->pupil_left_reflex ?? '-');
                                $right = ($record->pupil_right_size_mm ?? '-') . '/' . ($record->pupil_right_reflex ?? '-');
                                $value = "{$left}|{$right}";
                                }
                                elseif (isset($record->{$key}) && $record->{$key} !== null) { $value = $record->{$key}; }
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
                <div style="margin-top: 15px;"></div>
                @endif


                {{-- ==================== 4. Tabel Fluid Balance (CAIRAN) =================== --}}
                @if($uniqueTimestampsFluid->count() > 0)
                <h4 style="margin-top: 5px; margin-bottom: 5px; font-weight: bold;">FLUID BALANCE</h4>
                <table class="observation-table">
                    <thead>
                        <tr>
                            <th class="param-label">Parameter</th>
                            @foreach($uniqueTimestampsFluid as $timestamp)
                            <th class="time-header">
                                {{ \Carbon\Carbon::parse($timestamp)->format('H:i') }}<br>
                                <span class="author-header">{{ $mergedRecordsPerMinute[$timestamp]->inputters }}</span>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        {{-- CAIRAN MASUK --}}
                        <tr>
                            <td colspan="{{ $uniqueTimestampsFluid->count() + 1 }}" class="group-label">CAIRAN MASUK</td>
                        </tr>
                        {{-- Filter Parenteral --}}
                        @forelse ($uniqueParenteralFluids as $fluidName)
                        <tr>
                            <th class="param-label" style="padding-left: 15px;">{{ $fluidName }} (IV)</th>
                            @foreach($uniqueTimestampsFluid as $timestamp)
                            <td>
                                @php
                                $vol = collect($mergedRecordsPerMinute[$timestamp]->fluids_in)->where('is_parenteral', true)->where('jenis', $fluidName)->sum('volume');
                                @endphp
                                @if($vol > 0) <span class="font-semibold text-green-700">{{ $vol }}</span> @endif
                            </td>
                            @endforeach
                        </tr>
                        @empty @endforelse
                        {{-- Filter Enteral --}}
                        @forelse ($uniqueEnteralFluids as $fluidName)
                        <tr>
                            <th class="param-label" style="padding-left: 15px;">{{ $fluidName }} (Oral/NGT)</th>
                            @foreach($uniqueTimestampsFluid as $timestamp)
                            <td>
                                @php
                                $vol = collect($mergedRecordsPerMinute[$timestamp]->fluids_in)->where('is_enteral', true)->where('jenis', $fluidName)->sum('volume');
                                @endphp
                                @if($vol > 0) <span class="font-semibold text-green-700">{{ $vol }}</span> @endif
                            </td>
                            @endforeach
                        </tr>
                        @empty @endforelse

                        {{-- TOTAL CAIRAN MASUK --}}
                        <tr style="background-color: #f0f8ff;">
                            <th class="param-label">TOTAL Cairan Masuk</th>
                            @foreach($uniqueTimestampsFluid as $timestamp)
                            <td>
                                @php $totalIn = collect($mergedRecordsPerMinute[$timestamp]->fluids_in)->sum('volume'); @endphp
                                @if($totalIn > 0) <span class="font-bold text-green-700">{{ $totalIn }}</span> @endif
                            </td>
                            @endforeach
                        </tr>

                        {{-- CAIRAN KELUAR --}}
                        <tr>
                            <td colspan="{{ $uniqueTimestampsFluid->count() + 1 }}" class="group-label">CAIRAN KELUAR</td>
                        </tr>
                        @php $keluarTypes = ['Irigasi CM', 'Irigasi CK', 'Urine', 'NGT', 'Drain/WSD 1', 'Drain/WSD 2', 'Lain-lain']; @endphp
                        @foreach($keluarTypes as $type)
                        @php
                        // Filter Cairan Keluar: Hanya tampil jika total volume > 0 di SELURUH cycle
                        $totalKeluarForType = $allRecords
                        ->where('cairan_keluar_jenis', $type)
                        ->whereNotNull('cairan_keluar_volume')
                        ->sum('cairan_keluar_volume');
                        @endphp

                        @if($totalKeluarForType > 0)
                        <tr>
                            <th class="param-label">{{ $type }}</th>
                            @foreach($uniqueTimestampsFluid as $timestamp)
                            <td>
                                @php $vol = collect($mergedRecordsPerMinute[$timestamp]->fluids_out)->where('jenis', $type)->sum('volume'); @endphp
                                @if($vol > 0) <span class="font-semibold text-red-700">{{ $vol }}</span> @endif
                            </td>
                            @endforeach
                        </tr>
                        @endif
                        @endforeach

                        {{-- TOTAL CAIRAN KELUAR --}}
                        <tr style="background-color: #fff0f5;">
                            <th class="param-label">TOTAL Cairan Keluar</th>
                            @foreach($uniqueTimestampsFluid as $timestamp)
                            <td>
                                @php $totalOut = collect($mergedRecordsPerMinute[$timestamp]->fluids_out)->sum('volume'); @endphp
                                @if($totalOut > 0) <span class="font-bold text-red-700">{{ $totalOut }}</span> @endif
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
                <div style="margin-top: 15px;"></div>
                @endif


                {{-- ==================== 5. Tabel CATATAN / TINDAKAN (NOTES & MEDS) =================== --}}
                @if($uniqueTimestampsNotesAndMeds->count() > 0)
                <h4 style="margin-top: 5px; margin-bottom: 5px; font-weight: bold;">CATATAN KLINIS & TINDAKAN</h4>
                <table class="observation-table">
                    <thead>
                        <tr>
                            <th class="param-label">Parameter</th>
                            @foreach($uniqueTimestampsNotesAndMeds as $timestamp)
                            <th class="time-header">
                                {{ \Carbon\Carbon::parse($timestamp)->format('H:i') }}<br>
                                <span class="author-header">{{ $mergedRecordsPerMinute[$timestamp]->inputters }}</span>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allParameters as $param)

                        @if ($param['group'] == 'CATATAN')
                        @php
                        $key = $param['key'];
                        $hasDataInFilteredColumns = false;
                        foreach($uniqueTimestampsNotesAndMeds as $timestamp) {
                        $record = $mergedRecordsPerMinute[$timestamp];
                        if (!empty($record->{$key})) {
                        $hasDataInFilteredColumns = true;
                        break;
                        }
                        }
                        @endphp

                        @if($hasDataInFilteredColumns)
                        <tr>
                            <th class="param-label">{{ $param['label'] }}</th>
                            @foreach($uniqueTimestampsNotesAndMeds as $timestamp)
                            <td>
                                @php
                                $record = $mergedRecordsPerMinute[$timestamp];
                                $value = !empty($record->{$key}) ? $record->{$key} : null;
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
                @endif

            </td>
            {{-- ==================== AKHIR KOLOM KANAN =================== --}}
        </tr>
    </table>

</body>

</html>