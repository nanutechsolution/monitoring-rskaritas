<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Cetak Monitoring PICU - {{ $regPeriksa->no_rawat }}</title>
    <style>
        /* CSS Sederhana untuk PDF */
        @page { margin: 20px; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            margin: 0;
        }
        .page-break { page-break-after: always; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #666;
            padding: 4px;
            text-align: left;
            vertical-align: top;
            page-break-inside: avoid; /* Mencegah baris terpotong */
        }
        th {
            background-color: #f2f2f2;
            font-size: 9px;
            padding: 5px 4px;
        }

        /* === KOP SURAT === */
        .kop-surat { border: none; margin-bottom: 10px; }
        .kop-surat .logo { width: 70px; border: none; vertical-align: top; }
        .kop-surat .info { border: none; text-align: left; vertical-align: top; }
        .kop-surat .info strong { font-size: 14px; }
        .kop-surat .info span { font-size: 11px; }
        .kop-line { border-bottom: 1px solid #000; margin-bottom: 10px; }

        /* === HEADER PASIEN === */
        .header-table td { border: none; font-size: 11px; vertical-align: top; }

        /* === CATATAN HARIAN === */
        .notes-table td { vertical-align: top; height: 80px; }

        /* === GRID 24 JAM === */
        .grid-table { font-size: 8px; }
        .grid-table th { text-align: center; }
        .grid-table td { text-align: center; padding: 2px; }
        .grid-table .param-col { text-align: left; font-weight: bold; width: 100px; }
        .grid-table .param-col-sub { text-align: left; padding-left: 10px; width: 100px; }
        .section-header { background-color: #e0e0e0; font-weight: bold; text-align: center; }

        /* === LOG TABLES === */
        .log-table { font-size: 9px; }
        .log-table th { text-align: left; }

        /* === CPPT === */
        .cppt-entry {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 5px;
            margin-bottom: 5px;
            page-break-inside: avoid;
        }
        .cppt-header {
            font-size: 10px;
            font-weight: bold;
            border-bottom: 1px solid #eee;
            padding-bottom: 3px;
            margin-bottom: 3px;
        }
        .cppt-body { font-size: 9px; }
        .cppt-body strong { display: inline-block; width: 60px; }

        /* === JUDUL === */
        h1 { text-align: center; font-size: 16px; margin: 0; }
        h2 { text-align: center; font-size: 14px; margin: 0 0 10px 0; }
        h3 { font-size: 12px; margin: 10px 0 5px 0; background-color: #f2f2f2; padding: 4px; }
    </style>
</head>
<body>
    {{-- =============================================== --}}
    {{-- === 1. KOP SURAT (HEADER INSTANSI) === --}}
    {{-- =============================================== --}}
    @if($setting)
    <table class="kop-surat">
        <tr>
            <td class="logo">
                @if($setting->logo)
                    <img src="data:image/jpeg;base64,{{ base64_encode($setting->logo) }}" style="width: 60px; height: auto;">
                @endif
            </td>
            <td class="info">
                <strong>{{ $setting->nama_instansi }}</strong><br>
                <span>{{ $setting->alamat_instansi }}</span><br>
                <span>{{ $setting->kabupaten }}, {{ $setting->propinsi }}</span><br>
                <span>Kontak: {{ $setting->kontak }} | Email: {{ $setting->email }}</span>
            </td>
        </tr>
    </table>
    <div class="kop-line"></div>
    @endif

    <h1>MONITORING 24 JAM</h1>
    <h2>PEDIATRIC INTENSIF CARE UNIT (PICU)</h2>

    {{-- =============================================== --}}
    {{-- === 2. HEADER PASIEN === --}}
    {{-- =============================================== --}}
    <h3>A. Data Pasien & Admisi</h3>
    <table class="header-table">
        <tr>
            <td><strong>Nama</strong>: {{ $regPeriksa->pasien->nm_pasien }}</td>
            <td><strong>Umur</strong>: {{ $regPeriksa->umur }}</td>
            <td><strong>Umur Kehamilan</strong>: {{ $monitoringSheet->umur_kehamilan }}</td>
            <td><strong>Cara Persalinan</strong>: {{ $monitoringSheet->cara_persalinan }}</td>
        </tr>
        <tr>
            <td><strong>No. RM</strong>: {{ $regPeriksa->no_rkm_medis }}</td>
            <td><strong>Hari Rawat Ke</strong>: {{ \Carbon\Carbon::parse($regPeriksa->tgl_registrasi)->diffInDays(now()) + 1 }}</td>
            <td><strong>Umur Koreksi</strong>: {{ $monitoringSheet->umur_koreksi }}</td>
            <td><strong>Rujukan</strong>: {{ $monitoringSheet->rujukan }}</td>
        </tr>
         <tr>
            <td><strong>Tgl Lahir</strong>: {{ $regPeriksa->pasien->tgl_lahir->format('d/m/Y') }}</td>
            <td><strong>Jenis Kelamin</strong>: {{ $regPeriksa->pasien->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            <td><strong>Berat Lahir</strong>: {{ $monitoringSheet->berat_badan_lahir }}</td>
            <td><strong>Asal Ruangan</strong>: {{ $monitoringSheet->asal_ruangan }}</td>
        </tr>
        <tr>
            <td><strong>DPJP</strong>: {{ $monitoringSheet->dokter->nm_dokter ?? '-' }}</td>
            <td colspan="2"><strong>Diagnosis</strong>: {{ $monitoringSheet->diagnosis }}</td>
            <td><strong>Jaminan</strong>: {{ $monitoringSheet->jaminan }}</td>
        </tr>
    </table>

    {{-- =============================================== --}}
    {{-- === 3. CATATAN HARIAN === --}}
    {{-- =============================================== --}}
    <h3>B. Catatan Klinis Harian</h3>
    <table class="notes-table">
        <tr>
            <td style="width: 50%;"><strong>Masalah:</strong><br>{{ $monitoringSheet->masalah }}</td>
            <td style="width: 50%;"><strong>Catatan Nutrisi (Enteral & Parenteral):</strong><br>{{ $monitoringSheet->catatan_nutrisi }}</td>
        </tr>
        <tr>
            <td><strong>Program Terapi:</strong><br>{{ $monitoringSheet->program_terapi }}</td>
            <td><strong>Pemeriksaan Laboratorium (Catatan):</strong><br>{{ $monitoringSheet->catatan_lab }}</td>
        </tr>
    </table>

    {{-- =============================================== --}}
    {{-- === 4. GRID 24 JAM (TANDA VITAL & VENTILATOR) === --}}
    {{-- =============================================== --}}
    <h3>C. Grid Observasi 24 Jam</h3>
    <table class="grid-table">
        <thead>
            <tr>
                <th class="param-col">Parameter</th>
                @foreach ($hours as $hour)
                    <th>{{ $hour }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{-- Tanda Vital --}}
            <tr><td colspan="{{ count($hours) + 1 }}" class="section-header">TANDA VITAL & OBSERVASI</td></tr>
            <tr><td class="param-col">Temp. Inkubator</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->temp_inkubator ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col">Temp. Skin</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->temp_skin ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col">Heart Rate</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->heart_rate ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col">Resp. Rate</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->respiratory_rate ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col">Tekanan Darah</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->tekanan_darah ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col">Sat. O2</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->sat_o2 ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col">Irama EKG</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->irama_ekg ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col">Skala Nyeri</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->skala_nyeri ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col">Huidifier Inkubator</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->huidifier_inkubator ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col">Cyanosis</td>@foreach ($hours as $hour)<td>{{ ($cycles[$hour]->cyanosis ?? false) ? '+' : '' }}</td>@endforeach</tr>
            <tr><td class="param-col">Pucat</td>@foreach ($hours as $hour)<td>{{ ($cycles[$hour]->pucat ?? false) ? '+' : '' }}</td>@endforeach</tr>
            <tr><td class="param-col">Icterus</td>@foreach ($hours as $hour)<td>{{ ($cycles[$hour]->icterus ?? false) ? '+' : '' }}</td>@endforeach</tr>
            <tr><td class="param-col">CRT < 2 dtk</td>@foreach ($hours as $hour)<td>{{ ($cycles[$hour]->crt_lt_2 ?? false) ? '+' : '' }}</td>@endforeach</tr>
            <tr><td class="param-col">Bradikardia</td>@foreach ($hours as $hour)<td>{{ ($cycles[$hour]->bradikardia ?? false) ? '+' : '' }}</td>@endforeach</tr>
            <tr><td class="param-col">Stimulasi</td>@foreach ($hours as $hour)<td>{{ ($cycles[$hour]->stimulasi ?? false) ? '+' : '' }}</td>@endforeach</tr>

            {{-- Ventilator --}}
            <tr><td colspan="{{ count($hours) + 1 }}" class="section-header">TERAPI OKSIGEN / VENTILATOR</td></tr>
            <tr><td class="param-col">Mode</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_mode ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">Nasal: FiO2</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_fio2_nasal ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">Nasal: Flow</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_flow_nasal ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">CPAP: FiO2</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_fio2_cpap ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">CPAP: Flow</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_flow_cpap ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">CPAP: PEEP</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_peep_cpap ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">HFO: FiO2</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_fio2_hfo ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">HFO: Frekuensi</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_frekuensi_hfo ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">HFO: MAP</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_map_hfo ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">HFO: Amplitudo</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_amplitudo_hfo ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">HFO: I:T</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_it_hfo ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">Mekanik: Mode</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_mode_mekanik ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">Mekanik: FiO2</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_fio2_mekanik ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">Mekanik: PEEP</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_peep_mekanik ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">Mekanik: PIP</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_pip_mekanik ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">Mekanik: TV/Vte</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_tv_vte_mekanik ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">Mekanik: RR/Spontan</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_rr_spontan_mekanik ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">Mekanik: P. Max</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_p_max_mekanik ?? '' }}</td>@endforeach</tr>
            <tr><td class="param-col-sub">Mekanik: I:E</td>@foreach ($hours as $hour)<td>{{ $cycles[$hour]->vent_ie_mekanik ?? '' }}</td>@endforeach</tr>
        </tbody>
    </table>

    {{-- =============================================== --}}
    {{-- === 5. LOG BLOOD GAS (AGD) === --}}
    {{-- =============================================== --}}
    <h3>D. Log Blood Gas Monitor (AGD)</h3>
    <table class="log-table">
        <thead>
            <tr>
                <th style="width: 15%;">Waktu</th>
                <th>Gula Darah</th>
                <th>pH</th>
                <th>PCO2</th>
                <th>PO2</th>
                <th>HCO3</th>
                <th>BE</th>
                <th>SaO2</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($monitoringSheet->bloodGasLogs as $log)
                <tr>
                    <td>{{ $log->waktu_log->format('d/m H:i') }}</td>
                    <td>{{ $log->guka_darah_bs }}</td>
                    <td>{{ $log->ph }}</td>
                    <td>{{ $log->pco2 }}</td>
                    <td>{{ $log->po2 }}</td>
                    <td>{{ $log->hco3 }}</td>
                    <td>{{ $log->be }}</td>
                    <td>{{ $log->sao2 }}</td>
                    <td>{{ $log->petugas_id }}</td>
                </tr>
            @empty
                <tr><td colspan="9" style="text-align: center;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Memulai halaman baru jika datanya terlalu banyak --}}
    <div class="page-break"></div>

    {{-- =============================================== --}}
    {{-- === 6. KESEIMBANGAN CAIRAN === --}}
    {{-- =============================================== --}}
    <h3>E. Nutrisi & Keseimbangan Cairan</h3>

    {{-- Summary Dulu --}}
    <h4>Summary 24 Jam</h4>
    <table class="log-table">
        <tr>
            <th>Balance 24 Jam Sblm</th>
            <th>Total Cairan Masuk (CM)</th>
            <th>Total Cairan Keluar (CK)</th>
            <th>EWL / IWL</th>
            <th>Balance Harian (CM - CK - EWL)</th>
            <th>Balance Kumulatif</th>
            <th>Produksi Urine 24 Jam</th>
        </tr>
        <tr>
            <td>{{ $monitoringSheet->balance_cairan_24h_sebelumnya }} ml</td>
            <td>{{ $monitoringSheet->total_cairan_masuk_24h }} ml</td>
            <td>{{ $monitoringSheet->total_cairan_keluar_24h }} ml</td>
            <td>{{ $monitoringSheet->ewl_24h }} ml</td>
            <td>{{ $monitoringSheet->balance_cairan_24h }} ml</td>
            <td>{{ $monitoringSheet->balance_cairan_24h_sebelumnya + $monitoringSheet->balance_cairan_24h }} ml</td>
            <td>{{ $monitoringSheet->produksi_urine_24h }} ml</td>
        </tr>
    </table>

    {{-- Log Cairan (Side-by-side) --}}
    <table style="border: none;">
        <tr style="border: none;">
            <td style="width: 50%; border: none; vertical-align: top; padding-right: 10px;">
                <h4>Log Cairan Masuk</h4>
                <table class="log-table">
                    <thead><tr><th>Waktu</th><th>Kategori</th><th>Keterangan</th><th>Jumlah (ml)</th></tr></thead>
                    <tbody>
                        @forelse ($monitoringSheet->fluidInputs as $log)
                            <tr>
                                <td>{{ $log->waktu_log->format('H:i') }}</td>
                                <td>{{ $log->kategori }}</td>
                                <td>{{ $log->keterangan }}</td>
                                <td>{{ $log->jumlah }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align: center;">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
            <td style="width: 50%; border: none; vertical-align: top; padding-left: 10px;">
                <h4>Log Cairan Keluar</h4>
                <table class="log-table">
                    <thead><tr><th>Waktu</th><th>Kategori</th><th>Keterangan</th><th>Jumlah (ml)</th></tr></thead>
                    <tbody>
                        @forelse ($monitoringSheet->fluidOutputs as $log)
                            <tr>
                                <td>{{ $log->waktu_log->format('H:i') }}</td>
                                <td>{{ $log->kategori }}</td>
                                <td>{{ $log->keterangan }}</td>
                                <td>{{ $log->jumlah }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align: center;">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    {{-- =============================================== --}}
    {{-- === 7. OBAT-OBATAN === --}}
    {{-- =============================================== --}}
    <h3>F. Obat-obatan</h3>
    <table class="log-table">
        <thead>
            <tr>
                <th style="width: 15%;">Waktu</th>
                <th>Nama Obat</th>
                <th>Dosis</th>
                <th>Rute</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($monitoringSheet->medicationLogs as $log)
                <tr>
                    <td>{{ $log->waktu_pemberian->format('d/m H:i') }}</td>
                    <td>{{ $log->nama_obat }}</td>
                    <td>{{ $log->dosis }}</td>
                    <td>{{ $log->rute }}</td>
                    <td>{{ $log->petugas_id }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align: center;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- =============================================== --}}
    {{-- === 8. ALAT TERPASANG === --}}
    {{-- =============================================== --}}
    <h3>G. Alat Terpasang</h3>
    <table class="log-table">
        <thead>
            <tr>
                <th style="width: 15%;">Tgl. Pasang</th>
                <th>Nama Alat</th>
                <th>Ukuran</th>
                <th>Lokasi</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($monitoringSheet->devices as $log)
                <tr>
                    <td>{{ $log->tanggal_pemasangan->format('d/m/Y') }}</td>
                    <td>{{ $log->nama_alat }}</td>
                    <td>{{ $log->ukuran }}</td>
                    <td>{{ $log->lokasi }}</td>
                    <td>{{ $log->petugas_id }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align: center;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- =============================================== --}}
    {{-- === 9. CPPT (HALAMAN 2) === --}}
    {{-- =============================================== --}}
    <div class="page-break"></div>
    <h3>H. Catatan Perkembangan Pasien Terintegrasi (CPPT)</h3>

    @forelse ($cpptRecords as $cppt)
        <div class="cppt-entry">
            <div class="cppt-header">
                {{ $cppt->tgl_perawatan->format('d/m/Y') }} {{ \Carbon\Carbon::parse($cppt->jam_rawat)->format('H:i:s') }}
                - oleh: {{ $cppt->pegawai->nama ?? $cppt->nip }}
            </div>
            <div class="cppt-body">
                <div><strong>S (Subjek):</strong> {{ $cppt->keluhan }}</div>
                <div><strong>O (Objek):</strong> {{ $cppt->pemeriksaan }}
                    <div style="font-size: 8px; margin-left: 60px; color: #333;">
                        T: {{ $cppt->tensi }} mmHg, N: {{ $cppt->nadi }} x/m, RR: {{ $cppt->respirasi }} x/m, S: {{ $cppt->suhu_tubuh }} °C, SpO2: {{ $cppt->spo2 }} %, GCS: {{ $cppt->gcs }} ({{ $cppt->kesadaran }})
                    </div>
                </div>
                <div><strong>A (Asesmen):</strong> {{ $cppt->penilaian }}</div>
                <div><strong>P (Plan):</strong> {{ $cppt->rtl }}
                    @if($cppt->instruksi)<br><strong>Instruksi:</strong> {{ $cppt->instruksi }}@endif
                </div>
            </div>
        </div>
    @empty
        <p style="text-align: center;">Tidak ada data CPPT</p>
    @endforelse

</body>
</html>
