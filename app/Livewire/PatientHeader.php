<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Livewire\Attributes\Locked;
use Illuminate\View\View; // Import View

class PatientHeader extends Component
{
    #[Locked]
    public $patientData;

    public string $no_rawat;

    // Properti untuk ditampilkan di view
    public ?string $nama_pasien = null;
    public ?string $no_rkm_medis = null;
    public ?string $tgl_lahir = null;
    public ?string $jk = null;
    public ?string $nm_dokter = null;
    public ?string $diagnosa_awal = null;
    public ?string $tgl_masuk = null;
    public ?int $umur_bayi = null; // Umur dalam hari
    public ?float $berat_lahir = null; // Berat badan saat lahir
    public ?string $cara_persalinan = null;
    public ?string $jaminan = null;
    public ?string $status_rujukan = null;
    public ?string $asal_bangsal = null;
    public ?int $umur_koreksi = null; // Umur koreksi dalam minggu (jika prematur)
    public ?int $umur_kehamilan = null;
    public function mount(string $noRawat): void
    {
        $this->no_rawat = $noRawat;
        $rawData = DB::table('reg_periksa as rp')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('kamar_inap as ki', 'rp.no_rawat', '=', 'ki.no_rawat')
            ->join('dokter as d', 'rp.kd_dokter', '=', 'd.kd_dokter')
            ->join('penjab as pj', 'rp.kd_pj', '=', 'pj.kd_pj')
            ->leftJoin('pasien_bayi as pb', 'p.no_rkm_medis', '=', 'pb.no_rkm_medis')
            ->join('kamar as k', 'ki.kd_kamar', '=', 'k.kd_kamar')
            ->join('bangsal as b', 'k.kd_bangsal', '=', 'b.kd_bangsal')
            ->select(
                'p.nm_pasien',
                'p.no_rkm_medis',
                'p.tgl_lahir',
                'p.jk',
                // Ambil berat_badan dari pasien_bayi JIKA ADA, jika tidak, mungkin perlu query lain?
                'pb.berat_badan', // Asumsi ini berat lahir
                'pb.proses_lahir',
                'ki.tgl_masuk',
                'ki.diagnosa_awal',
                'd.nm_dokter',
                'pj.png_jawab as jaminan',
                'rp.tgl_registrasi', // Mungkin tidak perlu ditampilkan, tapi ada di query asli
                'rp.no_rawat', // Mungkin tidak perlu ditampilkan, tapi ada di query asli
                'rp.stts as status_rujukan',
                'b.nm_bangsal as asal_bangsal'
                // Tambahkan field lain jika diperlukan header, misal 'umur_kehamilan' dari 'pasien_bayi'?
            )
            // Kondisi where stts_pulang mungkin perlu disesuaikan tergantung kapan header ini ditampilkan
            ->where('ki.stts_pulang', '-') // Hanya ambil yang masih dirawat?
            ->where('rp.no_rawat', $this->no_rawat)
            ->orderBy('ki.tgl_masuk', 'desc') // Ambil data inap terakhir jika ada multiple?
            ->first();

        if (!$rawData) {
            // Beri pesan yang lebih sesuai jika hanya data pasien yang tidak ada
            abort(404, 'Data registrasi atau kamar inap pasien tidak ditemukan.');
        }

        $this->patientData = $rawData;

        // Assign ke properti publik untuk view
        $this->nama_pasien = $rawData->nm_pasien;
        $this->no_rkm_medis = $rawData->no_rkm_medis;
        $this->tgl_lahir = Carbon::parse($rawData->tgl_lahir)->isoFormat('D MMMM Y'); // Format tanggal
        $this->jk = ($rawData->jk == 'L' ? 'Laki-laki' : 'Perempuan');
        $this->nm_dokter = $rawData->nm_dokter;
        $this->diagnosa_awal = $rawData->diagnosa_awal;
        $this->tgl_masuk = Carbon::parse($rawData->tgl_masuk)->isoFormat('D MMMM Y HH:mm'); // Format tanggal masuk

        $this->berat_lahir = $rawData->berat_badan ?? null;
        $this->cara_persalinan = $rawData->proses_lahir ?? 'Tidak Diketahui';
        $this->jaminan = $rawData->jaminan ?? null;
        $this->status_rujukan = $rawData->status_rujukan ?? null;
        $this->asal_bangsal = $rawData->asal_bangsal ?? null;
        // --- Kalkulasi Usia ---
        $tanggalLahirCarbon = isset($rawData->tgl_lahir) ? Carbon::parse($rawData->tgl_lahir) : null;
        if ($tanggalLahirCarbon) {
            $this->umur_bayi = $tanggalLahirCarbon->diffInDays(now());
            // Kalkulasi Umur Koreksi (asumsi $this->umur_kehamilan sudah diisi dari sumber lain)
            // PASTIKAN $this->umur_kehamilan memiliki nilai yang benar sebelum kalkulasi ini
            $mingguKehamilan = isset($this->umur_kehamilan) ? (int) $this->umur_kehamilan : 0;
            if ($mingguKehamilan > 0 && $mingguKehamilan < 37) {
                $mingguPrematur = 40 - $mingguKehamilan;
                // Hitung tanggal perkiraan cukup bulan
                $tanggalCukupBulan = $tanggalLahirCarbon->copy()->addWeeks($mingguPrematur);
                // Hitung selisih minggu dari tanggal cukup bulan ke sekarang
                $this->umur_koreksi = $tanggalCukupBulan->diffInWeeks(now());
            } else {
                $this->umur_koreksi = null;
            }
        } else {
            $this->umur_bayi = null;
            $this->umur_koreksi = null;
        }
    }

    public function render(): View
    {
        return view('livewire.patient-header');
    }
}
