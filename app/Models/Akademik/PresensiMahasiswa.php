<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class PresensiMahasiswa extends Model
{
    use SoftDeletes;

    protected $table = 'presensi_mahasiswa';
    protected $guarded = [];

    public function pertemuan()
    {
        return $this->belongsTo(JadwalPertemuan::class, 'jadwal_pertemuan_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
