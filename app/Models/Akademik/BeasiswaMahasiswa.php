<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class BeasiswaMahasiswa extends Model
{
    use SoftDeletes;
    protected $table = 'beasiswa_mahasiswa';
    protected $guarded = [];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jenisBeasiswa()
    {
        return $this->belongsTo(JenisBeasiswa::class, 'jenis_beasiswa_id');
    }
}
