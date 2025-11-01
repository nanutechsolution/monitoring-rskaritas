<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitoring Intra Anestesi</title>
    <style>
        /* ================== Reset ================== */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.5;
            position: relative;
        }

        @page {
            margin: 25mm 20mm;
            @bottom-center {
                content: "Halaman " counter(page) " dari " counter(pages);
                font-size: 9pt;
                color: #555;
            }
        }

        /* ================== Watermark ================== */
        .watermark {
            position: fixed;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 60pt;
            color: rgba(0, 0, 0, 0.03);
            z-index: 0;
            pointer-events: none;
            user-select: none;
            font-weight: bold;
        }

        /* ================== Header ================== */
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #555;
            padding: 10px 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 90px;
            background: #fff;
            z-index: 10;
        }

        .header img {
            width: 90px;
            height: auto;
            margin-left: 15px;
        }

        .header .info {
            flex-grow: 1;
            text-align: center;
        }

        .header .info h1 {
            margin: 0;
            font-size: 17pt;
            color: #222;
            text-transform: uppercase;
        }

        .header .info p {
            margin: 2px 0;
            font-size: 9pt;
            color: #555;
        }

        /* ================== Footer ================== */
        .footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9pt;
            color: #555;
        }

        /* ================== Body ================== */
        .content {
            margin-top: 110px;
            margin-bottom: 60px;
            z-index: 5;
        }

        .main-title {
            text-align: center;
            font-size: 15pt;
            font-weight: bold;
            margin-bottom: 20px;
            color: #111;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10pt;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px 10px;
            vertical-align: top;
        }

        th {
            background-color: #f0f4f7;
            font-weight: bold;
            text-align: left;
        }

        tr:nth-child(even) td {
            background-color: #fafafa;
        }

        .no-border, .no-border td, .no-border th {
            border: none;
            padding: 0;
        }

        .label-col {
            width: 180px;
            font-weight: bold;
            color: #222;
        }

        .label-col-sm {
            width: 140px;
            font-weight: bold;
            color: #222;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .section-table th {
            background-color: #dbe9ff;
        }

        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        h3 {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        img.chart {
            width: 100%;
            max-width: 700px;
            display: block;
            margin: 10px auto;
        }

        .signature td {
            text-align: center;
            padding-top: 50px;
            font-weight: bold;
        }

        /* ================== Optional Highlight ================== */
        .highlight {
            background-color: #f5faff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="watermark">{{ $setting->nama_instansi }}</div>

    <div class="header">
        {{-- @if($logoBase64)
            <img src="{{ $logoBase64 }}" alt="Logo">
        @endif --}}
        <div class="info">
            <h1>{{ $setting->nama_instansi }}</h1>
            <p>{{ $setting->alamat_instansi }}, {{ $setting->kabupaten }}, {{ $setting->propinsi }}</p>
            <p>Kontak: {{ $setting->kontak }} | Email: {{ $setting->email }}</p>
        </div>
    </div>

    <div class="footer">
        Monitoring Intra Anestesi - {{ $setting->nama_instansi }}
    </div>

    <div class="content">
        <h2 class="main-title">Monitoring Intra Anestesi</h2>

        <!-- Detail Pasien -->
        <table class="no-border section">
            <tr>
                <td class="label-col">Nama</td>
                <td>{{ $pasien->nm_pasien ?? 'N/A' }}</td>
                <td class="label-col">No. RM</td>
                <td>{{ $monitoring->no_rekam_medis }}</td>
            </tr>
            <tr>
                <td class="label-col">Tgl Lahir / Usia</td>
                <td>{{ $pasien->tgl_lahir ? \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d-m-Y') : 'N/A' }} ({{ $pasien->umur }})</td>
                <td class="label-col">No. Rawat</td>
                <td>{{ $monitoring->no_rawat }}</td>
            </tr>
            <tr>
                <td class="label-col">Dokter Anestesi</td>
                <td>{{ $monitoring->dokterAnestesi->nm_dokter ?? 'N/A' }}</td>
                <td class="label-col">Penata Anestesi</td>
                <td>{{ $monitoring->penataAnestesi->nama ?? 'N/A' }}</td>
            </tr>
        </table>

        <!-- Dua kolom -->
        <table class="no-border section">
            <tr>
                <td style="width:50%; padding-right:5px; vertical-align:top;">
                    <table class="section-table">
                        <tr><th colspan="2">Persiapan, Premedikasi, & Induksi</th></tr>
                        <tr><td class="label-col-sm">Infus Perifer 1</td><td>{{ $monitoring->infus_perifer_1_tempat_ukuran ?? '-' }}</td></tr>
                        <tr><td class="label-col-sm">Infus Perifer 2</td><td>{{ $monitoring->infus_perifer_2_tempat_ukuran ?? '-' }}</td></tr>
                        <tr><td class="label-col-sm">CVC</td><td>{{ $monitoring->cvc ?? '-' }}</td></tr>
                        <tr><td class="label-col-sm">Posisi</td><td>{{ $monitoring->posisi ?? '-' }}</td></tr>
                        <tr><td class="label-col-sm">Perlindungan Mata</td><td>{{ $monitoring->perlindungan_mata ? 'Ya' : 'Tidak' }}</td></tr>
                        <tr><td class="label-col-sm">Premedikasi Oral</td><td>{{ $monitoring->premedikasi_oral ?? '-' }}</td></tr>
                        <tr><td class="label-col-sm">Premedikasi IV</td><td>{{ $monitoring->premedikasi_iv ?? '-' }}</td></tr>
                        <tr><td class="label-col-sm">Induksi IV</td><td>{{ $monitoring->induksi_intravena ?? '-' }}</td></tr>
                        <tr><td class="label-col-sm">Induksi Inhalasi</td><td>{{ $monitoring->induksi_inhalasi ?? '-' }}</td></tr>
                    </table>
                    <table class="section-table" style="margin-top:10px;">
                        <tr><th colspan="2">Waktu & Ventilasi</th></tr>
                        <tr><td class="label-col-sm">Mulai Anestesi</td><td>{{ $monitoring->mulai_anestesia ? $monitoring->mulai_anestesia->format('d-m-Y H:i') : '-' }}</td></tr>
                        <tr><td class="label-col-sm">Selesai Anestesi</td><td>{{ $monitoring->selesai_anestesia ? $monitoring->selesai_anestesia->format('d-m-Y H:i') : '-' }}</td></tr>
                        <tr><td class="label-col-sm">Mulai Pembedahan</td><td>{{ $monitoring->mulai_pembedahan ? $monitoring->mulai_pembedahan->format('d-m-Y H:i') : '-' }}</td></tr>
                        <tr><td class="label-col-sm">Selesai Pembedahan</td><td>{{ $monitoring->selesai_pembedahan ? $monitoring->selesai_pembedahan->format('d-m-Y H:i') : '-' }}</td></tr>
                        <tr><td class="label-col-sm">Ventilasi</td><td>{{ $monitoring->ventilasi_mode ?? '-' }}</td></tr>
                        @if($monitoring->ventilasi_mode == 'Ventilator')
                        <tr><td class="label-col-sm">TV</td><td>{{ $monitoring->ventilator_tv ?? '-' }}</td></tr>
                        <tr><td class="label-col-sm">RR</td><td>{{ $monitoring->ventilator_rr ?? '-' }}</td></tr>
                        <tr><td class="label-col-sm">PEEP</td><td>{{ $monitoring->ventilator_peep ?? '-' }}</td></tr>
                        @endif
                    </table>
                </td>
                <td style="width:50%; padding-left:5px; vertical-align:top;">
                    <table class="section-table">
                        <tr><th colspan="2">Jalan Nafas & Intubasi</th></tr>
                        <tr><td class="label-col-sm">Face mask No.</td><td>{{ $monitoring->jalan_nafas_facemask_no ?? '-' }}</td></tr>
                        <tr><td class="label-col-sm">Oro/Nasopharing</td><td>{{ $monitoring->jalan_nafas_oro_nasopharing ? 'Ya' : 'Tidak' }}</td></tr>
                        <tr><td class="label-col-sm">ETT No./Jenis/Fiksasi</td><td>{{ $monitoring->jalan_nafas_ett_no ?? '-' }} / {{ $monitoring->jalan_nafas_ett_jenis ?? '-' }} / {{ $monitoring->jalan_nafas_ett_fiksasi_cm ? $monitoring->jalan_nafas_ett_fiksasi_cm.' cm' : '-' }}</td></tr>
                        <tr><td class="label-col-sm">LMA No./Jenis</td><td>{{ $monitoring->jalan_nafas_lma_no ?? '-' }} / {{ $monitoring->jalan_nafas_lma_jenis ?? '-' }}</td></tr>
                        <tr><td class="label-col-sm">Intubasi</td><td>{{ $monitoring->intubasi_kondisi ?? '-' }} ({{ $monitoring->intubasi_jalan ?? '-' }})</td></tr>
                        <tr><td class="label-col-sm">Sulit Ventilasi</td><td>{{ $monitoring->sulit_ventilasi ? 'Ya' : 'Tidak' }}</td></tr>
                        <tr><td class="label-col-sm">Sulit Intubasi</td><td>{{ $monitoring->sulit_intubasi ? 'Ya' : 'Tidak' }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Grafik -->
        <div class="section">
            <h3>Grafik Pemantauan Vital</h3>
            @if($chartImageUrl)
                <img src="{{ $chartImageUrl }}" class="chart" alt="Grafik Vital">
            @else
                <p>Tidak ada data vital.</p>
            @endif
        </div>

        <!-- Tabel Vital & Obat -->
        <table class="no-border section">
            <tr>
                <td style="width:50%; padding-right:5px; vertical-align:top;">
                    <h3>Tanda Vital</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Nadi</th>
                                <th>Sis/Dis</th>
                                <th>RR</th>
                                <th>SpO2</th>
                                <th>Lain</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monitoring->vitals as $vital)
                            <tr>
                                <td>{{ $vital->waktu }}</td>
                                <td>{{ $vital->rrn ?? '-' }}</td>
                                <td>{{ $vital->td_sis ?? '-' }}/{{ $vital->td_dis ?? '-' }}</td>
                                <td>{{ $vital->rr ?? '-' }}</td>
                                <td>{{ $vital->spo2 ?? '-' }}</td>
                                <td>{{ $vital->pe_co2 ? 'PECO2:'.$vital->pe_co2 : '' }} {{ $vital->fio2 ? 'FiO2:'.$vital->fio2 : '' }} {{ $vital->lain_lain ?? '' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </td>
                <td style="width:50%; padding-left:5px; vertical-align:top;">
                    <h3>Obat / Infus / Gas</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Nama Obat</th>
                                <th>Dosis</th>
                                <th>Rute</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monitoring->medications as $med)
                            <tr>
                                <td>{{ $med->waktu }}</td>
                                <td>{{ $med->nama_obat_infus_gas }}</td>
                                <td>{{ $med->dosis ?? '-' }}</td>
                                <td>{{ $med->rute ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center">Tidak ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Signature -->
        <table class="signature no-border">
            <tr>
                <td>Penata Anestesi<br><br><br><br>({{ $monitoring->penataAnestesi->nama ?? '____________________' }})</td>
                <td>Dokter Anestesi<br><br><br><br>({{ $monitoring->dokterAnestesi->nm_dokter ?? '____________________' }})</td>
            </tr>
        </table>

    </div>
</body>
</html>
