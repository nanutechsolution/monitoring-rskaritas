<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class AdminUser extends Model implements Authenticatable
{
    use AuthenticatableTrait;

    protected $table = 'admin';
    protected $primaryKey = 'usere';
    public $incrementing = false;
    public $timestamps = false;

    // Jangan ada property $password, pakai getter khusus
    public function getAuthPassword()
    {
        return $this->passworde; // kolom sebenarnya
    }

    // Supaya Laravel tidak menaruh property password ketika save()
    // protected $fillable = ['usere', 'passworde'];
}
