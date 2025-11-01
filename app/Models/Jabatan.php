<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    // 1. Beri tahu Eloquent nama tabelnya
    protected $table = 'jabatan';

    // 2. Beri tahu apa Primary Key-nya
    protected $primaryKey = 'kd_jbtn';

    // 3. Beri tahu bahwa Primary Key-nya BUKAN angka
    public $incrementing = false;

    // 4. Beri tahu tipe datanya string
    protected $keyType = 'string';

    // 5. Beri tahu bahwa tabel ini tidak punya timestamps
    public $timestamps = false;
}
