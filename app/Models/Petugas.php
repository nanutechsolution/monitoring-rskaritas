<?php

namespace App\Models;

use App\Jabatan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;

    protected $table = 'petugas';
    protected $primaryKey = 'nip';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;


    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'kd_jbtn', 'kd_jbtn');
    }
}
