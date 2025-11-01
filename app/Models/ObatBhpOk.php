<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObatBhpOk extends Model
{
    use HasFactory;

    // 1. Tentukan nama tabel
    protected $table = 'obatbhp_ok';

    // 2. Tentukan Primary Key
    protected $primaryKey = 'kd_obat';

    // 3. Beri tahu bahwa PK bukan auto-increment
    public $incrementing = false;

    // 4. Beri tahu bahwa PK adalah string
    protected $keyType = 'string';

    // 5. Beri tahu bahwa tidak ada timestamps
    public $timestamps = false;
}
