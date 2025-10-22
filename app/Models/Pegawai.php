<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'nik';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    // Jika mau bisa tambah fillable
    protected $fillable = ['nik', 'nama'];
}
