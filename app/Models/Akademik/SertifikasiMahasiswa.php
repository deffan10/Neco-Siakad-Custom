<?php

namespace App\Models\Akademik;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SertifikasiMahasiswa extends Model
{
    use SoftDeletes;

    protected $table = 'sertifikasi_mahasiswa';
    protected $guarded = [];

    protected $casts = [
        'tanggal_terbit'     => 'date',
        'tanggal_kadaluarsa' => 'date',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
