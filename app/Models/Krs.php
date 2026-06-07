<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    protected $table = 'krs';

    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'tahun_akademik',
        'semester',
        'status_approval',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function nilai()
    {
        return $this->hasOne(Nilai::class, 'krs_id');
    }
}
