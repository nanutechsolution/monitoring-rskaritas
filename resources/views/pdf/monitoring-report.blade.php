<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Monitoring {{ $title }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 7px;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
        }

        th,
        td {
            border: 1px solid #999;
            /* PERKECIL PADDING SEL */
            padding: 1px 2px;
            /* Padding atas/bawah 1px, kiri/kanan 2px */
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .header-table {
            margin-bottom: 5px;
            /* Mengurangi margin */
        }

        .header-table td {
            border: none;
            padding: 1px 0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .no-border,
        .no-border td {
            border: none;
        }

        .w-50 {
            width: 50%;
        }

        h2 {
            text-align: center;
            margin: 0 0 5px 0;
        }

        h3 {
            text-align: center;
            margin: 5px 0 5px 0;
            /* MENGURANGI MARGIN */
        }

        .page-break {
            page-break-after: always;
        }

        .nutrition-lab-table {
            width: 100%;
            /* Lebar penuh */
            border-collapse: collapse;
            /* Hapus spasi antar border */
            border: 1px solid black;
            /* Border luar */
            font-size: 8px;
            /* Sesuaikan ukuran font */
            margin-bottom: 5px;
            /* Jarak bawah */
        }

        .nutrition-lab-table th,
        .nutrition-lab-table td {
            border: 1px solid black;
            /* Border dalam */
            padding: 3px;
            /* Sedikit padding */
            vertical-align: top;
            /* Rata atas */
        }

        .nutrition-lab-table th {
            font-weight: bold;
            text-align: center;
            background-color: #f2f2f2;
            /* Warna header */
        }

        /* Atur tinggi area konten */
        .nutrition-content-cell {
            height: 80px;
            /* Sesuaikan tinggi sesuai kebutuhan */
        }

        .lab-content-cell {
            height: 60px;
            /* Sesuaikan tinggi sesuai kebutuhan */
        }

    </style>
</head>
<body>
    <table width="100%" style="margin-bottom: 5px;">
        <tr>
            <td style="width: 15%; text-align: center;">
                @if(!empty($setting->logo))
                <img src="data:image/png;base64,{{ base64_encode($setting->logo) }}" style="height: 60px;">
                @endif
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <div style="font-size: 14px; font-weight: bold;">{{ $setting->nama_instansi ?? 'Nama Instansi' }}</div>
                <div style="font-size: 10px;">{{ $setting->alamat_instansi ?? '-' }}</div>
                <div style="font-size: 10px;">
                    {{ $setting->kabupaten ?? '' }}, {{ $setting->propinsi ?? '' }}
                </div>
                <div style="font-size: 9px;">Telp: {{ $setting->kontak ?? '-' }} | Email: {{ $setting->email ?? '-' }}</div>
            </td>
        </tr>
    </table>
    <hr style="margin: 4px 0;">
    {{-- HEADER COMPACT A3 LANDSCAPE 3-COLUMN --}}
    <table style="width:100%; font-size:10px; border-collapse:collapse;">
        <tr>
            {{-- KIRI: Highlight --}}
            <td style="width:30%; vertical-align:top;">
                <table style="width:100%; font-size:10px; border-collapse:collapse;">
                    <tr>
                        <td style="background:#f0f0f0; padding:4px; font-weight:bold;">Nama Bayi</td>
                        <td style="padding:4px; font-weight:bold;">{{ $patient->nm_pasien ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="background:#f0f0f0; padding:4px; font-weight:bold;">Jenis Kelamin</td>
                        <td style="padding:4px;">{{ $patient->jk === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <td style="background:#f0f0f0; padding:4px; font-weight:bold;">Umur Bayi</td>
                        <td style="padding:4px;">{{ $umur_bayi ?? 'N/A' }} hari</td>
                    </tr>
                    <tr>
                        <td style="background:#f0f0f0; padding:4px; font-weight:bold;">Tanggal</td>
                        <td style="padding:4px; font-weight:bold;">{{ $cycle->start_time ? \Carbon\Carbon::parse($cycle->start_time)->isoFormat('dddd, D MMMM YYYY') : 'N/A' }}</td>
                    </tr>
                </table>
            </td>

            {{-- KANAN: Info Tambahan --}}
            <td style="width:70%; vertical-align:top;">
                <table style="width:100%; font-size:10px; border-collapse:collapse;">
                    {{-- BARIS 1 --}}
                    <tr>
                        <td style="background:#f0f0f0; padding:4px; font-weight:bold;">Tanggal Lahir</td>
                        <td style="padding:4px;">{{ $patient->tgl_lahir ? \Carbon\Carbon::parse($patient->tgl_lahir)->isoFormat('D MMM YYYY') : 'N/A' }}</td>

                        <td style="background:#f0f0f0; padding:4px; font-weight:bold;">Hari Rawat Ke</td>
                        <td style="padding:4px;">{{ $hari_rawat_ke ?? 'N/A' }}</td>

                        <td style="background:#f0f0f0; padding:4px; font-weight:bold;">No RM</td>
                        <td style="padding:4px;">{{ $patient->no_rkm_medis ?? 'N/A' }}</td>

                        <td style="background:#f0f0f0; padding:4px; font-weight:bold;">Berat Lahir</td>
                        <td style="padding:4px;">{{ $patient->berat_lahir ?? 'N/A' }} {{ $patient->berat_lahir ? 'gram' : ''}}</td>
                    </tr>

                    {{-- BARIS 2 --}}
                    <tr>
                        <td style="background:#f0f0f0; padding:4px; font-weight:bold;">Umur Kehamilan</td>
                        <td style="padding:4px;">{{ $patient->umur_kehamilan ?? 'N/A' }} minggu</td>

                        <td style="background:#f0f0f0; padding:4px; font-weight:bold;">Diagnosis</td>
                        <td style="padding:4px;" colspan="5">{{ $patient->diagnosa_awal ?? 'N/A' }}</td>
                    </tr>

                    {{-- BARIS 3 --}}
                    <tr>
                        <td style="background:#f0f0f0; padding:4px; font-weight:bold;">Umur Koreksi</td>
                        <td style="padding:4px;">N/A</td>

                        <td style="background:#f0f0f0; padding:4px; font-weight:bold;">Asal Ruangan</td>
                        <td style="padding:4px;">{{ $patient->asal_bangsal ?? 'N/A' }}</td>

                        <td style="background:#f0f0f0; padding:4px; font-weight:bold;">Dokter DPJP</td>
                        <td style="padding:4px;" colspan="3">{{ $patient->nm_dokter ?? 'N/A' }}</td>
                    </tr>

                    {{-- BARIS 4 --}}
                    <tr>
                        <td style="background:#f0f0f0; padding:4px; font-weight:bold;">Non Rujukan</td>
                        <td style="padding:4px;">{{ $patient->non_rujukan ?? 'N/A' }}</td>

                        <td style="background:#f0f0f0; padding:4px; font-weight:bold;">Jaminan</td>
                        <td style="padding:4px;">{{ $patient->jaminan ?? 'N/A' }}</td>

                        <td style="background:#f0f0f0; padding:4px; font-weight:bold;">Cara Persalinan</td>
                        <td style="padding:4px;">{{ $patient->cara_persalinan ?? 'N/A' }}</td>

                        <td style="background:#f0f0f0; padding:4px; font-weight:bold;">Rujukan</td>
                        <td style="padding:4px;">{{ $patient->rujukan ?? 'N/A' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <h2>{{ $title }}</h2>
    <hr>
    {{-- HEADER COMPACT A3 LANDSCAPE 3-COLUMN --}}

    <table class="border" style="width: 100%; table-layout: fixed; border-spacing: 0;">
        <tr>
            <td style="width: 33.3%; vertical-align: top; padding: 0 1px;">
                <h4 style="margin: 0; font-size: 8px; font-weight: bold; text-align: center; line-height: 1;">
                    Tanda Vital
                </h4>
                @if($chartVitalsBase64)
                <img src="{{ $chartVitalsBase64 }}" style="width: 100%; max-height: 80px; object-fit: contain;">
                @endif
            </td>
            <td style="width: 33.3%; vertical-align: top; padding: 0 1px;">
                <h4 style="margin: 0; font-size: 8px; font-weight: bold; text-align: center; line-height: 1;">
                    Suhu
                </h4>
                @if($chartTempBase64)
                <img src="{{ $chartTempBase64 }}" style="width: 100%; max-height: 80px; object-fit: contain;">
                @endif
            </td>
            <td style="width: 33.3%; vertical-align: top; padding: 0 1px;">
                <h4 style="margin: 0; font-size: 8px; font-weight: bold; text-align: center; line-height: 1;">
                    Tekanan Darah
                </h4>
                @if($chartBpBase64)
                <img src="{{ $chartBpBase64 }}" style="width: 100%; max-height: 80px; object-fit: contain;">
                @endif
            </td>
        </tr>
    </table>

    <table style="width: 100%; table-layout: fixed; border-collapse: collapse;">
        <tr>
            <!-- Kolom Kiri: Masalah Klinis + Program Terapi -->
            <td style="width: 20%; vertical-align: top; padding: 4px; border: 1px solid #ccc; background: #f9fafb;">
                <!-- Masalah Klinis Aktif -->
                <strong style="display: block; background: #e9ecef; padding: 3px 4px; border-radius: 4px; margin-bottom: 4px; font-size: 9px; text-align: center;">
                    Masalah Klinis Aktif
                </strong>
                <div style="margin-top: 4px; font-size: 8px; line-height: 1.1;">
                    {!! nl2br(e($therapySections['masalahOnly'])) !!}
                </div>

                <!-- Garis pembatas halus -->
                <hr style="border: 0; border-top: 1px dashed #ccc; margin: 6px 0;">

                <!-- Program Terapi -->
                <strong style="display: block; background: #e9ecef; padding: 3px 4px; border-radius: 4px; margin-bottom: 4px; font-size: 9px; text-align: center;">
                    Program Terapi
                </strong>
                <div style="margin-top: 4px; font-size: 8px; line-height: 1.1;">
                    {!! nl2br(e($therapySections['programOnly'])) !!}
                </div>
            </td>

            <!-- Kolom Kanan: Hemodinamik + Observasi Apnea -->
            <td style="width: 65%; vertical-align: top; padding-left: 6px;">
                <h3 style="background-color: #dee2e6; padding: 4px; border-radius: 4px; font-size: 10px; margin-bottom: 2px;">
                    Masalah Klinis & Data Hemodinamik / Apnea
                </h3>
                @php
                // Ambil semua jam yang memang punya nilai di salah satu parameter hemodinamik
                $activeHemoHours = collect($reportHours)->filter(function ($hour) use ($hemoMatrix) {
                foreach ($hemoMatrix as $paramKey => $values) {
                if (!empty($values[$hour])) {
                return true;
                }
                }
                return false;
                })->values();
                @endphp

                <!-- Tabel Hemodinamik -->
                <table style="width: 100%; table-layout: fixed; font-size: 8px; border-collapse: collapse;" border="1">
                    <thead>
                        <tr>
                            <th style="width: 110px;" rowspan="2">Parameter</th>
                            <th class="text-center" colspan="{{ count($activeHemoHours) }}">JAM / TIME</th>
                        </tr>
                        <tr>
                            @foreach($activeHemoHours as $hour)
                            <th style="width: 26px;" class="text-center">{{ $hour }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hemoParameters as $paramKey => $paramName)
                        <tr>
                            <td style="font-weight: bold; padding: 2px 4px;">{{ $paramName }}</td>
                            @foreach($activeHemoHours as $hour)
                            <td class="text-center" style="padding: 2px 2px;">
                                @if(!empty($hemoMatrix[$paramKey][$hour]))
                                {{ $hemoMatrix[$paramKey][$hour] }}
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Tabel Observasi Apnea Warna -->
                @if($activeObservationHours->isNotEmpty())
                <table style="width: 100%; table-layout: fixed; font-size: 8px; margin-top: 4px; border-collapse: collapse;" border="1">
                    <thead>
                        <tr>
                            <th style="width: 110px; text-align: left;">Parameter</th>
                            @foreach($activeObservationHours as $hour)
                            <th style="width: 26px;" class="text-center">{{ $hour }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($observationParameters as $paramKey => $paramName)
                        @if(count(array_filter($observationMatrix[$paramKey])) > 0)
                        <tr>
                            <td style="font-weight: bold; padding: 2px 4px;">{{ $paramName }}</td>
                            @foreach($activeObservationHours as $hour)
                            <td class="text-center" style="padding: 2px 2px;">
                                @if(!empty($observationMatrix[$paramKey][$hour]))
                                {{ $observationMatrix[$paramKey][$hour] }}
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
                @endif

            </td>
        </tr>
    </table>

    <table style="width: 100%; table-layout: fixed; border-collapse: collapse; margin-top: 6px;">
        <tr>
            <!-- Kolom Kiri: NUTRISI + PEMERIKSAAN LABORATORIUM -->
            <td style="width: 20%; vertical-align: top; padding: 4px; border: 1px solid #ccc; background: #f9fafb;">
                <!-- Bagian NUTRISI -->
                <strong style="display: block; background: #e9ecef; padding: 3px 4px; border-radius: 4px; margin-bottom: 4px; font-size: 9px; text-align: center;">
                    NUTRISI
                </strong>
                <table style="width: 100%; font-size: 8px; border-collapse: collapse;" border="1">
                    <thead>
                        <tr style="background-color: #f8f9fa;">
                            <th style="width: 50%; text-align: center;">ENTERAL</th>
                            <th style="width: 50%; text-align: center;">PARENTERAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="vertical-align: top; padding: 2px;">
                                {!! nl2br(e($therapySections['nutrisiEnteralOnly'])) !!}
                            </td>
                            <td style="vertical-align: top; padding: 2px;">
                                {!! nl2br(e($therapySections['nutrisiParenteralOnly'])) !!}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr style="border: 0; border-top: 1px dashed #ccc; margin: 6px 0;">

                <!-- Bagian PEMERIKSAAN LABORATORIUM -->
                <strong style="display: block; background: #e9ecef; padding: 3px 4px; border-radius: 4px; margin-bottom: 4px; font-size: 9px; text-align:center">
                    PEMERIKSAAN PENUNJANG
                </strong>
                <div style="font-size: 8px; line-height: 1.1;">
                    {!! nl2br(e($therapySections['pemeriksaanLabOnly'])) !!}
                </div>
            </td>

            <!-- Kolom Kanan: GAS DARAH + TERAPI OKSIGEN / VENTILATOR -->
            <td style="width: 65%; vertical-align: top; padding-left: 6px;">
                <!-- BAGIAN GAS DARAH -->
                @if($bloodGasHours->isNotEmpty())
                <h3 style="text-align: center; background-color: #dee2e6; padding: 4px; border-radius: 4px; font-size: 10px; margin-bottom: 4px;">
                    Hasil Gas Darah
                </h3>
                <table border="1" cellspacing="0" cellpadding="4" style="width: 100%; font-size: 9px; border-collapse: collapse; margin-bottom: 6px;">
                    <thead>
                        <tr style="background-color: #f8f9fa;">
                            <th style="width: 120px; text-align: left;">Parameter</th>
                            @foreach ($bloodGasHours as $hour)
                            <th style="text-align: center;">{{ $hour }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bloodGasParameters as $param => $label)
                        <tr>
                            <td style="font-weight: bold; padding: 2px 4px;">{{ $label }}</td>
                            @foreach ($bloodGasHours as $hour)
                            <td style="text-align: center;">{{ $bloodGasMatrix[$param][$hour] ?? '-' }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                <!-- BAGIAN TERAPI OKSIGEN / VENTILATOR -->
                @if($activeVentilatorHours->isNotEmpty())
                <h3 style="background-color: #dee2e6; padding: 4px; border-radius: 4px; font-size: 10px; margin-bottom: 2px;">
                    Terapi Oksigen / Ventilator
                </h3>

                @foreach($ventilatorParams as $group => $params)
                @php
                $hasData = false;
                foreach ($params as $paramKey => $label) {
                foreach ($activeVentilatorHours as $hour) {
                if (!empty($ventilatorMatrix[$paramKey][$hour])) {
                $hasData = true;
                break 2;
                }
                }
                }
                @endphp

                @if($hasData)
                <h3 style="background-color: #e9ecef; padding: 3px 6px; border-radius: 4px; font-size: 9px; margin-top: 4px;">
                    {{ $group }}
                </h3>

                <table style="width: 100%; table-layout: fixed; font-size: 8px; margin-top: 2px; border-collapse: collapse;" border="1">
                    <thead>
                        <tr style="background-color: #f8f9fa;">
                            <th style="width: 120px; text-align: left; padding: 2px 4px;">PARAMETER</th>
                            @foreach($activeVentilatorHours as $hour)
                            <th style="width: 26px; text-align: center;">{{ $hour }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($params as $paramKey => $paramLabel)
                        <tr>
                            <td style="font-weight: bold; padding: 2px 4px;">{{ $paramLabel }}</td>
                            @foreach($activeVentilatorHours as $hour)
                            <td style="text-align: center;">{{ $ventilatorMatrix[$paramKey][$hour] ?? '' }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                @endforeach
                @endif
            </td>
        </tr>
    </table>
    @php
    $filteredRecords = $records->filter(function ($record) {
    return
    $record->parenteralIntakes->isNotEmpty() ||
    $record->enteralIntakes->isNotEmpty() ||
    $record->intake_ogt > 0 ||
    $record->intake_oral > 0 ||
    $record->output_ngt > 0 ||
    $record->output_urine > 0 ||
    $record->output_bab > 0 ||
    $record->output_drain > 0;
    });
    @endphp
    <table style="width:100%; table-layout:fixed; font-family:Arial, sans-serif; border-collapse:collapse; margin-top:5px;">
        <tr>
            <!-- Kolom Kiri -->
            <td style="width:25%; vertical-align:top; padding-right:6px;">
                {{-- Balance Cairan 24 Jam --}}
                @if(isset($cycle) && $filteredRecords->isNotEmpty())
                <div style="background:#d0e0ff; border-radius:8px; padding:6px; margin-bottom:6px; font-size:8px;">
                    <strong>Balance Cairan 24 Jam</strong>
                    <table style="width:100%; border-collapse:collapse; margin-top:4px; font-size:8px;">
                        @foreach([
                        ['Masuk', number_format($filteredRecords->sum(fn($r)=>$r->totalCairanMasuk()),2),'ml'],
                        ['Keluar', number_format($filteredRecords->sum(fn($r)=>$r->totalCairanKeluar()),2),'ml'],
                        ['Produksi urine', number_format($filteredRecords->sum('output_urine'),2),'ml'],
                        ['EWL', number_format($cycle->daily_iwl ?? 0,2),'ml'],
                        ['BC 24 Jam', number_format($cycle->calculated_balance_24h ?? 0,2),'ml'],
                        ['BC 24 Jam sebelumnya', number_format($previousCycle->calculated_balance_24h ?? 0,2),'ml'],
                        ] as $row)
                        <tr>
                            <td style="padding:3px 4px;">{{ $row[0] }}</td>
                            <td style="text-align:right; padding:3px 4px;">{{ $row[1] }}</td>
                            <td style="text-align:center; padding:3px 4px;">{{ $row[2] }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                @endif
                {{-- Alat Terpasang --}}
                <div style="background:#f0f4ff; border-radius:8px; padding:6px; font-size:8px;">
                    <strong>Alat Terpasang</strong>
                    @if($patientDevices->isNotEmpty())
                    <ul style="padding-left:15px; margin-top:4px;">
                        @foreach($patientDevices as $device)
                        <li>
                            {{ $device->device_name }}
                            @if($device->size) (Size: {{ $device->size }}) @endif
                            @if($device->location) — {{ $device->location }} @endif
                            @if($device->installation_date)
                            <span style="color: gray;">({{ \Carbon\Carbon::parse($device->installation_date)->format('d M Y') }})</span>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p style="color:#666; margin-top:4px;">Belum ada alat terpasang.</p>
                    @endif
                </div>
            </td>

            <!-- Kolom Kanan -->
            <td style="width:80%; vertical-align:top; padding-left:6px;">
                {{-- Keseimbangan Cairan per Jam --}}
                @if($filteredRecords->isNotEmpty())
                <div style="background:#f0f4ff; border-radius:8px; padding:6px; margin-bottom:6px; font-size:8px;">
                    <h3 style="text-align: center; background-color: #dee2e6; padding: 4px; border-radius: 4px; font-size: 10px; margin-bottom: 4px;">
                        Keseimbangan Cairan per Jam
                    </h3>
                    <table style="width:100%; border-collapse:collapse; font-size:8px; margin-top:4px;">
                        <thead>
                            <tr style="background:#e0e7ff;">
                                <th style="border:1px solid #ccc; padding:2px 4px; text-align:left; width:100px;">Jenis Cairan</th>
                                @foreach($filteredRecords as $record)
                                <th style="border:1px solid #ccc; padding:2px; text-align:center;">{{ date('H:i', strtotime($record->record_time)) }}</th>
                                @endforeach
                                <th style="border:1px solid #ccc; padding:2px; text-align:center; background:#f9fafb;">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tbody>
                            {{-- INTAKE --}}
                            <tr style="background-color:#f9fafb; font-weight:bold;">
                                <td colspan="{{ count($filteredRecords) + 2 }}" style="padding:3px;">Parental</td>
                            </tr>
                            {{-- PARENTERAL --}}
                            @php
                            $allInfusNames = collect();
                            foreach ($filteredRecords as $record) {
                            $allInfusNames = $allInfusNames->merge($record->parenteralIntakes->pluck('name'));
                            }
                            $uniqueInfusNames = $allInfusNames->unique()->values();
                            @endphp
                            @foreach ($uniqueInfusNames as $infusName)
                            <tr>
                                <td style="border:1px solid #ccc; padding:2px 4px; font-style:italic;">{{ $infusName }}</td>
                                @foreach ($filteredRecords as $record)
                                @php
                                $infus = $record->parenteralIntakes->firstWhere('name', $infusName);
                                @endphp
                                <td style="border:1px solid #ccc; text-align:center;">
                                    {{ $infus ? $infus->volume : '' }}
                                </td>
                                @endforeach
                                <td style="border:1px solid #ccc; text-align:center; font-weight:bold; background:#f9fafb;">
                                    {{ $filteredRecords->sum(fn($r) => ($r->parenteralIntakes->firstWhere('name', $infusName)?->volume ?? 0)) }}
                                </td>
                            </tr>
                            @endforeach
                            <tr style="background-color:#f9fafb; font-weight:bold;">
                                <td colspan="{{ count($filteredRecords) + 2 }}" style="padding:3px;">Enteral</td>
                            </tr>

                            @php
                            $allInfusNames = collect();
                            foreach ($filteredRecords as $record) {
                            $allInfusNames = $allInfusNames->merge($record->enteralIntakes->pluck('name'));
                            }
                            $uniqueInfusNames = $allInfusNames->unique()->values();
                            @endphp
                            @foreach ($uniqueInfusNames as $infusName)
                            <tr>
                                <td style="border:1px solid #ccc; padding:2px 4px; font-style:italic;">{{ $infusName }}</td>
                                @foreach ($filteredRecords as $record)
                                @php
                                $infus = $record->enteralIntakes->firstWhere('name', $infusName);
                                @endphp
                                <td style="border:1px solid #ccc; text-align:center;">
                                    {{ $infus ? $infus->volume : '' }}
                                </td>
                                @endforeach
                                <td style="border:1px solid #ccc; text-align:center; font-weight:bold; background:#f9fafb;">
                                    {{ $filteredRecords->sum(fn($r) => ($r->enteralIntakes->firstWhere('name', $infusName)?->volume ?? 0)) }}
                                </td>
                            </tr>
                            @endforeach
                            {{-- OGT --}}
                            <tr>
                                <td style="border:1px solid #ccc;">OGT</td>
                                @foreach ($filteredRecords as $record)
                                <td style="border:1px solid #ccc; text-align:center;">{{ $record->intake_ogt ?: '' }}</td>
                                @endforeach
                                <td style="border:1px solid #ccc; text-align:center; font-weight:bold; background:#f9fafb;">
                                    {{ $filteredRecords->sum('intake_ogt') }}
                                </td>
                            </tr>

                            {{-- Oral --}}
                            <tr>
                                <td style="border:1px solid #ccc;">Oral</td>
                                @foreach ($filteredRecords as $record)
                                <td style="border:1px solid #ccc; text-align:center;">{{ $record->intake_oral ?: '' }}</td>
                                @endforeach
                                <td style="border:1px solid #ccc; text-align:center; font-weight:bold; background:#f9fafb;">
                                    {{ $filteredRecords->sum('intake_oral') }}
                                </td>
                            </tr>
                            {{-- TOTAL CM --}}
                            <tr style="background:#f3f4f6; font-weight:bold;">
                                <td style="border:1px solid #ccc;">TOTAL CM</td>
                                @foreach ($filteredRecords as $record)
                                <td style="border:1px solid #ccc; text-align:center;">{{ $record->totalCairanMasuk() }}</td>
                                @endforeach
                                <td style="border:1px solid #ccc; text-align:center;">{{ $filteredRecords->sum(fn($r) => $r->totalCairanMasuk()) }}</td>
                            </tr>

                            {{-- OUTPUT --}}
                            <tr style="background:#f9fafb; font-weight:bold;">
                                <td colspan="{{ count($filteredRecords) + 2 }}" style="padding:3px;">OUTPUT (CAIRAN KELUAR)</td>
                            </tr>

                            @foreach (['output_ngt' => 'NGT','output_urine' => 'Urine','output_bab' => 'BAB','output_drain' => 'Drain'] as $field => $label)
                            <tr>
                                <td style="border:1px solid #ccc;">{{ $label }}</td>
                                @foreach ($filteredRecords as $record)
                                <td style="border:1px solid #ccc; text-align:center;">{{ $record->$field ?: '' }}</td>
                                @endforeach
                                <td style="border:1px solid #ccc; text-align:center; font-weight:bold; background:#f9fafb;">
                                    {{ $filteredRecords->sum($field) }}
                                </td>
                            </tr>
                            @endforeach

                            {{-- TOTAL CK --}}
                            <tr style="background:#f3f4f6; font-weight:bold;">
                                <td style="border:1px solid #ccc;">TOTAL CK</td>
                                @foreach ($filteredRecords as $record)
                                <td style="border:1px solid #ccc; text-align:center;">{{ $record->totalCairanKeluar() }}</td>
                                @endforeach
                                <td style="border:1px solid #ccc; text-align:center;">
                                    {{ $filteredRecords->sum(fn($r) => $r->totalCairanKeluar()) }}
                                </td>
                            </tr>

                            {{-- BALANCE --}}
                            <tr style="font-weight:bold; color:#111;">
                                <td style="border:1px solid #ccc; background:#e0e7ff;">BALANCE</td>
                                @foreach ($filteredRecords as $record)
                                @php
                                $balance = $record->totalCairanMasuk() - $record->totalCairanKeluar();
                                $color = $balance < 0 ? '#fecaca' : '#e0e7ff' ; @endphp <td style="border:1px solid #ccc; text-align:center; background:{{ $color }};">{{ $balance }}
            </td>
            @endforeach
            @php
            $totalBalance = $filteredRecords->sum(fn($r) => $r->totalCairanMasuk() - $r->totalCairanKeluar());
            $totalColor = $totalBalance < 0 ? '#fecaca' : '#e0e7ff' ; @endphp <td style="border:1px solid #ccc; text-align:center; background:{{ $totalColor }};">{{ $totalBalance }}</td>
        </tr>
        </tbody>
    </table>
    </div>
    @endif
    @php
    // Ambil jam unik dari data obat
    $jamList = $medications->pluck('given_at')
    ->map(fn($t) => \Carbon\Carbon::parse($t)->format('H:i'))
    ->unique()
    ->sort()
    ->values();
    // Ambil nama obat unik
    $medNames = $medications->pluck('medication_name')->unique()->values();

    // Matriks data: [nama_obat][jam] = dosis/rute
    $matrix = [];
    foreach($medications as $med){
    $jam = \Carbon\Carbon::parse($med->given_at)->format('H:i');
    $matrix[$med->medication_name][$jam] = $med->dose . ' / ' . $med->route;
    }
    // Batas dosis untuk highlight (mg)
    $highlightThreshold = 500;
    @endphp

    {{-- Obat-Obatan --}}
    @if($medications->isNotEmpty())
    <div style="background:#f0f4ff; border-radius:8px; padding:6px; font-size:8px;">
        <h3 style="text-align: center; background-color: #dee2e6; padding: 4px; border-radius: 4px; font-size: 10px; margin-bottom: 4px;">
            Obat-Obatan
        </h3>
        <table style="width:100%; border-collapse:collapse; font-size:8px; margin-top:4px;">
            <thead>
                <tr style="background:#e0e7ff;">
                    <th style="border:1px solid #ccc; padding:2px 4px; text-align:left;">Obat</th>
                    @foreach($jamList as $jam)
                    <th style="border:1px solid #ccc; padding:2px 4px; text-align:center;">{{ $jam }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($medNames as $medName)
                <tr>
                    <td style="border:1px solid #ccc; padding:2px 4px; font-weight:bold;">{{ $medName }}</td>
                    @foreach($jamList as $jam)
                    @php
                    $cell = $matrix[$medName][$jam] ?? '';
                    $highlight = '';
                    if($cell !== '' && preg_match('/\d+/', $cell, $m) && intval($m[0])>500) $highlight='color:red;font-weight:bold;';
                    @endphp
                    <td style="border:1px solid #ccc; text-align:center; {{ $highlight }}">{{ $cell }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    </td>
    </tr>
    </table>



</body>
</html>
