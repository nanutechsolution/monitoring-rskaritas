<?php

namespace App\Livewire\Monitoring;

use Livewire\Component;
use App\Models\RegPeriksa;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Petugas;
use App\Models\IntraAnesthesiaMonitoring;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Rule;
use Livewire\Attributes\On;

class AnesthesiaCreate extends Component
{
    // === DATA MASTER ===
    public $noRawat;
    public ?Pasien $pasien;
    public $allDokterAnestesi = [];
    public $allPenataAnestesi = [];

    // === MODE & LANGKAH ===
    public $mode = 'create';
    public ?IntraAnesthesiaMonitoring $monitoringRecord;
    public $currentStep = 1;
    public $totalSteps = 6;
    public $isSaving = false;

    // === SEMUA FIELD (STATE) ===
    public $no_rekam_medis, $nama_lengkap, $tanggal_lahir, $no_ktp_paspor, $kd_dokter_anestesi, $nip_penata_anestesi,
    $infus_perifer_1_tempat_ukuran, $infus_perifer_2_tempat_ukuran, $cvc, $posisi, $perlindungan_mata,
    $premedikasi_oral, $premedikasi_iv, $induksi_intravena, $induksi_inhalasi, $jalan_nafas_facemask_no,
    $jalan_nafas_oro_nasopharing, $jalan_nafas_ett_no, $jalan_nafas_ett_jenis, $jalan_nafas_ett_fiksasi_cm,
    $jalan_nafas_lma_no, $jalan_nafas_lma_jenis, $jalan_nafas_trakheostomi, $jalan_nafas_bronkoskopi_fiberoptik,
    $jalan_nafas_glidescope, $jalan_nafas_lain_lain, $intubasi_kondisi, $intubasi_jalan, $sulit_ventilasi,
    $sulit_intubasi, $intubasi_dengan_stilet, $intubasi_cuff, $intubasi_level_ett, $intubasi_pack,
    $ventilasi_mode, $ventilator_tv, $ventilator_rr, $ventilator_peep, $konversi, $mulai_anestesia,
    $selesai_anestesia, $mulai_pembedahan, $selesai_pembedahan, $lama_pembiusan, $lama_pembedahan,
    $regional_jenis, $regional_lokasi, $regional_jenis_jarum_no, $regional_kateter, $regional_fiksasi_cm,
    $regional_obat_obat, $regional_komplikasi, $regional_hasil, $masalah_intra_anestesi,
    $total_cairan_infus_ml, $total_darah_ml, $total_urin_ml, $total_perdarahan_ml;

    public $vitals = [];
    public $medications = [];

    // === DATA CHART (Hanya untuk mode edit) ===
    public $chartLabels = [];
    public $chartDataNadi = [];
    public $chartDataSistolik = [];
    public $chartDataDiastolik = [];
    public $chartDataRR = [];

    /**
     * Atribut untuk validasi
     */
    protected $validationAttributes = [
        'noRawat' => 'Nomor Rawat',
        'kd_dokter_anestesi' => 'Dokter Anestesi',
        'nip_penata_anestesi' => 'Penata Anestesi',
        'mulai_anestesia' => 'Waktu Mulai Anestesia',
        'mulai_pembedahan' => 'Waktu Mulai Pembedahan',
        'vitals.*.waktu' => 'Waktu pada baris vital',

        // Aturan Tambahan (Langkah 5)
        'medications.*.waktu' => 'Waktu pada baris obat',
        'medications.*.nama_obat_infus_gas' => 'Nama Obat pada baris obat',
    ];


    #[On('drugSelected')]
    public function updateDrugName($index, $name)
    {
        // Update array medications di baris yang benar
        if (isset($this->medications[$index])) {
            $this->medications[$index]['nama_obat_infus_gas'] = $name;
        }
    }

