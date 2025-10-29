<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Cache;

// PENTING:
// Kita implementasi kontrak 'Authenticatable'
// TAPI KITA TIDAK 'use AuthenticatableTrait'
class KhanzaUser extends Model implements Authenticatable
{
    // Kita tetap set tabel default, walaupun tidak akan dipakai untuk menulis
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    public $incrementing = false;
    protected $keyType = 'string';

    // Beri tahu Eloquent bahwa model ini "bodoh" dan tidak boleh disimpan
    public $exists = false;
    public $timestamps = false;


    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'nik', 'id_user');
    }

    /**
     * Cek apakah user ini adalah seorang Dokter.
     * Menggunakan pengecekan langsung ke tabel 'dokter'.
     *
     * @return bool
     */
    public function isDokter(): bool
    {
        // Identifier unik user (NIK/Username dari Khanza)
        $identifier = $this->getAuthIdentifier();

        // Jika identifier kosong, pasti bukan dokter
        if (empty($identifier)) {
            return false;
        }

        // --- Gunakan Cache agar tidak query ke DB terus-menerus ---
        $cacheKey = 'is_dokter_' . $identifier;

        // Cek di cache dulu selama 1 jam (3600 detik)
        return Cache::remember($cacheKey, 3600, function () use ($identifier) {
            // Jika tidak ada di cache, cek ke database
            // Cek apakah identifier ini ada di kolom kd_dokter pada tabel dokter
            return Dokter::where('kd_dokter', $identifier)->exists();
        });
        // --- Akhir penggunaan Cache ---
    }

    /**
     * Cegah Laravel mencoba menyimpan model ini ke database.
     * Ini adalah fungsi yang MENCEGAH error UPDATE Anda.
     */
    public function save(array $options = [])
    {
        return true; // Bilang saja berhasil, tapi tidak melakukan apa-apa
    }

    /**
     * Cegah Laravel mencoba mengupdate model ini.
     */
    public function update(array $attributes = [], array $options = [])
    {
        return true; // Bilang saja berhasil, tapi tidak melakukan apa-apa
    }

    // ===================================================================
    // IMPLEMENTASI MANUAL KONTRAK AUTHENTICATABLE
    // ===================================================================

    /**
     * Dapatkan nama identifier unik untuk user.
     */
    public function getAuthIdentifierName()
    {
        return 'id_user'; // Ini adalah nama kolom ID plain-text kita
    }

    /**
     * Dapatkan identifier unik untuk user.
     */
    public function getAuthIdentifier()
    {
        // $this->id_user akan diisi oleh KhanzaUserProvider
        return $this->{$this->getAuthIdentifierName()};
    }

    /**
     * Dapatkan password untuk user.
     * (Tidak akan dipakai untuk validasi, tapi wajib ada)
     */
    public function getAuthPassword()
    {
        // Kita tidak menyimpan password plain-text, jadi kembalikan hash palsu
        return 'dummy-hash-khanza-tidak-pakai-ini';
    }
    /**
     * Cek apakah user ini adalah Admin (berdasarkan flag dari provider).
     * @return bool
     */
    public function isAdmin(): bool
    {
        // Langsung cek nilai properti 'is_super_admin' yang di-set oleh provider
        return (bool) ($this->is_super_admin ?? false);
    }

    /**
     * Dapatkan nama kolom untuk password.
     */
    public function getAuthPasswordName()
    {
        return 'password'; // atau 'passworde', tidak masalah
    }

    /**
     * Dapatkan token "remember me".
     */
    public function getRememberToken()
    {
        return null; // Kita tidak pakai fitur ini
    }

    /**
     * Set token "remember me".
     */
    public function setRememberToken($value)
    {
        // Tidak melakukan apa-apa
    }

    /**
     * Dapatkan nama kolom "remember me".
     */
    public function getRememberTokenName()
    {
        return ''; // Kosongkan
    }
}
