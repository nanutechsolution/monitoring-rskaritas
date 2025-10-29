<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