    /**
     * Metode rules()
     * Menyediakan aturan validasi dinamis berdasarkan langkah saat ini.
     */
    public function rules()
    {
        $rules = [];
        // Aturan untuk Langkah 1
        if ($this->currentStep == 1 || $this->isSaving) {
            $rules['kd_dokter_anestesi'] = 'required';
            $rules['nip_penata_anestesi'] = 'required';
        }

        // Aturan untuk Langkah 2
        if ($this->currentStep == 2 || $this->isSaving) {
            $rules['mulai_anestesia'] = 'required|date';
            $rules['mulai_pembedahan'] = 'required|date';
        }

        // Aturan untuk Langkah 4 (Vitals)
        if ($this->currentStep == 4 || $this->isSaving) {
            if ($this->isSaving && !empty($this->vitals) && (isset($this->vitals[0]['waktu']) || isset($this->vitals[0]['rrn']))) {
                $rules['vitals.*.waktu'] = 'required';
            }
        }


        if ($this->currentStep == 5 || $this->isSaving) {
            // Logika: Jika 'nama_obat_infus_gas' diisi, 'waktu' wajib diisi.
            $rules['medications.*.waktu'] = 'required_with:medications.*.nama_obat_infus_gas';

            // Logika: Jika 'waktu' diisi, 'nama_obat_infus_gas' wajib diisi.
            $rules['medications.*.nama_obat_infus_gas'] = 'required_with:medications.*.waktu';
        }

        $otherFields = [
            'no_rekam_medis',
            'nama_lengkap',
            'no_ktp_paspor',
            'infus_perifer_1_tempat_ukuran',
            'infus_perifer_2_tempat_ukuran',
            'cvc',
            'posisi',
            'perlindungan_mata',
            'premedikasi_oral',
            'premedikasi_iv',
            'induksi_intravena',
            'induksi_inhalasi',
            'jalan_nafas_facemask_no',
            'jalan_nafas_oro_nasopharing',
            'jalan_nafas_ett_no',
            'jalan_nafas_ett_jenis',
            'jalan_nafas_ett_fiksasi_cm',
            'jalan_nafas_lma_no',
            'jalan_nafas_lma_jenis',
            'jalan_nafas_trakheostomi',
            'jalan_nafas_bronkoskopi_fiberoptik',
            'jalan_nafas_glidescope',
            'jalan_nafas_lain_lain',
            'intubasi_kondisi',
            'intubasi_jalan',
            'sulit_ventilasi',
            'sulit_intubasi',
            'intubasi_dengan_stilet',
            'intubasi_cuff',
            'intubasi_level_ett',
            'intubasi_pack',
            'ventilasi_mode',
            'ventilator_tv',
            'ventilator_rr',
            'ventilator_peep',
            'konversi',
            'selesai_anestesia',
            'selesai_pembedahan',
            'lama_pembiusan',
            'lama_pembedahan',
            'regional_jenis',
            'regional_lokasi',
            'regional_jenis_jarum_no',
            'regional_kateter',
            'regional_fiksasi_cm',
            'regional_obat_obat',
            'regional_komplikasi',
            'regional_hasil',
            'masalah_intra_anestesi',
            'total_cairan_infus_ml',
            'total_darah_ml',
            'total_urin_ml',
            'total_perdarahan_ml'
        ];

        foreach ($otherFields as $field) {
            if (!isset($rules[$field])) {
                $rules[$field] = 'nullable';
            }
        }

        $rules['tanggal_lahir'] = 'nullable|date';
        $rules['vitals'] = 'nullable|array';
        $rules['medications'] = 'nullable|array';

        return $rules;
    }

