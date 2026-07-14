<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BahanTugasPertemuan extends Model
{
    use SoftDeletes;

    protected $table = 'bahan_tugas_pertemuan';
    protected $guarded = [];

    public function pertemuan()
    {
        return $this->belongsTo(JadwalPertemuan::class, 'jadwal_pertemuan_id');
    }

    public function submisi()
    {
        return $this->hasMany(TugasMahasiswa::class, 'bahan_tugas_pertemuan_id');
    }
}
