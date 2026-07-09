<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class PendaftaranWisuda extends Model
{
    use SoftDeletes;

    protected $table = 'pendaftaran_wisuda';
    protected $guarded = [];

    public function kegiatanWisuda()
    {
        return $this->belongsTo(KegiatanWisuda::class, 'kegiatan_wisuda_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(DataMahasiswa::class, 'mahasiswa_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