    /**
     * Fungsi Mount
     */
    public function mount($noRawat = null, $monitoringId = null)
    {
        $this->loadDropdownData();

        if ($monitoringId) {
            // === MODE EDIT ===
            $this->mode = 'edit';
            $this->monitoringRecord = IntraAnesthesiaMonitoring::with('vitals', 'medications')->findOrFail($monitoringId);

            $regPeriksa = RegPeriksa::with('pasien')->where('no_rawat', $this->monitoringRecord->no_rawat)->firstOrFail();
            $this->pasien = $regPeriksa->pasien;

            $this->noRawat = $this->monitoringRecord->no_rawat;
            $this->fill($this->monitoringRecord->toArray());

            // Format data pasien & tanggal
            $this->no_rekam_medis = $this->pasien->no_rkm_medis;
            $this->nama_lengkap = $this->pasien->nm_pasien;
            $this->tanggal_lahir = $this->pasien->tgl_lahir ? $this->pasien->tgl_lahir->format('Y-m-d') : null;
            $this->mulai_anestesia = $this->monitoringRecord->mulai_anestesia ? $this->monitoringRecord->mulai_anestesia->format('Y-m-d\TH:i') : null;
            $this->selesai_anestesia = $this->monitoringRecord->selesai_anestesia ? $this->monitoringRecord->selesai_anestesia->format('Y-m-d\TH:i') : null;
            $this->mulai_pembedahan = $this->monitoringRecord->mulai_pembedahan ? $this->monitoringRecord->mulai_pembedahan->format('Y-m-d\TH:i') : null;
            $this->selesai_pembedahan = $this->monitoringRecord->selesai_pembedahan ? $this->monitoringRecord->selesai_pembedahan->format('Y-m-d\TH:i') : null;

            // Muat data relasi
            $this->vitals = $this->monitoringRecord->vitals->toArray();
            $this->medications = $this->monitoringRecord->medications->toArray();

            // Siapkan data chart
            if (!empty($this->vitals)) {
                $vitalsCollection = collect($this->vitals)->sortBy('waktu');
                $this->chartLabels = $vitalsCollection->pluck('waktu')->all();
                $this->chartDataNadi = $vitalsCollection->pluck('rrn')->all();
                $this->chartDataSistolik = $vitalsCollection->pluck('td_sis')->all();
                $this->chartDataDiastolik = $vitalsCollection->pluck('td_dis')->all();
                $this->chartDataRR = $vitalsCollection->pluck('rr')->all();
            }

        } elseif ($noRawat) {
            // === MODE CREATE ===
            $this->mode = 'create';
            $this->noRawat = str_replace('_', '/', $noRawat);

            $regPeriksa = RegPeriksa::with('pasien')->where('no_rawat', $this->noRawat)->firstOrFail();
            $this->pasien = $regPeriksa->pasien;

            $this->no_rekam_medis = $this->pasien->no_rkm_medis;
            $this->nama_lengkap = $this->pasien->nm_pasien;
            $this->tanggal_lahir = $this->pasien->tgl_lahir ? $this->pasien->tgl_lahir->format('Y-m-d') : null;
        } else {
            abort(404, 'Parameter tidak valid.');
        }

        // Selalu pastikan ada 1 baris kosong jika array kosong
        if (empty($this->vitals))
            $this->addVital();
        if (empty($this->medications))
            $this->addMedication();
    }

    /**
     * Helper untuk memuat dropdown
     */
    public function loadDropdownData()
    {
        // 💡 DIPERBAIKI: Tambahkan filter 'kd_sps'
        $this->allDokterAnestesi = Dokter::where('status', '1')

            ->orderBy('nm_dokter', 'asc')
            ->get();

        // 💡 DIPERBAIKI: Tambahkan filter 'kd_jbtn'
        $kodeJabatanPenata = 'PNA';
        $this->allPenataAnestesi = Petugas::where('status', '1')
            ->orderBy('nama', 'asc')
            ->get();
    }

