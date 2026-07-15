<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class SuratTugasMengajar extends Model
{
    use SoftDeletes;
    protected $table = 'surat_tugas_mengajar';
    protected $guarded = [];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }
}
