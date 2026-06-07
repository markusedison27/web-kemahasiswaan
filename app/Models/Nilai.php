<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilai';

    protected $fillable = [
        'krs_id',
        'nilai_angka',
        'nilai_huruf',
        'dosen_id',
    ];

    public function krs()
    {
        return $this->belongsTo(Krs::class, 'krs_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}
