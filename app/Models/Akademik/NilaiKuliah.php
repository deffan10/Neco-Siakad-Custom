<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NilaiKuliah extends Model
{
    use SoftDeletes;

    protected $table = 'nilai_kuliah';
    protected $guarded = [];

    public function kelasMahasiswa()
    {
        return $this->belongsTo(KelasMahasiswa::class, 'kelas_mahasiswa_id');
    }
}
