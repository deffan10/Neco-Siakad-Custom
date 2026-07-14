<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Mahasiswa;

class TugasMahasiswa extends Model
{
    use SoftDeletes;

    protected $table = 'tugas_mahasiswa';
    protected $guarded = [];

    public function bahanTugas()
    {
        return $this->belongsTo(BahanTugasPertemuan::class, 'bahan_tugas_pertemuan_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }
}
