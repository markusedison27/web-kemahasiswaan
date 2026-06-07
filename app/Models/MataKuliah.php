<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function krs()
    {
        return $this->hasMany(Krs::class);
    }
}