    // === Navigasi Wizard ===
    public function nextStep()
    {
        $this->validate();
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    // === Helper Baris Dinamis ===
    public function addVital()
    {
        $this->vitals[] = ['waktu' => '', 'rrn' => '', 'td_sis' => '', 'td_dis' => '', 'rr' => '', 'spo2' => '', 'pe_co2' => '', 'fio2' => '', 'lain_lain' => ''];
    }
    public function removeVital($index)
    {
        unset($this->vitals[$index]);
        $this->vitals = array_values($this->vitals);
    }
    public function addMedication()
    {
        $this->medications[] = ['waktu' => '', 'nama_obat_infus_gas' => '', 'dosis' => '', 'rute' => ''];
    }
    public function removeMedication($index)
    {
        unset($this->medications[$index]);
        $this->medications = array_values($this->medications);
    }

    /**
     * Fungsi Simpan Utama
     */
    public function save()
    {
        $this->isSaving = true; // Set flag untuk memvalidasi SEMUA aturan

        try {
            $this->validate(); // Memvalidasi semua aturan dari rules()
        } catch (ValidationException $e) {

            // Logika untuk menemukan langkah error
            $errorStep = 1;
            $errorKeys = array_keys($e->validator->errors()->getMessages());

            if (in_array('kd_dokter_anestesi', $errorKeys) || in_array('nip_penata_anestesi', $errorKeys))
                $errorStep = 1;
            elseif (in_array('mulai_anestesia', $errorKeys) || in_array('mulai_pembedahan', $errorKeys))
                $errorStep = 2;
            elseif (str_contains(implode(',', $errorKeys), 'vitals.'))
                $errorStep = 4;

            $this->currentStep = $errorStep; // Kirim pengguna ke langkah yang error
            $this->isSaving = false; // Reset flag
            $this->dispatch('validation-failed'); // Kirim event untuk scroll
            throw $e;
        }

        $this->isSaving = false; // Reset flag

        // Kumpulkan data utama
        $mainData = [
            'no_rawat' => $this->noRawat,
            'no_rekam_medis' => $this->no_rekam_medis,
            'nama_lengkap' => $this->nama_lengkap,
            'tanggal_lahir' => $this->tanggal_lahir,
            'no_ktp_paspor' => $this->no_ktp_paspor,
            'kd_dokter_anestesi' => $this->kd_dokter_anestesi,
            'nip_penata_anestesi' => $this->nip_penata_anestesi,
            'infus_perifer_1_tempat_ukuran' => $this->infus_perifer_1_tempat_ukuran,
            'infus_perifer_2_tempat_ukuran' => $this->infus_perifer_2_tempat_ukuran,
            'cvc' => $this->cvc,
            'posisi' => $this->posisi,
            'perlindungan_mata' => $this->perlindungan_mata ?? false,
            'premedikasi_oral' => $this->premedikasi_oral,
            'premedikasi_iv' => $this->premedikasi_iv,
            'induksi_intravena' => $this->induksi_intravena,
            'induksi_inhalasi' => $this->induksi_inhalasi,
            'jalan_nafas_facemask_no' => $this->jalan_nafas_facemask_no,
            'jalan_nafas_oro_nasopharing' => $this->jalan_nafas_oro_nasopharing ?? false,
            'jalan_nafas_ett_no' => $this->jalan_nafas_ett_no,
            'jalan_nafas_ett_jenis' => $this->jalan_nafas_ett_jenis,
            'jalan_nafas_ett_fiksasi_cm' => $this->jalan_nafas_ett_fiksasi_cm,
            'jalan_nafas_lma_no' => $this->jalan_nafas_lma_no,
            'jalan_nafas_lma_jenis' => $this->jalan_nafas_lma_jenis,
            'jalan_nafas_trakheostomi' => $this->jalan_nafas_trakheostomi ?? false,
            'jalan_nafas_bronkoskopi_fiberoptik' => $this->jalan_nafas_bronkoskopi_fiberoptik ?? false,
            'jalan_nafas_glidescope' => $this->jalan_nafas_glidescope ?? false,
            'jalan_nafas_lain_lain' => $this->jalan_nafas_lain_lain,
            'intubasi_kondisi' => $this->intubasi_kondisi,
            'intubasi_jalan' => $this->intubasi_jalan,
            'sulit_ventilasi' => $this->sulit_ventilasi ?? false,
            'sulit_intubasi' => $this->sulit_intubasi ?? false,
            'intubasi_dengan_stilet' => $this->intubasi_dengan_stilet ?? false,
            'intubasi_cuff' => $this->intubasi_cuff ?? false,
            'intubasi_level_ett' => $this->intubasi_level_ett,
            'intubasi_pack' => $this->intubasi_pack ?? false,
            'ventilasi_mode' => $this->ventilasi_mode,
            'ventilator_tv' => $this->ventilator_tv,
            'ventilator_rr' => $this->ventilator_rr,
            'ventilator_peep' => $this->ventilator_peep,
            'konversi' => $this->konversi,
            'mulai_anestesia' => $this->mulai_anestesia,
            'selesai_anestesia' => $this->selesai_anestesia,
            'mulai_pembedahan' => $this->mulai_pembedahan,
            'selesai_pembedahan' => $this->selesai_pembedahan,
            'lama_pembiusan' => $this->lama_pembiusan,
            'lama_pembedahan' => $this->lama_pembedahan,
            'regional_jenis' => $this->regional_jenis,
            'regional_lokasi' => $this->regional_lokasi,
            'regional_jenis_jarum_no' => $this->regional_jenis_jarum_no,
            'regional_kateter' => $this->regional_kateter ?? false,
            'regional_fiksasi_cm' => $this->regional_fiksasi_cm,
            'regional_obat_obat' => $this->regional_obat_obat,
            'regional_komplikasi' => $this->regional_komplikasi,
            'regional_hasil' => $this->regional_hasil,
            'masalah_intra_anestesi' => $this->masalah_intra_anestesi,
            'total_cairan_infus_ml' => $this->total_cairan_infus_ml,
            'total_darah_ml' => $this->total_darah_ml,
            'total_urin_ml' => $this->total_urin_ml,
            'total_perdarahan_ml' => $this->total_perdarahan_ml,
        ];
        // Transaksi Database (Logika ini SAMA dan sudah BENAR)
        try {
            DB::transaction(function () use ($mainData) {
                $monitoring = null;
                if ($this->mode === 'edit') {
                    $this->monitoringRecord->update($mainData);
                    $monitoring = $this->monitoringRecord;
                    $monitoring->vitals()->delete();
                    $monitoring->medications()->delete();
                } else {
                    $monitoring = IntraAnesthesiaMonitoring::create($mainData);
                }

                // Simpan vitals
                foreach ($this->vitals as $vitalData) {
                    if (!empty($vitalData['waktu'])) {
                        $sanitizedData = [
                            'waktu' => $vitalData['waktu'],
                            'rrn' => $vitalData['rrn'] === '' ? null : $vitalData['rrn'],
                            'td_sis' => $vitalData['td_sis'] === '' ? null : $vitalData['td_sis'],
                            'td_dis' => $vitalData['td_dis'] === '' ? null : $vitalData['td_dis'],
                            'rr' => $vitalData['rr'] === '' ? null : $vitalData['rr'],
                            'spo2' => $vitalData['spo2'] === '' ? null : $vitalData['spo2'],
                            'pe_co2' => $vitalData['pe_co2'] === '' ? null : $vitalData['pe_co2'],
                            'fio2' => $vitalData['fio2'] === '' ? null : $vitalData['fio2'],
                            'lain_lain' => $vitalData['lain_lain'] === '' ? null : $vitalData['lain_lain'],
                        ];
                        $monitoring->vitals()->create($sanitizedData);
                    }
                }

                // Simpan obat-obatan
                foreach ($this->medications as $medData) {
                    if (!empty($medData['nama_obat_infus_gas'])) {
                        $sanitizedMedData = [
                            'waktu' => $medData['waktu'],
                            'nama_obat_infus_gas' => $medData['nama_obat_infus_gas'],
                            'dosis' => $medData['dosis'] === '' ? null : $medData['dosis'],
                            'rute' => $medData['rute'] === '' ? null : $medData['rute'],
                        ];
                        $monitoring->medications()->create($sanitizedMedData);
                    }
                }
            });

            $pesan = $this->mode === 'edit' ? 'Formulir berhasil diperbarui.' : 'Formulir berhasil disimpan.';
            session()->flash('success', $pesan);

            return $this->redirectRoute('monitoring.anestesi.history', [
                'noRawat' => str_replace('/', '_', $this->noRawat)
            ]);

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            $this->dispatch('save-failed');
        }
    }

    /**
     * Render Tampilan
     */
    public function render()
    {
        return view('livewire.monitoring.anesthesia-create')
            ->layout('layouts.app'); // Sesuaikan dengan layout utama Anda
    }
}
