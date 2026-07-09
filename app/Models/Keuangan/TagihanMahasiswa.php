<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Akademik\TahunAkademik;

class TagihanMahasiswa extends Model
{
    use SoftDeletes;

    protected $table = 'tagihan_mahasiswa';
    protected $guarded = [];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }

    public function pembayarans()
    {
        return $this->hasMany(PembayaranMahasiswa::class, 'tagihan_id');
    }
}
