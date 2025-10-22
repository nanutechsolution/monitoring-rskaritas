<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CPPT / SOAP Pasien</title>
    <style>
        /* ======= GAYA UTAMA ======= */
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 10px;
            color: #1a1a1a;
            margin: 10px;
        }

        /* ======= [BARU] HEADER PASIEN ======= */
        .patient-header {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            /* Sedikit lebih besar dari body */
            margin-bottom: 10px;
            border: 1px solid #4a4a4a;
            /* Samakan dengan border tabel utama */
        }

        .patient-header td {
            border: 1px solid #555;
            padding: 5px 6px;
            vertical-align: top;
        }

        .patient-header .label {
            background-color: #f5f7fa;
            /* Mirip 'even' row */
            font-weight: bold;
            width: 15%;
        }

        .patient-header .value {
            width: 35%;
            font-weight: bold;
        }

        /* ================================== */

        .cppt-title {
            font-size: 14px;
            text-align: center;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #1a1a1a;
            margin-bottom: 12px;
            border-bottom: 2px solid #4a5568;
            padding-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-top: 8px;
            border: 1px solid #4a4a4a;
        }

        thead th {
            background: linear-gradient(to bottom, #f8f9fa, #e9ecef);
            border: 1px solid #4a4a4a;
            padding: 6px 5px;
            text-align: center;
            vertical-align: middle;
            font-weight: 700;
            color: #222;
        }

        tbody td {
            border: 1px solid #555;
            padding: 5px 6px;
            vertical-align: top;
            text-align: left;
            line-height: 1.4;
        }

        /* ======= KOLOM ======= */
        .col-tgl {
            width: 8%;
            text-align: center;
        }

        .col-profesi {
            width: 12%;
            text-align: center;
            font-weight: 600;
        }

        .col-soap {
            width: 35%;
        }

        .col-instruksi {
            width: 15%;
        }

        .col-petugas {
            width: 20%;
            text-align: center;
        }

        /* ======= SOAP LABEL ======= */
        .soap-label {
            color: #2c5282;
            font-weight: bold;
        }

        /* ======= GAYA BARIS ======= */
        tbody tr:nth-child(odd) {
            background-color: #fdfdfd;
        }

        tbody tr:nth-child(even) {
            background-color: #f5f7fa;
        }

        /* ======= AREA TTD ======= */
        .privy-box {
            display: block;
            border: 1px dashed #999;
            border-radius: 4px;
            height: 60px;
            /* semula 40px → diperbesar */
            margin-top: 4px;
            font-size: 8px;
            color: #666;
            text-align: center;
            padding-top: 15px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #777;
        }

        /* ======= PAGE BREAK UNTUK KONTROL PDF ======= */
        @media print {
            .cppt-table {
                page-break-inside: auto;
            }

            .cppt-table tr {
                page-break-inside: avoid;
            }
        }

    </style>
</head>

<body>
    <table class="patient-header">
        <tr>
            <td class="label">Nama Pasien</td>
            <td class="value">{{ $patient->nm_pasien ?? 'N/A' }}</td>
            <td class="label">No. Rekam Medis</td>
            <td class="value">{{ $patient->no_rkm_medis ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Lahir</td>
            <td class="value">{{ $patient->tgl_lahir ? \Carbon\Carbon::parse($patient->tgl_lahir)->isoFormat('D MMM YYYY') : 'N/A' }}</td>

            {{-- INI TAMBAHANNYA --}}
            <td class="label">Ruang Rawat</td>
            <td class="value">{{ $patient->asal_bangsal ?? 'N/A' }}</td>
        </tr>
    </table>
    <h3 class="cppt-title">CATATAN PERKEMBANGAN PASIEN TERINTEGRASI (CPPT / SOAP)</h3>

    <table class="cppt-table">
        <thead>
            <tr>
                <th class="col-tgl">TGL / JAM</th>
                <th class="col-profesi">PROFESI</th>
                <th class="col-soap">SOAP</th>
                <th class="col-instruksi">INSTRUKSI</th>
                <th class="col-petugas">PETUGAS & PARAF</th>
            </tr>
        </thead>

        <tbody>
            @forelse($dataForSoap as $cppt)
            <tr>
                <td>{{ $cppt->waktu_pemeriksaan->format('d/m/y H:i') }}</td>
                <td>{{ $cppt->pegawai?->jbtn ?? 'Petugas' }}</td>
                <td>
                    <span class="soap-label">S:</span> {!! nl2br(e($cppt->keluhan ?? '-')) !!}<br>
                    <span class="soap-label">O:</span> {!! nl2br(e($cppt->pemeriksaan ?? '-')) !!}<br>
                    <span class="soap-label">A:</span> {!! nl2br(e($cppt->penilaian ?? '-')) !!}<br>
                    <span class="soap-label">P:</span> {!! nl2br(e($cppt->rtl ?? '-')) !!}
                </td>
                <td>{!! nl2br(e($cppt->instruksi ?? '-')) !!}</td>
                <td>
                    {{ $cppt->pegawai?->nama ?? $cppt->nip }}
                    <div class="privy-box">
                        <span>Area TTD Digital (Privy)</span>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="no-data">Tidak ada catatan CPPT untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
