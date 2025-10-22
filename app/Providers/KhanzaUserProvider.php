<?php

namespace App\Providers;

use App\Models\KhanzaUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class KhanzaUserProvider extends EloquentUserProvider
{
    /**
     * Fungsi helper untuk membuat instance KhanzaUser dari data DB
     */
    private function createKhanzaUserInstance($dbRow, $plainUsername)
    {
        $user = new KhanzaUser();

        // Salin SEMUA atribut (seperti 'penyakit', 'dokter', 'petugas', dll)
        $attributes = (array) $dbRow;

        // Hapus password terenkripsi. Kita tidak perlu menyimpannya di session.
        unset($attributes['password']);
        unset($attributes['passworde']);

        // Set 'id_user' ke username plain-text yang dipakai login
        $attributes['id_user'] = $plainUsername;

        $user->setRawAttributes($attributes); // Atur semua data
        $user->exists = true; // Tandai ini sebagai "ada"

        return $user;
    }

    /**
     * Mengambil user berdasarkan kredensial (saat login)
     */
    public function retrieveByCredentials(array $credentials)
    {
        $username = $credentials['id_user'] ?? null;
        $password = $credentials['password'] ?? null;

        if (!$username || !$password) {
            return null;
        }

        // 1. Cek admin
        $adminRow = DB::table('admin')
            ->whereRaw("usere = AES_ENCRYPT(?, 'nur') AND passworde = AES_ENCRYPT(?, 'windi')", [$username, $password])
            ->first();

        if ($adminRow) {
            return $this->createKhanzaUserInstance($adminRow, $username);
        }

        // 2. Cek user
        $userRow = DB::table('user')
            ->whereRaw("id_user = AES_ENCRYPT(?, 'nur') AND password = AES_ENCRYPT(?, 'windi')", [$username, $password])
            ->first();

        if ($userRow) {
            return $this->createKhanzaUserInstance($userRow, $username);
        }

        return null;
    }

    /**
     * Mengambil user berdasarkan ID (di setiap request setelah login)
     */
    public function retrieveById($identifier)
    {
        // $identifier adalah 'id_user' plain-text dari session

        // 1. Cek admin
        $adminRow = DB::table('admin')
            ->whereRaw("usere = AES_ENCRYPT(?, 'nur')", [$identifier])
            ->first();

        if ($adminRow) {
            return $this->createKhanzaUserInstance($adminRow, $identifier);
        }

        // 2. Cek user
        $userRow = DB::table('user')
            ->whereRaw("id_user = AES_ENCRYPT(?, 'nur')", [$identifier])
            ->first();

        if ($userRow) {
            return $this->createKhanzaUserInstance($userRow, $identifier);
        }

        return null;
    }

    /**
     * Validasi user terhadap kredensial.
     */
    public function validateCredentials(UserContract $user, array $credentials): bool
    {
        // retrieveByCredentials sudah memvalidasi, jadi ini selalu true
        // jika user ditemukan.
        return !empty($user->getAuthIdentifier());
    }
}
