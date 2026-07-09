<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class KrsMahasiswa extends Model
{
    use SoftDeletes;

    protected $table = 'krs_mahasiswa';
    protected $guarded = [];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }

    public function dosenPa()
    {
        return $this->belongsTo(User::class, 'dosen_pa_id');
    }

    public function details()
    {
        return $this->hasMany(KrsDetail::class, 'krs_id');
    }
}
