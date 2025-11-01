<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'setting';

    // Tentukan primary key (bukan 'id')
    protected $primaryKey = 'nama_instansi';

    // Beri tahu Eloquent bahwa primary key BUKAN auto-incrementing
    public $incrementing = false;

    // Beri tahu Eloquent bahwa primary key adalah string
    protected $keyType = 'string';

    // Beri tahu Eloquent bahwa tabel ini tidak punya timestamps
    public $timestamps = false;


    /**
     * Helper untuk mengambil satu-satunya baris setting
     * dan menyimpannya di cache agar tidak query berulang.
     */
    public static function instance()
    {
        // Cache data ini selama 1 jam
        return Cache::remember('hospital_setting', 3600, function () {
            return self::first();
        });
    }

    /**
     * Accessor untuk mengubah data LOGO (blob)
     * menjadi format Base64 yang bisa dibaca oleh tag <img> di HTML.
     */
    public function getLogoBase64Attribute()
    {

        if ($this->logo) {
            return 'data:image/png;base64,' . base64_encode($this->logo);
        }
        return null;


    }


}
