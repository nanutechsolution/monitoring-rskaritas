<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Cetak Monitoring Intra Anestesi</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9pt;
            line-height: 1.3;
        }

        @page {
            margin: 15mm;
            /* Margin A4 */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            /* Kurangi margin */
        }

        /* Tabel utama untuk layout */
        .layout-table,
        .layout-table tr,
        .layout-table td {
            border: none;
            padding: 0;
            margin: 0;
        }

        /* Tabel data */
        .data-table th,
        .data-table td {
            border: 1px solid #777;
            padding: 4px 6px;
            vertical-align: top;
            word-wrap: break-word;
        }

        .data-table th {
            background-color: #f2f2f2;
            text-align: left;
            font-size: 9pt;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .mb-4 {
            margin-bottom: 15px;
        }

        .label-col {
            width: 170px;
            font-weight: bold;
        }

        .label-col-sm {
            width: 110px;
            font-weight: bold;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        .header {
            border-bottom: 3px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .main-title {
            font-size: 14pt;
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 11pt;
            font-weight: bold;
            background-color: #f2f2f2;
            padding: 5px;
            border: 1px solid #777;
            margin-top: 10px;
            margin-bottom: 5px;
        }

        /* Helper untuk checkbox */
        .check-box {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            margin-right: 5px;
            text-align: center;
            line-height: 10px;
            font-weight: bold;
            vertical-align: middle;
        }

        .checked {
            background-color: #ccc;
        }

        /* 💡 TAMBAHKAN STYLE INI */
        .header-info-table td {
            border: 1px solid #999;
            padding: 6px 8px;
            vertical-align: top;
        }

        .header-info-label {
            font-size: 8pt;
            color: #555;
            text-transform: uppercase;
            display: block;
            /* Membuat label di atas data */
            margin-bottom: 2px;
        }

        .header-info-data {
            font-size: 11pt;
            font-weight: bold;
        }

    </style>
</head>
<body>

    <table class="layout-table header">
        <tr>
            <td style="width: 80px;">
                @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="Logo" style="width: 70px; height: auto;">
                @endif
            </td>
            <td class="text-center">
                <h1 style="font-size: 14pt; margin: 0;" class="font-bold uppercase">{{ $setting->nama_instansi }}</h1>
                <p style="font-size: 9pt; margin: 2px 0;">{{ $setting->alamat_instansi }}, {{ $setting->kabupaten }}, {{ $setting->propinsi }}</p>
                <p style="font-size: 9pt; margin: 2px 0;">Kontak: {{ $setting->kontak }} | Email: {{ $setting->email }}</p>
            </td>
            <td style="width: 80px; text-align: right; vertical-align: top;">
                RM/RI/8.7.2
            </td>
        </tr>
    </table>

    <h2 class="main-title">MONITORING INTRA ANESTESI</h2>

    <table class="header-info-table mb-4">
        <tr>
            {{-- Kolom 1: Nama Pasien --}}
            <td>
                <span class="header-info-label">Nama Pasien</span>
                <span class="header-info-data">{{ $pasien->nm_pasien ?? 'N/A' }}</span>
            </td>
            {{-- Kolom 2: No. RM --}}
            <td>
                <span class="header-info-label">No. Rekam Medis</span>
                <span class="header-info-data">{{ $monitoring->no_rekam_medis }}</span>
            </td>
            {{-- Kolom 3: Dokter Anestesi --}}
            <td>
                <span class="header-info-label">Dokter Anestesi</span>
                <span class="header-info-data">{{ $monitoring->dokterAnestesi->nm_dokter ?? 'N/A' }}</span>
            </td>
        </tr>
        <tr>
            {{-- Kolom 4: Tgl Lahir & Usia --}}
            <td>
                <span class="header-info-label">Tgl. Lahir (Usia)</span>
                <span class="header-info-data">
                    {{ $pasien->tgl_lahir ? \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d-m-Y') : 'N/A' }}
                    ({{ $pasien->umur }})
                </span>
            </td>
            {{-- Kolom 5: No. Rawat --}}
            <td>
                <span class="header-info-label">No. Rawat</span>
                <span class="header-info-data">{{ $monitoring->no_rawat }}</span>
            </td>
            {{-- Kolom 6: Penata Anestesi --}}
            <td>
                <span class="header-info-label">Penata Anestesi</span>
                <span class="header-info-data">{{ $monitoring->penataAnestesi->nama ?? 'N/A' }}</span>
            </td>
        </tr>
    </table>
    <table class="layout-table mb-4">
        <tr>
            <td class="layout-table" style="width: 50%; padding-right: 5px; vertical-align: top;">
                <table class="data-table avoid-break">
                    <tr>
                        <th colspan="2">Persiapan & Premedikasi</th>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Infus Perifer 1</td>
                        <td>{{ $monitoring->infus_perifer_1_tempat_ukuran ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Infus Perifer 2</td>
                        <td>{{ $monitoring->infus_perifer_2_tempat_ukuran ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">CVC</td>
                        <td>{{ $monitoring->cvc ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Posisi</td>
                        <td>
                            <span class="check-box {{ $monitoring->posisi == 'Terlentang' ? 'checked' : '' }}"></span> Terlentang
                            <span class="check-box {{ $monitoring->posisi == 'Lithotomi' ? 'checked' : '' }}"></span> Lithotomi
                            <span class="check-box {{ $monitoring->posisi == 'Prone' ? 'checked' : '' }}"></span> Prone
                            <span class="check-box {{ $monitoring->posisi == 'Lateral Ka' ? 'checked' : '' }}"></span> Lateral Ka/Ki
                            <br>Lain-lain: {{ !in_array($monitoring->posisi, ['Terlentang', 'Lithotomi', 'Prone', 'Lateral Ka', 'Lateral Ki']) ? $monitoring->posisi : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Perlind. Mata</td>
                        <td><span class="check-box {{ $monitoring->perlindungan_mata ? 'checked' : '' }}"></span> Ya</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Premedikasi Oral</td>
                        <td>{{ $monitoring->premedikasi_oral ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Induksi Intravena</td>
                        <td>{{ $monitoring->induksi_intravena ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Induksi Inhalasi</td>
                        <td>{{ $monitoring->induksi_inhalasi ?? '-' }}</td>
                    </tr>
                </table>
                <table class="data-table avoid-break" style="margin-top: 5px;">
                    <tr>
                        <th colspan="2">Tata Laksana Jalan Nafas</th>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Face mask No.</td>
                        <td>{{ $monitoring->jalan_nafas_facemask_no ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Oro/Nasopharing</td>
                        <td><span class="check-box {{ $monitoring->jalan_nafas_oro_nasopharing ? 'checked' : '' }}"></span> Ya</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">ETT</td>
                        <td>No: {{ $monitoring->jalan_nafas_ett_no ?? '-' }} / Jenis: {{ $monitoring->jalan_nafas_ett_jenis ?? '-' }} / Fiksasi: {{ $monitoring->jalan_nafas_ett_fiksasi_cm ? $monitoring->jalan_nafas_ett_fiksasi_cm.' cm' : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">LMA</td>
                        <td>No: {{ $monitoring->jalan_nafas_lma_no ?? '-' }} / Jenis: {{ $monitoring->jalan_nafas_lma_jenis ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Lain-lain</td>
                        <td>
                            <span class="check-box {{ $monitoring->jalan_nafas_trakheostomi ? 'checked' : '' }}"></span> Trakheostomi
                            <span class="check-box {{ $monitoring->jalan_nafas_bronkoskopi_fiberoptik ? 'checked' : '' }}"></span> Bronkoskopi
                            <span class="check-box {{ $monitoring->jalan_nafas_glidescope ? 'checked' : '' }}"></span> Glidescope
                            Lain: {{ $monitoring->jalan_nafas_lain_lain ?? '' }}
                        </td>
                    </tr>
                </table>
            </td>
            <td class="layout-table" style="width: 50%; padding-left: 5px; vertical-align: top;">
                <table class="data-table avoid-break">
                    <tr>
                        <th colspan="2">Intubasi</th>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Kondisi</td>
                        <td>
                            <span class="check-box {{ $monitoring->intubasi_kondisi == 'Sesudah tidur' ? 'checked' : '' }}"></span> Sesudah tidur
                            <span class="check-box {{ $monitoring->intubasi_kondisi == 'Blind' ? 'checked' : '' }}"></span> Blind
                        </td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Jalan</td>
                        <td>
                            <span class="check-box {{ $monitoring->intubasi_jalan == 'Oral' ? 'checked' : '' }}"></span> Oral
                            <span class="check-box {{ $monitoring->intubasi_jalan == 'Nasal Ka' ? 'checked' : '' }}"></span> Nasal Ka/Ki
                        </td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Keterangan</td>
                        <td>
                            <span class="check-box {{ $monitoring->intubasi_dengan_stilet ? 'checked' : '' }}"></span> Stilet
                            <span class="check-box {{ $monitoring->intubasi_cuff ? 'checked' : '' }}"></span> Cuff
                            <span class="check-box {{ $monitoring->intubasi_pack ? 'checked' : '' }}"></span> Pack
                            Level ETT: {{ $monitoring->intubasi_level_ett ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Sulit Ventilasi</td>
                        <td><span class="check-box {{ $monitoring->sulit_ventilasi ? 'checked' : '' }}"></span> Ya</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Sulit Intubasi</td>
                        <td><span class="check-box {{ $monitoring->sulit_intubasi ? 'checked' : '' }}"></span> Ya</td>
                    </tr>
                </table>
                <table class="data-table avoid-break" style="margin-top: 5px;">
                    <tr>
                        <th colspan="2">Ventilasi</th>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Mode</td>
                        <td>
                            <span class="check-box {{ $monitoring->ventilasi_mode == 'Spontan' ? 'checked' : '' }}"></span> Spontan
                            <span class="check-box {{ $monitoring->ventilasi_mode == 'Kendali' ? 'checked' : '' }}"></span> Kendali
                            <span class="check-box {{ $monitoring->ventilasi_mode == 'Ventilator' ? 'checked' : '' }}"></span> Ventilator
                        </td>
                    </tr>
                    @if($monitoring->ventilasi_mode == 'Ventilator')
                    <tr>
                        <td class="label-col-sm">Ventilator TV</td>
                        <td>{{ $monitoring->ventilator_tv ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Ventilator RR</td>
                        <td>{{ $monitoring->ventilator_rr ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Ventilator PEEP</td>
                        <td>{{ $monitoring->ventilator_peep ?? '-' }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="label-col-sm">Konversi</td>
                        <td>{{ $monitoring->konversi ?? '-' }}</td>
                    </tr>
                </table>
                <table class="data-table avoid-break" style="margin-top: 5px;">
                    <tr>
                        <th colspan="2">Manajemen Waktu</th>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Mulai Anestesi (X)</td>
                        <td>{{ $monitoring->mulai_anestesia ? $monitoring->mulai_anestesia->format('H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Selesai Anestesi (X)</td>
                        <td>{{ $monitoring->selesai_anestesia ? $monitoring->selesai_anestesia->format('H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Mulai Pembedahan (O)</td>
                        <td>{{ $monitoring->mulai_pembedahan ? $monitoring->mulai_pembedahan->format('H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Selesai Pembedahan (O)</td>
                        <td>{{ $monitoring->selesai_pembedahan ? $monitoring->selesai_pembedahan->format('H:i') : '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="avoid-break mb-4">
        <h3 class="section-title">Grafik Pemantauan Vital</h3>
        @if($chartImageUrl)
        <div class="text-center" style="padding: 10px; border: 1px solid #999;">
            <img src="{{ $chartImageUrl }}" style="width: 100%; max-width: 680px; margin: auto;" alt="Grafik Vital">
        </div>
        @else
        <p style="text-align: center; padding: 20px; border: 1px solid #999;">Tidak ada data vital untuk ditampilkan.</p>
        @endif
    </div>

    <div class="avoid-break">
        <table class="layout-table mb-4">
            <tr>
                <td class="layout-table" style="width: 50%; padding-right: 5px; vertical-align: top;">
                    <h3 class="section-title" style="margin-top:0;">Tabel Tanda Vital</h3>
                    <table class="data-table">
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
                                <td class="text-center">{{ $vital->waktu }}</td>
                                <td class="text-center">{{ $vital->rrn ?? '-' }}</td>
                                <td class="text-center">{{ $vital->td_sis ?? '-' }}/{{ $vital->td_dis ?? '-' }}</td>
                                <td class="text-center">{{ $vital->rr ?? '-' }}</td>
                                <td class="text-center">{{ $vital->spo2 ?? '-' }}</td>
                                <td>{{ $vital->pe_co2 ? 'PECO2:'.$vital->pe_co2 : '' }} {{ $vital->fio2 ? 'FiO2:'.$vital->fio2 : '' }} {{ $vital->lain_lain ?? '' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </td>
                <td class="layout-table" style="width: 50%; padding-left: 5px; vertical-align: top;">
                    <h3 class="section-title" style="margin-top:0;">Obat-obatan / Infus / Gas</h3>
                    <table class="data-table">
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
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <table class="layout-table mb-4">
        <tr>
            <td class="layout-table" style="width: 50%; padding-right: 5px; vertical-align: top;">
                <table class="data-table avoid-break">
                    <tr>
                        <th colspan="2">Teknik Regional / Blok Perifer</th>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Jenis</td>
                        <td>{{ $monitoring->regional_jenis ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Lokasi</td>
                        <td>{{ $monitoring->regional_lokasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Jarum</td>
                        <td>{{ $monitoring->regional_jenis_jarum_no ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Kateter</td>
                        <td><span class="check-box {{ $monitoring->regional_kateter ? 'checked' : '' }}"></span> Ya. Fiksasi: {{ $monitoring->regional_fiksasi_cm ? $monitoring->regional_fiksasi_cm.' cm' : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Obat-obatan</td>
                        <td style="white-space: pre-wrap;">{{ $monitoring->regional_obat_obat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Komplikasi</td>
                        <td style="white-space: pre-wrap;">{{ $monitoring->regional_komplikasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Hasil</td>
                        <td>
                            <span class="check-box {{ $monitoring->regional_hasil == 'Total Blok' ? 'checked' : '' }}"></span> Total Blok
                            <span class="check-box {{ $monitoring->regional_hasil == 'Partial' ? 'checked' : '' }}"></span> Partial
                            <span class="check-box {{ $monitoring->regional_hasil == 'Batal' ? 'checked' : '' }}"></span> Batal
                        </td>
                    </tr>
                </table>
            </td>
            <td class="layout-table" style="width: 50%; padding-left: 5px; vertical-align: top;">
                <table class="data-table avoid-break">
                    <tr>
                        <th colspan="2">Catatan & Total Cairan</th>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Total Infus</td>
                        <td>{{ $monitoring->total_cairan_infus_ml ?? '-' }} ml</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Total Darah</td>
                        <td>{{ $monitoring->total_darah_ml ?? '-' }} ml</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Total Urin</td>
                        <td>{{ $monitoring->total_urin_ml ?? '-' }} ml</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Total Perdarahan</td>
                        <td>{{ $monitoring->total_perdarahan_ml ?? '-' }} ml</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Lama Pembiusan</td>
                        <td>{{ $monitoring->lama_pembiusan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Lama Pembedahan</td>
                        <td>{{ $monitoring->lama_pembedahan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col-sm">Masalah Intra Anestesi</td>
                        <td style="white-space: pre-wrap;">{{ $monitoring->masalah_intra_anestesi ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="avoid-break" style="margin-top: 20px;">
        <table class="layout-table">
            <tr>
                <td class="layout-table text-center" style="width: 50%;">
                    <p>Penata Anestesi</p>
                    <br><br><br><br>
                    <p>( {{ $monitoring->penataAnestesi->nama ?? '____________________' }} )</p>
                </td>
                <td class="layout-table text-center" style="width: 50%;">
                    <p>Dokter Anestesi</p>
                    <br><br><br><br>
                    <p>( {{ $monitoring->dokterAnestesi->nm_dokter ?? '____________________' }} )</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
